<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EventBudaya;
use App\Models\PendaftaranEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // 4. Simpan dalam database transaction
        $pendaftaran = DB::transaction(function () use ($event, $validated) {
            $record = PendaftaranEvent::create([
                'event_budaya_id' => $event->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => strtolower(trim($validated['email'])),
                'nomor_telepon' => trim($validated['nomor_telepon']),
                'asal_instansi' => trim($validated['asal_instansi']),
                'jumlah_peserta' => (int) $validated['jumlah_peserta'],
                'catatan' => $validated['catatan'] ? trim($validated['catatan']) : null,
                'status' => 'terdaftar',
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
                ],
                userId: null
            );

            return $record;
        });

        return redirect()
            ->route('event.pendaftaran.bukti', $pendaftaran->kode_pendaftaran)
            ->with('success', 'Pendaftaran Anda berhasil dicatat! Simpan tanda bukti pendaftaran berikut untuk ditunjukkan kepada pengelola.');
    }

    /**
     * Tampilkan tanda bukti pendaftaran resmi (E-Ticket / Bukti Partisipasi)
     */
    public function bukti(string $kode): View
    {
        $pendaftaran = PendaftaranEvent::with(['event.kategori'])
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
