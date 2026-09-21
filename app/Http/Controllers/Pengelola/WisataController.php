<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KategoriWisata;
use App\Models\ObjekWisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WisataController extends Controller
{
    public function index(Request $request): View
    {
        $query = ObjekWisata::with(['kategori', 'creator'])->withCount('fasilitas')->latest('id');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search): void {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategoriId = $request->input('kategori_id')) {
            $query->where('kategori_wisata_id', $kategoriId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $wisataList = $query->paginate(10)->withQueryString();
        $kategoriList = KategoriWisata::orderBy('nama')->get();

        return view('pengelola.wisata.index', compact('wisataList', 'kategoriList'));
    }

    public function create(): View
    {
        $kategoriList = KategoriWisata::orderBy('nama')->get();

        return view('pengelola.wisata.create', compact('kategoriList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori_wisata_id' => ['required', 'exists:kategori_wisata,id'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'alamat' => ['required', 'string', 'max:500'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
            'harga_tiket' => ['nullable', 'string', 'max:100'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['required', 'string'],
            'foto_utama' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'status' => ['required', Rule::in(['aktif', 'pending', 'nonaktif'])],
        ], [
            'nama.required' => 'Nama objek wisata wajib diisi.',
            'kategori_wisata_id.required' => 'Pilih kategori wisata.',
            'latitude.required' => 'Titik koordinat latitude wajib diisi atau dipilih pada peta.',
            'latitude.between' => 'Koordinat latitude harus di antara -90 dan 90 derajat.',
            'longitude.required' => 'Titik koordinat longitude wajib diisi atau dipilih pada peta.',
            'longitude.between' => 'Koordinat longitude harus di antara -180 dan 180 derajat.',
            'alamat.required' => 'Alamat lengkap objek wisata wajib diisi.',
            'deskripsi.required' => 'Deskripsi objek wisata wajib diisi.',
            'foto_utama.image' => 'File foto harus berupa gambar valid (JPG, PNG, WEBP).',
            'foto_utama.max' => 'Ukuran foto maksimal adalah 3 MB.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_utama')) {
            $fotoPath = $request->file('foto_utama')->store('wisata', 'public');
        }

        $wisata = ObjekWisata::create([
            'kategori_wisata_id' => $validated['kategori_wisata_id'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'jam_operasional' => $validated['jam_operasional'] ?? '08.00 - 17.00 WIB',
            'harga_tiket' => $validated['harga_tiket'] ?? 'Gratis',
            'kontak' => $validated['kontak'],
            'deskripsi' => $validated['deskripsi'],
            'foto_utama' => $fotoPath,
            'status' => $validated['status'],
            'created_by' => auth()->id(),
        ]);

        ActivityLog::log(
            aksi: 'CREATE_WISATA',
            entitasTipe: 'ObjekWisata',
            entitasId: $wisata->id,
            keterangan: ['nama' => $wisata->nama, 'kategori' => $wisata->kategori?->nama]
        );

        return redirect()->route('pengelola.wisata.index')->with(
            'success',
            "Objek wisata '{$wisata->nama}' berhasil ditambahkan."
        );
    }

    public function edit(ObjekWisata $wisata): View
    {
        $kategoriList = KategoriWisata::orderBy('nama')->get();

        return view('pengelola.wisata.edit', compact('wisata', 'kategoriList'));
    }

    public function update(Request $request, ObjekWisata $wisata): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kategori_wisata_id' => ['required', 'exists:kategori_wisata,id'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'alamat' => ['required', 'string', 'max:500'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
            'harga_tiket' => ['nullable', 'string', 'max:100'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['required', 'string'],
            'foto_utama' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'status' => ['required', Rule::in(['aktif', 'pending', 'nonaktif'])],
        ], [
            'nama.required' => 'Nama objek wisata wajib diisi.',
            'kategori_wisata_id.required' => 'Pilih kategori wisata.',
            'latitude.required' => 'Titik koordinat latitude wajib diisi.',
            'longitude.required' => 'Titik koordinat longitude wajib diisi.',
            'alamat.required' => 'Alamat lengkap objek wisata wajib diisi.',
            'deskripsi.required' => 'Deskripsi objek wisata wajib diisi.',
            'foto_utama.max' => 'Ukuran foto maksimal adalah 3 MB.',
        ]);

        if ($request->hasFile('foto_utama')) {
            if ($wisata->foto_utama && Storage::disk('public')->exists($wisata->foto_utama)) {
                Storage::disk('public')->delete($wisata->foto_utama);
            }
            $validated['foto_utama'] = $request->file('foto_utama')->store('wisata', 'public');
        }

        $validated['updated_by'] = auth()->id();
        $wisata->update($validated);

        ActivityLog::log(
            aksi: 'UPDATE_WISATA',
            entitasTipe: 'ObjekWisata',
            entitasId: $wisata->id,
            keterangan: ['nama' => $wisata->nama, 'status' => $wisata->status]
        );

        return redirect()->route('pengelola.wisata.index')->with(
            'success',
            "Data objek wisata '{$wisata->nama}' berhasil diperbarui."
        );
    }

    public function destroy(ObjekWisata $wisata): RedirectResponse
    {
        $nama = $wisata->nama;
        $id = $wisata->id;
        $wisata->delete();

        ActivityLog::log(
            aksi: 'DELETE_WISATA',
            entitasTipe: 'ObjekWisata',
            entitasId: $id,
            keterangan: ['nama' => $nama]
        );

        return redirect()->route('pengelola.wisata.index')->with(
            'success',
            "Objek wisata '{$nama}' berhasil dihapus."
        );
    }
}
