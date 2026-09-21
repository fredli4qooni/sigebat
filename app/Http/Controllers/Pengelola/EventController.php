<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EventBudaya;
use App\Models\KategoriEvent;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $query = EventBudaya::with(['kategori', 'creator'])->latest('tanggal_mulai');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search): void {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategoriId = $request->input('kategori_id')) {
            $query->where('kategori_event_id', $kategoriId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($statusTurunan = $request->input('status_turunan')) {
            $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            if ($statusTurunan === 'akan_datang') {
                $query->where('tanggal_mulai', '>', $today);
            } elseif ($statusTurunan === 'berlangsung') {
                $query->where('tanggal_mulai', '<=', $today)
                    ->where('tanggal_selesai', '>=', $today);
            } elseif ($statusTurunan === 'selesai') {
                $query->where('tanggal_selesai', '<', $today);
            }
        }

        $eventList = $query->paginate(10)->withQueryString();
        $kategoriList = KategoriEvent::orderBy('nama')->get();

        return view('pengelola.event.index', compact('eventList', 'kategoriList'));
    }

    public function create(): View
    {
        $kategoriList = KategoriEvent::orderBy('nama')->get();

        return view('pengelola.event.create', compact('kategoriList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori_event_id' => ['required', 'exists:kategori_event,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['nullable', 'string', 'max:20'],
            'jam_selesai' => ['nullable', 'string', 'max:20'],
            'lokasi' => ['required', 'string', 'max:500'],
            'deskripsi' => ['required', 'string'],
            'poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'status' => ['required', Rule::in(['aktif', 'pending', 'nonaktif'])],
        ], [
            'judul.required' => 'Judul event budaya wajib diisi.',
            'kategori_event_id.required' => 'Pilih kategori event.',
            'tanggal_mulai.required' => 'Tanggal mulai pelaksanaan wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai pelaksanaan wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'lokasi.required' => 'Lokasi tempat pelaksanaan event wajib diisi.',
            'deskripsi.required' => 'Deskripsi event budaya wajib diisi.',
            'poster.max' => 'Ukuran poster maksimal adalah 3 MB.',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('event', 'public');
        }

        $event = EventBudaya::create([
            'judul' => $validated['judul'],
            'kategori_event_id' => $validated['kategori_event_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jam_mulai' => $validated['jam_mulai'] ?: '09:00:00',
            'jam_selesai' => $validated['jam_selesai'] ?: '17:00:00',
            'lokasi' => $validated['lokasi'],
            'deskripsi' => $validated['deskripsi'],
            'poster' => $posterPath,
            'status' => $validated['status'],
            'created_by' => auth()->id(),
        ]);

        ActivityLog::log(
            aksi: 'CREATE_EVENT',
            entitasTipe: 'EventBudaya',
            entitasId: $event->id,
            keterangan: ['judul' => $event->judul, 'tanggal_mulai' => $event->tanggal_mulai?->format('Y-m-d')]
        );

        return redirect()->route('pengelola.event.index')->with(
            'success',
            "Event budaya '{$event->judul}' berhasil ditambahkan ke agenda."
        );
    }

    public function edit(EventBudaya $event): View
    {
        $kategoriList = KategoriEvent::orderBy('nama')->get();

        return view('pengelola.event.edit', compact('event', 'kategoriList'));
    }

    public function update(Request $request, EventBudaya $event): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori_event_id' => ['required', 'exists:kategori_event,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['nullable', 'string', 'max:20'],
            'jam_selesai' => ['nullable', 'string', 'max:20'],
            'lokasi' => ['required', 'string', 'max:500'],
            'deskripsi' => ['required', 'string'],
            'poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'status' => ['required', Rule::in(['aktif', 'pending', 'nonaktif'])],
        ], [
            'judul.required' => 'Judul event budaya wajib diisi.',
            'kategori_event_id.required' => 'Pilih kategori event.',
            'tanggal_mulai.required' => 'Tanggal mulai pelaksanaan wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai pelaksanaan wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'lokasi.required' => 'Lokasi tempat pelaksanaan event wajib diisi.',
            'deskripsi.required' => 'Deskripsi event budaya wajib diisi.',
            'poster.max' => 'Ukuran poster maksimal adalah 3 MB.',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }
            $validated['poster'] = $request->file('poster')->store('event', 'public');
        }

        $event->update($validated);

        ActivityLog::log(
            aksi: 'UPDATE_EVENT',
            entitasTipe: 'EventBudaya',
            entitasId: $event->id,
            keterangan: ['judul' => $event->judul, 'status' => $event->status]
        );

        return redirect()->route('pengelola.event.index')->with(
            'success',
            "Data event budaya '{$event->judul}' berhasil diperbarui."
        );
    }

    public function destroy(EventBudaya $event): RedirectResponse
    {
        $judul = $event->judul;
        $id = $event->id;
        $event->delete();

        ActivityLog::log(
            aksi: 'DELETE_EVENT',
            entitasTipe: 'EventBudaya',
            entitasId: $id,
            keterangan: ['judul' => $judul]
        );

        return redirect()->route('pengelola.event.index')->with(
            'success',
            "Event budaya '{$judul}' berhasil dihapus dari agenda."
        );
    }
}
