<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EventBudaya;
use App\Models\PendaftaranEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembayaranEventController extends Controller
{
    /**
     * Dashboard monitoring dan verifikasi transaksi pembayaran event
     */
    public function index(Request $request): View
    {
        $query = PendaftaranEvent::with(['event.kategori', 'verifier'])
            ->latest('id');

        // Filter event
        if ($request->filled('event_id')) {
            $query->where('event_budaya_id', $request->input('event_id'));
        }

        // Filter status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->input('status_pembayaran'));
        }

        // Filter metode pembayaran
        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->input('metode'));
        }

        // Pencarian teks (kode, nama, email, nomor HP)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('kode_pendaftaran', 'LIKE', "%{$search}%")
                    ->orWhere('nama_lengkap', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_telepon', 'LIKE', "%{$search}%");
            });
        }

        $pembayaranList = $query->paginate(15)->withQueryString();

        // Kartu Statistik Keuangan SIM
        $allTransactions = PendaftaranEvent::query();
        if ($request->filled('event_id')) {
            $allTransactions->where('event_budaya_id', $request->input('event_id'));
        }

        $metrics = [
            'total_kas_masuk' => (int) (clone $allTransactions)->where('status_pembayaran', 'lunas')->sum('total_tagihan'),
            'total_menunggu_verifikasi' => (int) (clone $allTransactions)->where('status_pembayaran', 'menunggu_verifikasi')->count(),
            'total_menunggu_pembayaran' => (int) (clone $allTransactions)->where('status_pembayaran', 'menunggu_pembayaran')->count(),
            'total_transaksi_lunas' => (int) (clone $allTransactions)->where('status_pembayaran', 'lunas')->count(),
            'total_tiket_terjual' => (int) (clone $allTransactions)->where('status_pembayaran', 'lunas')->sum('jumlah_peserta'),
        ];

        $events = EventBudaya::orderBy('tanggal_mulai', 'desc')->get();

        return view('pengelola.pembayaran.index', compact('pembayaranList', 'events', 'metrics'));
    }

    /**
     * Otorisasi verifikasi pembayaran oleh pengelola (Setujui / Tolak)
     */
    public function verifikasi(Request $request, PendaftaranEvent $peserta): RedirectResponse
    {
        $validated = $request->validate([
            'aksi' => ['required', 'in:setujui,tolak'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'aksi.required' => 'Pilih tindakan verifikasi pembayaran.',
            'aksi.in' => 'Tindakan verifikasi tidak valid.',
        ]);

        if ($validated['aksi'] === 'setujui') {
            $peserta->update([
                'status_pembayaran' => 'lunas',
                'diverifikasi_oleh' => auth()->id(),
                'waktu_verifikasi' => now(),
                'catatan_verifikasi' => $validated['catatan'] ?: 'Pembayaran telah diverifikasi sah oleh pengelola desa.',
            ]);

            ActivityLog::log(
                aksi: 'VERIFIKASI_PEMBAYARAN_DISETUJUI',
                entitasTipe: 'PendaftaranEvent',
                entitasId: $peserta->id,
                keterangan: [
                    'kode' => $peserta->kode_pendaftaran,
                    'total_tagihan' => $peserta->total_tagihan,
                    'admin_id' => auth()->id(),
                    'admin_name' => auth()->user()->name,
                ],
                userId: auth()->id()
            );

            return back()->with('success', "Pembayaran untuk registrasi [{$peserta->kode_pendaftaran}] berhasil disetujui (LUNAS).");
        }

        // Aksi Tolak
        $peserta->update([
            'status_pembayaran' => 'ditolak',
            'diverifikasi_oleh' => auth()->id(),
            'waktu_verifikasi' => now(),
            'catatan_verifikasi' => $validated['catatan'] ?: 'Bukti pembayaran tidak valid atau nominal transfer tidak sesuai.',
        ]);

        ActivityLog::log(
            aksi: 'VERIFIKASI_PEMBAYARAN_DITOLAK',
            entitasTipe: 'PendaftaranEvent',
            entitasId: $peserta->id,
            keterangan: [
                'kode' => $peserta->kode_pendaftaran,
                'alasan' => $peserta->catatan_verifikasi,
                'admin_id' => auth()->id(),
            ],
            userId: auth()->id()
        );

        return back()->with('warning', "Pembayaran untuk registrasi [{$peserta->kode_pendaftaran}] telah ditolak.");
    }

    /**
     * Lembar cetak laporan rekapitulasi keuangan kas event budaya
     */
    public function cetakLaporan(Request $request): View
    {
        $query = PendaftaranEvent::with(['event.kategori', 'verifier'])
            ->where('status_pembayaran', 'lunas')
            ->orderBy('waktu_pembayaran', 'asc');

        $selectedEvent = null;
        if ($request->filled('event_id')) {
            $selectedEvent = EventBudaya::findOrFail($request->input('event_id'));
            $query->where('event_budaya_id', $selectedEvent->id);
        }

        $laporanList = $query->get();
        $totalPemasukan = $laporanList->sum('total_tagihan');
        $totalTiket = $laporanList->sum('jumlah_peserta');

        return view('pengelola.pembayaran.cetak', compact('laporanList', 'selectedEvent', 'totalPemasukan', 'totalTiket'));
    }
}
