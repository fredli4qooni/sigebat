<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EventBudaya;
use App\Models\PendaftaranEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PesertaEventController extends Controller
{
    /**
     * Tampilkan data monitoring peserta event budaya (SIM)
     */
    public function index(Request $request): View
    {
        $query = PendaftaranEvent::with(['event.kategori'])->latest('id');

        // Filter event tertentu
        if ($eventId = $request->input('event_id')) {
            $query->where('event_budaya_id', $eventId);
        }

        // Filter status kehadiran
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter pencarian teks
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search): void {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('kode_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('nomor_telepon', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('asal_instansi', 'like', "%{$search}%");
            });
        }

        // Hitung metrik ringkasan
        $baseMetricsQuery = clone $query;
        $totalPendaftar = (clone $baseMetricsQuery)->count();
        $totalOrang = (int) (clone $baseMetricsQuery)->where('status', '!=', 'batal')->sum('jumlah_peserta');
        $totalHadir = (int) (clone $baseMetricsQuery)->where('status', 'hadir')->sum('jumlah_peserta');
        $totalBatal = (clone $baseMetricsQuery)->where('status', 'batal')->count();

        $pesertaList = $query->paginate(15)->withQueryString();
        $eventList = EventBudaya::orderBy('tanggal_mulai', 'desc')->get();

        $selectedEvent = $eventId ? EventBudaya::find($eventId) : null;

        return view('pengelola.peserta.index', compact(
            'pesertaList',
            'eventList',
            'selectedEvent',
            'totalPendaftar',
            'totalOrang',
            'totalHadir',
            'totalBatal'
        ));
    }

    /**
     * Perbarui status kehadiran peserta (Presensi di Lokasi / Hari-H)
     */
    public function updateStatus(Request $request, PendaftaranEvent $peserta): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['terdaftar', 'hadir', 'batal'])],
        ]);

        $oldStatus = $peserta->status;
        $peserta->update(['status' => $validated['status']]);

        ActivityLog::log(
            aksi: 'UPDATE_STATUS_PESERTA',
            entitasTipe: 'PendaftaranEvent',
            entitasId: $peserta->id,
            keterangan: [
                'kode' => $peserta->kode_pendaftaran,
                'nama' => $peserta->nama_lengkap,
                'status_lama' => $oldStatus,
                'status_baru' => $peserta->status,
            ]
        );

        return back()->with(
            'success',
            "Status pendaftaran {$peserta->nama_lengkap} ({$peserta->kode_pendaftaran}) berhasil diperbarui menjadi {$peserta->status_label}."
        );
    }

    /**
     * Hapus data pendaftaran peserta
     */
    public function destroy(PendaftaranEvent $peserta): RedirectResponse
    {
        $kode = $peserta->kode_pendaftaran;
        $nama = $peserta->nama_lengkap;
        $id = $peserta->id;

        $peserta->delete();

        ActivityLog::log(
            aksi: 'DELETE_PESERTA',
            entitasTipe: 'PendaftaranEvent',
            entitasId: $id,
            keterangan: ['kode' => $kode, 'nama' => $nama]
        );

        return back()->with('success', "Data pendaftaran {$nama} ({$kode}) berhasil dihapus.");
    }

    /**
     * Cetak lembar presensi dan daftar hadir kegiatan resmi (Print-Friendly A4)
     */
    public function cetak(EventBudaya $event): View
    {
        $pesertaList = $event->pendaftar()
            ->where('status', '!=', 'batal')
            ->orderBy('created_at')
            ->get();

        return view('pengelola.peserta.cetak', compact('event', 'pesertaList'));
    }
}
