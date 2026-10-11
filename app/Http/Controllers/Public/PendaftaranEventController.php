<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EventBudaya;
use App\Models\PendaftaranEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PendaftaranEventController extends Controller
{
    /**
     * Simpan pendaftaran peserta event budaya dari form publik
     */
    public function store(Request $request, string $slug): RedirectResponse
    {
        $event = EventBudaya::where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        // 1. Cek apakah pendaftaran masih dibuka
        if (! $event->is_pendaftaran_bisa_dilakukan) {
            $pesan = match (true) {
                $event->status_turunan === 'Selesai' => 'Pendaftaran ditutup karena kegiatan telah selesai dilaksanakan.',
                $event->is_kuota_penuh => 'Mohon maaf, kuota pendaftaran untuk event ini telah terpenuhi.',
                ! $event->buka_pendaftaran => 'Pendaftaran daring untuk kegiatan ini sedang dinonaktifkan.',
                default => 'Mohon maaf, pendaftaran untuk event ini saat ini tidak tersedia.',
            };

            return back()->with('error', $pesan);
        }

        // 2. Validasi input
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'nomor_telepon' => ['required', 'string', 'min:8', 'max:25'],
            'asal_instansi' => ['required', 'string', 'max:100'],
            'jumlah_peserta' => ['required', 'integer', 'min:1', 'max:20'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'asal_instansi.required' => 'Asal instansi atau kota asal wajib diisi.',
            'jumlah_peserta.required' => 'Jumlah peserta wajib diisi.',
            'jumlah_peserta.min' => 'Jumlah peserta minimal 1 orang.',
            'jumlah_peserta.max' => 'Pendaftaran online maksimal 20 orang per formulir.',
        ]);

        // 3. Validasi kuota atomik
        if (! is_null($event->sisa_kuota) && $validated['jumlah_peserta'] > $event->sisa_kuota) {
            return back()
                ->withErrors([
                    'jumlah_peserta' => "Jumlah peserta yang diajukan ({$validated['jumlah_peserta']} orang) melebihi sisa kuota yang tersedia ({$event->sisa_kuota} orang).",
                ])
                ->withInput();
        }

        // 4. Hitung biaya pembayaran
        $isBerbayar = $event->is_berbayar;
        $nominalPerTiket = $isBerbayar ? $event->harga_tiket : 0;
        $totalTagihan = (int) $validated['jumlah_peserta'] * $nominalPerTiket;
        $statusPembayaran = $isBerbayar ? 'menunggu_pembayaran' : 'gratis';

        // 5. Simpan dalam database transaction
        $pendaftaran = DB::transaction(function () use ($event, $validated, $nominalPerTiket, $totalTagihan, $statusPembayaran) {
            $record = PendaftaranEvent::create([
                'event_budaya_id' => $event->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => strtolower(trim($validated['email'])),
                'nomor_telepon' => trim($validated['nomor_telepon']),
                'asal_instansi' => trim($validated['asal_instansi']),
                'jumlah_peserta' => (int) $validated['jumlah_peserta'],
                'catatan' => ! empty($validated['catatan']) ? trim($validated['catatan']) : null,
                'status' => 'terdaftar',
                'nominal_per_tiket' => $nominalPerTiket,
                'total_tagihan' => $totalTagihan,
                'status_pembayaran' => $statusPembayaran,
            ]);

            ActivityLog::log(
                aksi: 'PENDAFTARAN_EVENT',
                entitasTipe: 'PendaftaranEvent',
                entitasId: $record->id,
                keterangan: [
                    'kode' => $record->kode_pendaftaran,
                    'nama' => $record->nama_lengkap,
                    'event_id' => $event->id,
                    'event_judul' => $event->judul,
                    'jumlah_peserta' => $record->jumlah_peserta,
                    'total_tagihan' => $record->total_tagihan,
                    'status_pembayaran' => $record->status_pembayaran,
                ],
                userId: null
            );

            return $record;
        });

        if ($isBerbayar) {
            return redirect()
                ->route('event.pendaftaran.bayar', $pendaftaran->kode_pendaftaran)
                ->with('info', 'Pendaftaran Anda berhasil dicatat! Silakan lakukan konfirmasi pembayaran berikut untuk menyelesaikan tiket.');
        }

        return redirect()
            ->route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran)
            ->with('success', 'Pendaftaran Anda berhasil dicatat! Simpan tanda bukti pendaftaran berikut untuk ditunjukkan kepada pengelola.');
    }

    /**
     * Halaman instruksi pembayaran dan upload bukti transfer
     */
    public function bayar(string $kode): View|RedirectResponse
    {
        $pendaftaran = PendaftaranEvent::with(['event.kategori'])
            ->where('kode_pendaftaran', $kode)
            ->firstOrFail();

        // Jika pendaftaran sudah lunas atau gratis, arahkan langsung ke lembar bukti
        if ($pendaftaran->is_lunas) {
            return redirect()
                ->route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran)
                ->with('info', 'Pembayaran untuk pendaftaran ini telah terverifikasi lunas.');
        }

        return view('kalender.bayar', compact('pendaftaran'));
    }

    /**
     * Proses pengunggahan bukti transfer pembayaran
     */
    public function prosesBayar(Request $request, string $kode): RedirectResponse
    {
        $pendaftaran = PendaftaranEvent::where('kode_pendaftaran', $kode)->firstOrFail();

        if ($pendaftaran->is_lunas) {
            return redirect()
                ->route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran)
                ->with('info', 'Pembayaran telah terverifikasi lunas.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:transfer_bank,qris'],
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ], [
            'metode_pembayaran.required' => 'Pilih metode pembayaran yang Anda gunakan.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'bukti_pembayaran.required' => 'Unggah foto struk ATM atau tangkapan layar bukti transfer.',
            'bukti_pembayaran.image' => 'Berkas bukti pembayaran harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'bukti_pembayaran.max' => 'Ukuran berkas maksimal 3 MB.',
        ]);

        // Hapus file lama jika ada penggantian bukti
        if ($pendaftaran->bukti_pembayaran && Storage::disk('public')->exists($pendaftaran->bukti_pembayaran)) {
            Storage::disk('public')->delete($pendaftaran->bukti_pembayaran);
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $pendaftaran->update([
            'status_pembayaran' => 'menunggu_verifikasi',
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'bukti_pembayaran' => $path,
            'waktu_pembayaran' => now(),
            'catatan_verifikasi' => null, // reset catatan penolakan lama jika ada
        ]);

        ActivityLog::log(
            aksi: 'UPLOAD_BUKTI_PEMBAYARAN',
            entitasTipe: 'PendaftaranEvent',
            entitasId: $pendaftaran->id,
            keterangan: [
                'kode' => $pendaftaran->kode_pendaftaran,
                'metode' => $pendaftaran->metode_pembayaran,
                'total_tagihan' => $pendaftaran->total_tagihan,
            ],
            userId: null
        );

        return redirect()
            ->route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Pengelola akan segera memverifikasi transaksi Anda.');
    }

    /**
     * Mode simulasi instan untuk pengujian / presentasi sidang skripsi
     */
    public function simulasiBayar(Request $request, string $kode): RedirectResponse
    {
        $pendaftaran = PendaftaranEvent::where('kode_pendaftaran', $kode)->firstOrFail();

        $pendaftaran->update([
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => 'simulasi_demo',
            'waktu_pembayaran' => now(),
            'waktu_verifikasi' => now(),
            'catatan_verifikasi' => 'Terverifikasi otomatis melalui mode Sandbox Demo Sidang.',
        ]);

        ActivityLog::log(
            aksi: 'SIMULASI_PEMBAYARAN_DEMO',
            entitasTipe: 'PendaftaranEvent',
            entitasId: $pendaftaran->id,
            keterangan: [
                'kode' => $pendaftaran->kode_pendaftaran,
                'total_tagihan' => $pendaftaran->total_tagihan,
            ],
            userId: null
        );

        return redirect()
            ->route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran)
            ->with('success', 'Simulasi pembayaran berhasil! Tiket Anda kini telah berstatus LUNAS.');
    }

    /**
     * Tampilkan tanda bukti pendaftaran resmi (E-Ticket / Bukti Partisipasi)
     */
    public function bukti(string $kode): View
    {
        $pendaftaran = PendaftaranEvent::with(['event.kategori', 'verifier'])
            ->where('kode_pendaftaran', $kode)
            ->firstOrFail();

        return view('kalender.bukti-pendaftaran', compact('pendaftaran'));
    }

    /**
     * Halaman pencarian dan pelacakan bukti pendaftaran mandiri (Self-Service)
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

            // Jika kata kunci adalah kode pendaftaran tepat, langsung arahkan ke lembar bukti
            $kodeFormatted = strtoupper($q);
            $pendaftaranTepat = PendaftaranEvent::where('kode_pendaftaran', $kodeFormatted)->first();
            if ($pendaftaranTepat) {
                return redirect()->route('event.pendaftaran.bukti', $pendaftaranTepat->kode_pendaftaran);
            }

            // Pencarian fleksibel: Kode pendaftaran, Email, Nama Lengkap, atau Nomor Telepon
            $cleanPhone = preg_replace('/[^0-9]/', '', $q);

            $hasil = PendaftaranEvent::with(['event.kategori'])
                ->where(function ($query) use ($q, $cleanPhone) {
                    $query->where('kode_pendaftaran', 'LIKE', "%{$q}%")
                        ->orWhere('email', 'LIKE', "%{$q}%")
                        ->orWhere('nama_lengkap', 'LIKE', "%{$q}%");

                    if ($cleanPhone !== '' && strlen($cleanPhone) >= 4) {
                        $query->orWhere('nomor_telepon', 'LIKE', "%{$cleanPhone}%");
                    }
                })
                ->latest()
                ->take(20)
                ->get();
        }

        return view('kalender.cek-pendaftaran', compact('hasil', 'q', 'isSearching'));
    }
}
