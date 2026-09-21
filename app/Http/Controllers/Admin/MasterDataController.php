<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\JenisFasilitas;
use App\Models\KategoriEvent;
use App\Models\KategoriWisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    // ==========================================
    // 1. KATEGORI WISATA
    // ==========================================
    public function wisata(): View
    {
        $kategoriList = KategoriWisata::withCount('objekWisata')
            ->latest('id')
            ->paginate(10);

        return view('admin.master.wisata', compact('kategoriList'));
    }

    public function storeWisata(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:kategori_wisata,nama'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori wisata wajib diisi.',
            'nama.unique' => 'Nama kategori wisata ini sudah ada.',
        ]);

        $kategori = KategoriWisata::create([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        ActivityLog::log(
            aksi: 'CREATE_KATEGORI_WISATA',
            entitasTipe: 'KategoriWisata',
            entitasId: $kategori->id,
            keterangan: ['nama' => $kategori->nama]
        );

        return redirect()->route('admin.master.wisata')->with('success', 'Kategori wisata berhasil ditambahkan.');
    }

    public function updateWisata(Request $request, KategoriWisata $kategoriWisata): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:kategori_wisata,nama,'.$kategoriWisata->id],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori wisata wajib diisi.',
            'nama.unique' => 'Nama kategori wisata ini sudah digunakan.',
        ]);

        $kategoriWisata->update([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        ActivityLog::log(
            aksi: 'UPDATE_KATEGORI_WISATA',
            entitasTipe: 'KategoriWisata',
            entitasId: $kategoriWisata->id,
            keterangan: ['nama' => $kategoriWisata->nama]
        );

        return redirect()->route('admin.master.wisata')->with('success', 'Kategori wisata berhasil diperbarui.');
    }

    public function destroyWisata(KategoriWisata $kategoriWisata): RedirectResponse
    {
        $count = $kategoriWisata->objekWisata()->count();
        if ($count > 0) {
            return redirect()->route('admin.master.wisata')->with(
                'error',
                "Kategori wisata '{$kategoriWisata->nama}' tidak dapat dihapus karena sedang digunakan oleh {$count} data objek wisata."
            );
        }

        $nama = $kategoriWisata->nama;
        $id = $kategoriWisata->id;
        $kategoriWisata->delete();

        ActivityLog::log(
            aksi: 'DELETE_KATEGORI_WISATA',
            entitasTipe: 'KategoriWisata',
            entitasId: $id,
            keterangan: ['nama' => $nama]
        );

        return redirect()->route('admin.master.wisata')->with('success', "Kategori wisata '{$nama}' berhasil dihapus.");
    }

    // ==========================================
    // 2. KATEGORI EVENT
    // ==========================================
    public function event(): View
    {
        $kategoriList = KategoriEvent::withCount('eventBudaya')
            ->latest('id')
            ->paginate(10);

        return view('admin.master.event', compact('kategoriList'));
    }

    public function storeEvent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:kategori_event,nama'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori event wajib diisi.',
            'nama.unique' => 'Nama kategori event ini sudah ada.',
        ]);

        $kategori = KategoriEvent::create([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        ActivityLog::log(
            aksi: 'CREATE_KATEGORI_EVENT',
            entitasTipe: 'KategoriEvent',
            entitasId: $kategori->id,
            keterangan: ['nama' => $kategori->nama]
        );

        return redirect()->route('admin.master.event')->with('success', 'Kategori event budaya berhasil ditambahkan.');
    }

    public function updateEvent(Request $request, KategoriEvent $kategoriEvent): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:kategori_event,nama,'.$kategoriEvent->id],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama kategori event wajib diisi.',
            'nama.unique' => 'Nama kategori event ini sudah digunakan.',
        ]);

        $kategoriEvent->update([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        ActivityLog::log(
            aksi: 'UPDATE_KATEGORI_EVENT',
            entitasTipe: 'KategoriEvent',
            entitasId: $kategoriEvent->id,
            keterangan: ['nama' => $kategoriEvent->nama]
        );

        return redirect()->route('admin.master.event')->with('success', 'Kategori event budaya berhasil diperbarui.');
    }

    public function destroyEvent(KategoriEvent $kategoriEvent): RedirectResponse
    {
        $count = $kategoriEvent->eventBudaya()->count();
        if ($count > 0) {
            return redirect()->route('admin.master.event')->with(
                'error',
                "Kategori event '{$kategoriEvent->nama}' tidak dapat dihapus karena sedang digunakan oleh {$count} data event budaya."
            );
        }

        $nama = $kategoriEvent->nama;
        $id = $kategoriEvent->id;
        $kategoriEvent->delete();

        ActivityLog::log(
            aksi: 'DELETE_KATEGORI_EVENT',
            entitasTipe: 'KategoriEvent',
            entitasId: $id,
            keterangan: ['nama' => $nama]
        );

        return redirect()->route('admin.master.event')->with('success', "Kategori event '{$nama}' berhasil dihapus.");
    }

    // ==========================================
    // 3. JENIS FASILITAS
    // ==========================================
    public function fasilitas(): View
    {
        $jenisList = JenisFasilitas::withCount('fasilitas')
            ->latest('id')
            ->paginate(10);

        return view('admin.master.fasilitas', compact('jenisList'));
    }

    public function storeFasilitas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:jenis_fasilitas,nama'],
            'icon' => ['nullable', 'string', 'max:50'],
        ], [
            'nama.required' => 'Nama jenis fasilitas wajib diisi.',
            'nama.unique' => 'Nama jenis fasilitas ini sudah ada.',
        ]);

        $jenis = JenisFasilitas::create([
            'nama' => $validated['nama'],
            'icon' => $validated['icon'] ?? 'buildings',
        ]);

        ActivityLog::log(
            aksi: 'CREATE_JENIS_FASILITAS',
            entitasTipe: 'JenisFasilitas',
            entitasId: $jenis->id,
            keterangan: ['nama' => $jenis->nama]
        );

        return redirect()->route('admin.master.fasilitas')->with('success', 'Jenis fasilitas berhasil ditambahkan.');
    }

    public function updateFasilitas(Request $request, JenisFasilitas $jenisFasilitas): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:jenis_fasilitas,nama,'.$jenisFasilitas->id],
            'icon' => ['nullable', 'string', 'max:50'],
        ], [
            'nama.required' => 'Nama jenis fasilitas wajib diisi.',
            'nama.unique' => 'Nama jenis fasilitas ini sudah digunakan.',
        ]);

        $jenisFasilitas->update([
            'nama' => $validated['nama'],
            'icon' => $validated['icon'] ?? 'buildings',
        ]);

        ActivityLog::log(
            aksi: 'UPDATE_JENIS_FASILITAS',
            entitasTipe: 'JenisFasilitas',
            entitasId: $jenisFasilitas->id,
            keterangan: ['nama' => $jenisFasilitas->nama]
        );

        return redirect()->route('admin.master.fasilitas')->with('success', 'Jenis fasilitas berhasil diperbarui.');
    }

    public function destroyFasilitas(JenisFasilitas $jenisFasilitas): RedirectResponse
    {
        $count = $jenisFasilitas->fasilitas()->count();
        if ($count > 0) {
            return redirect()->route('admin.master.fasilitas')->with(
                'error',
                "Jenis fasilitas '{$jenisFasilitas->nama}' tidak dapat dihapus karena sedang digunakan oleh {$count} data fasilitas."
            );
        }

        $nama = $jenisFasilitas->nama;
        $id = $jenisFasilitas->id;
        $jenisFasilitas->delete();

        ActivityLog::log(
            aksi: 'DELETE_JENIS_FASILITAS',
            entitasTipe: 'JenisFasilitas',
            entitasId: $id,
            keterangan: ['nama' => $nama]
        );

        return redirect()->route('admin.master.fasilitas')->with('success', "Jenis fasilitas '{$nama}' berhasil dihapus.");
    }
}
