<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Fasilitas;
use App\Models\PengaturanPembayaran;
use App\Models\PenyewaanFasilitas;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PenyewaanFasilitasController extends Controller
{
    /**
     * Tampilkan formulir pemesanan / sewa fasilitas
     */
    public function create(Fasilitas $fasilitas): View|RedirectResponse
    {
        if (! $fasilitas->bisa_disewa || $fasilitas->status_ketersediaan !== 'tersedia') {
            return redirect()
                ->route('fasilitas.index')
                ->with('error', 'Fasilitas ini saat ini tidak dibuka untuk penyewaan umum.');
        }

        // Ambil riwayat sewa aktif untuk info kalender ketersediaan
        $bookingAktif = $fasilitas->penyewaan()
            ->whereNotIn('status_sewa', ['batal'])
            ->where('status_pembayaran', '!=', 'ditolak')
            ->where('tanggal_selesai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai')
            ->get(['tanggal_mulai', 'tanggal_selesai']);

        return view('fasilitas.sewa', compact('fasilitas', 'bookingAktif'));
    }

    /**
     * Simpan reservasi sewa fasilitas baru
     */
    public function store(Request $request, Fasilitas $fasilitas): RedirectResponse
    {
        if (! $fasilitas->bisa_disewa || $fasilitas->status_ketersediaan !== 'tersedia') {
            return redirect()
                ->route('fasilitas.index')
                ->with('error', 'Fasilitas ini saat ini tidak dibuka untuk penyewaan umum.');
        }

        $maxKapasitas = $fasilitas->kapasitas ?: 100;

        $validated = $request->validate([
            'nama_penyewa' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'nomor_telepon' => ['required', 'string', 'min:8', 'max:25'],
            'asal_instansi' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jumlah_tamu' => ['required', 'integer', 'min:1', "max:{$maxKapasitas}"],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'nama_penyewa.required' => 'Nama lengkap penyewa / penanggung jawab wajib diisi.',
            'email.required' => 'Alamat email aktif wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'nomor_telepon.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal awal sewa wajib ditentukan.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh di masa lampau.',
            'tanggal_selesai.required' => 'Tanggal akhir sewa wajib ditentukan.',
            'tanggal_selesai.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
            'jumlah_tamu.required' => 'Jumlah tamu wajib diisi.',
            'jumlah_tamu.max' => "Jumlah tamu melebihi kapasitas maksimal fasilitas ({$maxKapasitas} orang).",
        ]);

        // Validasi Anti Double-Booking
        if (! $fasilitas->isTersediaRentang($validated['tanggal_mulai'], $validated['tanggal_selesai'])) {
            return back()
                ->withErrors([
                    'tanggal_mulai' => 'Mohon maaf, fasilitas ini sudah terisi atau dipesan pada rentang tanggal yang Anda pilih. Silakan tentukan tanggal lain.',
                ])
                ->withInput();
        }

        // Hitung durasi dan tagihan sewa harian
        $start = Carbon::parse($validated['tanggal_mulai']);
        $end = Carbon::parse($validated['tanggal_selesai']);
        $durasiHari = (int) $start->diffInDays($end) + 1;

        $hargaPerHari = (int) $fasilitas->harga_sewa_per_hari;
        $totalTagihan = $durasiHari * $hargaPerHari;

        $penyewaan = DB::transaction(function () use ($fasilitas, $validated, $durasiHari, $hargaPerHari, $totalTagihan) {
            $record = PenyewaanFasilitas::create([
                'fasilitas_id' => $fasilitas->id,
                'nama_penyewa' => trim($validated['nama_penyewa']),
                'email' => strtolower(trim($validated['email'])),
                'nomor_telepon' => trim($validated['nomor_telepon']),
                'asal_instansi' => ! empty($validated['asal_instansi']) ? trim($validated['asal_instansi']) : null,
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'durasi_hari' => $durasiHari,
                'jumlah_tamu' => (int) $validated['jumlah_tamu'],
                'harga_per_hari' => $hargaPerHari,
                'total_tagihan' => $totalTagihan,
                'catatan' => ! empty($validated['catatan']) ? trim($validated['catatan']) : null,
                'status_sewa' => 'diajukan',
                'status_pembayaran' => 'menunggu_pembayaran',
            ]);

            ActivityLog::log(
                aksi: 'BOOKING_FASILITAS',
                entitasTipe: 'PenyewaanFasilitas',
                entitasId: $record->id,
                keterangan: [
                    'kode' => $record->kode_sewa,
                    'fasilitas' => $fasilitas->nama,
                    'durasi_hari' => $durasiHari,
                    'total_tagihan' => $totalTagihan,
                    'nama_penyewa' => $record->nama_penyewa,
                ],
                userId: null
            );

            return $record;
        });

        return redirect()
            ->route('sewa.bayar', $penyewaan->kode_sewa)
            ->with('info', 'Permohonan sewa berhasil diajukan! Silakan selesaikan pembayaran ke rekening kas desa berikut.');
    }

    /**
     * Halaman instruksi dan formulir pembayaran sewa fasilitas
     */
    public function bayar(string $kode): View|RedirectResponse
    {
        $penyewaan = PenyewaanFasilitas::with(['fasilitas.jenis', 'fasilitas.objekWisata'])
            ->where('kode_sewa', $kode)
            ->firstOrFail();

        if ($penyewaan->is_lunas) {
            return redirect()
                ->route('sewa.bukti', $penyewaan->kode_sewa)
                ->with('info', 'Pembayaran sewa telah berstatus Lunas.');
        }

        $setting = PengaturanPembayaran::getSetting();

        return view('fasilitas.bayar', compact('penyewaan', 'setting'));
    }

    /**
     * Proses unggah bukti pembayaran transfer / QRIS oleh penyewa
     */
    public function prosesBayar(Request $request, string $kode): RedirectResponse
    {
        $penyewaan = PenyewaanFasilitas::where('kode_sewa', $kode)->firstOrFail();

        if ($penyewaan->is_lunas) {
            return redirect()
                ->route('sewa.bukti', $penyewaan->kode_sewa)
                ->with('info', 'Pembayaran sewa sudah terverifikasi Lunas.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:transfer_bank,qris'],
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ], [
            'metode_pembayaran.required' => 'Pilih metode pembayaran yang digunakan.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'bukti_pembayaran.required' => 'Unggah struk bukti transfer atau tangkapan layar pembayaran.',
            'bukti_pembayaran.image' => 'Berkas bukti pembayaran harus berupa file gambar.',
            'bukti_pembayaran.max' => 'Ukuran berkas maksimal 3 MB.',
        ]);

        if ($penyewaan->bukti_pembayaran && Storage::disk('public')->exists($penyewaan->bukti_pembayaran)) {
            Storage::disk('public')->delete($penyewaan->bukti_pembayaran);
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_sewa', 'public');

        $penyewaan->update([
            'status_pembayaran' => 'menunggu_verifikasi',
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'bukti_pembayaran' => $path,
            'waktu_pembayaran' => now(),
            'catatan_verifikasi' => null,
        ]);

        ActivityLog::log(
            aksi: 'UPLOAD_BUKTI_SEWA',
            entitasTipe: 'PenyewaanFasilitas',
            entitasId: $penyewaan->id,
            keterangan: [
                'kode' => $penyewaan->kode_sewa,
                'metode' => $penyewaan->metode_pembayaran,
                'total_tagihan' => $penyewaan->total_tagihan,
            ],
            userId: null
        );

        return redirect()
            ->route('sewa.bukti', $penyewaan->kode_sewa)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Pengelola akan segera memverifikasi transaksi sewa Anda.');
    }

    /**
     * Tampilkan voucher / tanda bukti pemesanan sewa fasilitas
     */
    public function bukti(string $kode): View
    {
        $penyewaan = PenyewaanFasilitas::with(['fasilitas.jenis', 'fasilitas.objekWisata', 'verifier'])
            ->where('kode_sewa', $kode)
            ->firstOrFail();

        return view('fasilitas.bukti-sewa', compact('penyewaan'));
    }

    /**
     * Pelacakan status sewa mandiri bagi wisatawan
     */
    public function cek(Request $request): View|RedirectResponse
    {
        $q = trim((string) $request->input('q', ''));
        $hasil = collect();
        $isSearching = $request->has('q');

        if ($q !== '') {
            $request->validate([
                'q' => ['required', 'string', 'min:3', 'max:100'],
            ], [
                'q.min' => 'Kata kunci pencarian minimal 3 karakter.',
                'q.max' => 'Kata kunci pencarian maksimal 100 karakter.',
            ]);

            $kodeFormatted = strtoupper($q);
            $sewaTepat = PenyewaanFasilitas::where('kode_sewa', $kodeFormatted)->first();
            if ($sewaTepat) {
                return redirect()->route('sewa.bukti', $sewaTepat->kode_sewa);
            }

            $cleanPhone = preg_replace('/[^0-9]/', '', $q);

            $hasil = PenyewaanFasilitas::with(['fasilitas.jenis'])
                ->where(function ($query) use ($q, $cleanPhone) {
                    $query->where('kode_sewa', 'LIKE', "%{$q}%")
                        ->orWhere('email', 'LIKE', "%{$q}%")
                        ->orWhere('nama_penyewa', 'LIKE', "%{$q}%");

                    if ($cleanPhone !== '' && strlen($cleanPhone) >= 4) {
                        $query->orWhere('nomor_telepon', 'LIKE', "%{$cleanPhone}%");
                    }
                })
                ->latest()
                ->take(20)
                ->get();
        }

        return view('fasilitas.cek-sewa', compact('hasil', 'q', 'isSearching'));
    }
}
