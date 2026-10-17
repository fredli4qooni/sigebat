<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Fasilitas;
use App\Models\PenyewaanFasilitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenyewaanFasilitasController extends Controller
{
    /**
     * Dashboard monitoring reservasi dan keuangan penyewaan fasilitas desa
     */
    public function index(Request $request): View
    {
        $query = PenyewaanFasilitas::with(['fasilitas.jenis', 'verifier'])
            ->latest('id');

        // Filter fasilitas
        if ($request->filled('fasilitas_id')) {
            $query->where('fasilitas_id', $request->input('fasilitas_id'));
        }

        // Filter status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->input('status_pembayaran'));
        }

        // Filter status operasional sewa
        if ($request->filled('status_sewa')) {
            $query->where('status_sewa', $request->input('status_sewa'));
        }

        // Pencarian teks (kode, nama penyewa, email, telepon)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search): void {
                $q->where('kode_sewa', 'LIKE', "%{$search}%")
                    ->orWhere('nama_penyewa', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_telepon', 'LIKE', "%{$search}%");
            });
        }

        $penyewaanList = $query->paginate(15)->withQueryString();

        // Statistik Metrik SIM Penyewaan
        $allBookings = PenyewaanFasilitas::query();
        if ($request->filled('fasilitas_id')) {
            $allBookings->where('fasilitas_id', $request->input('fasilitas_id'));
        }

        $metrics = [
            'total_kas_sewa' => (int) (clone $allBookings)->where('status_pembayaran', 'lunas')->sum('total_tagihan'),
            'menunggu_verifikasi' => (int) (clone $allBookings)->where('status_pembayaran', 'menunggu_verifikasi')->count(),
            'menunggu_pembayaran' => (int) (clone $allBookings)->where('status_pembayaran', 'menunggu_pembayaran')->count(),
            'total_booking_lunas' => (int) (clone $allBookings)->where('status_pembayaran', 'lunas')->count(),
            'sedang_digunakan' => (int) (clone $allBookings)->where('status_sewa', 'digunakan')->count(),
        ];

        $fasilitasList = Fasilitas::where('bisa_disewa', true)->orderBy('nama')->get();

        return view('pengelola.penyewaan.index', compact('penyewaanList', 'fasilitasList', 'metrics'));
    }

    /**
     * Otorisasi verifikasi pembayaran sewa fasilitas (Setujui / Tolak)
     */
    public function verifikasi(Request $request, PenyewaanFasilitas $sewa): RedirectResponse
    {
        $validated = $request->validate([
            'aksi' => ['required', 'in:setujui,tolak'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'aksi.required' => 'Pilih tindakan verifikasi pembayaran.',
            'aksi.in' => 'Tindakan verifikasi tidak valid.',
        ]);

        if ($validated['aksi'] === 'setujui') {
            $sewa->update([
                'status_pembayaran' => 'lunas',
                'status_sewa' => $sewa->status_sewa === 'diajukan' ? 'dikonfirmasi' : $sewa->status_sewa,
                'diverifikasi_oleh' => auth()->id(),
                'waktu_verifikasi' => now(),
                'catatan_verifikasi' => $validated['catatan'] ?: 'Pembayaran sewa diverifikasi lunas oleh pengelola desa.',
            ]);

            ActivityLog::log(
                aksi: 'VERIFIKASI_SEWA_DISETUJUI',
                entitasTipe: 'PenyewaanFasilitas',
                entitasId: $sewa->id,
                keterangan: [
                    'kode' => $sewa->kode_sewa,
                    'total_tagihan' => $sewa->total_tagihan,
                    'admin_id' => auth()->id(),
                    'admin_name' => auth()->user()->name,
                ],
                userId: auth()->id()
            );

            return back()->with('success', "Pembayaran reservasi sewa '{$sewa->kode_sewa}' berhasil disetujui & diverifikasi LUNAS.");
        }

        $sewa->update([
            'status_pembayaran' => 'ditolak',
            'diverifikasi_oleh' => auth()->id(),
            'waktu_verifikasi' => now(),
            'catatan_verifikasi' => $validated['catatan'] ?: 'Bukti pembayaran tidak valid atau dana belum diterima pada rekening BUMDes.',
        ]);

        ActivityLog::log(
            aksi: 'VERIFIKASI_SEWA_DITOLAK',
            entitasTipe: 'PenyewaanFasilitas',
            entitasId: $sewa->id,
            keterangan: [
                'kode' => $sewa->kode_sewa,
                'alasan' => $sewa->catatan_verifikasi,
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
            ],
            userId: auth()->id()
        );

        return back()->with('warning', "Pembayaran reservasi sewa '{$sewa->kode_sewa}' telah ditolak dengan catatan penolakan.");
    }

    /**
     * Update status operasional sewa fasilitas (Check-in, Selesai, Batal)
     */
    public function updateStatus(Request $request, PenyewaanFasilitas $sewa): RedirectResponse
    {
        $validated = $request->validate([
            'status_sewa' => ['required', 'in:diajukan,dikonfirmasi,digunakan,selesai,batal'],
        ], [
            'status_sewa.required' => 'Pilih status operasional sewa.',
            'status_sewa.in' => 'Status operasional sewa tidak valid.',
        ]);

        $statusLama = $sewa->status_sewa;
        $sewa->update(['status_sewa' => $validated['status_sewa']]);

        ActivityLog::log(
            aksi: 'UPDATE_STATUS_SEWA',
            entitasTipe: 'PenyewaanFasilitas',
            entitasId: $sewa->id,
            keterangan: [
                'kode' => $sewa->kode_sewa,
                'status_lama' => $statusLama,
                'status_baru' => $sewa->status_sewa,
                'admin_name' => auth()->user()->name,
            ],
            userId: auth()->id()
        );

        return back()->with('success', "Status operasional sewa '{$sewa->kode_sewa}' berhasil diubah menjadi: {$sewa->status_sewa_label}.");
    }

    /**
     * Cetak rekapitulasi laporan penyewaan & kas fasilitas (Format A4 Resmi)
     */
    public function cetakLaporan(Request $request): View
    {
        $query = PenyewaanFasilitas::with(['fasilitas.jenis', 'verifier'])
            ->latest('tanggal_mulai');

        if ($request->filled('fasilitas_id')) {
            $query->where('fasilitas_id', $request->input('fasilitas_id'));
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->input('status_pembayaran'));
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_mulai', '>=', $request->input('tanggal_dari'));
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->input('tanggal_sampai'));
        }

        $penyewaanList = $query->get();

        $totalKas = $penyewaanList->where('status_pembayaran', 'lunas')->sum('total_tagihan');
        $totalDurasiHari = $penyewaanList->where('status_pembayaran', 'lunas')->sum('durasi_hari');
        $fasilitasTerpilih = $request->filled('fasilitas_id') ? Fasilitas::find($request->input('fasilitas_id')) : null;

        return view('pengelola.penyewaan.cetak', compact('penyewaanList', 'totalKas', 'totalDurasiHari', 'fasilitasTerpilih'));
    }

    /**
     * Hapus data booking sewa (Soft Delete)
     */
    public function destroy(PenyewaanFasilitas $sewa): RedirectResponse
    {
        $kode = $sewa->kode_sewa;
        $id = $sewa->id;
        $sewa->delete();

        ActivityLog::log(
            aksi: 'DELETE_SEWA',
            entitasTipe: 'PenyewaanFasilitas',
            entitasId: $id,
            keterangan: ['kode' => $kode]
        );

        return back()->with('success', "Data penyewaan '{$kode}' berhasil dihapus.");
    }
}
