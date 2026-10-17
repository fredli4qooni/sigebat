<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Fasilitas;
use App\Models\JenisFasilitas;
use App\Models\ObjekWisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function index(Request $request): View
    {
        $query = Fasilitas::with(['jenis', 'objekWisata', 'creator'])->latest('id');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search): void {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('keterangan_lokasi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($jenisId = $request->input('jenis_id')) {
            $query->where('jenis_fasilitas_id', $jenisId);
        }

        if ($lingkup = $request->input('lingkup')) {
            if ($lingkup === 'umum') {
                $query->whereNull('objek_wisata_id');
            } elseif ($lingkup === 'khusus') {
                $query->whereNotNull('objek_wisata_id');
            }
        }

        $fasilitasList = $query->paginate(10)->withQueryString();
        $jenisList = JenisFasilitas::orderBy('nama')->get();

        return view('pengelola.fasilitas.index', compact('fasilitasList', 'jenisList'));
    }

    public function create(): View
    {
        $jenisList = JenisFasilitas::orderBy('nama')->get();
        $wisataList = ObjekWisata::orderBy('nama')->get();

        return view('pengelola.fasilitas.create', compact('jenisList', 'wisataList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis_fasilitas_id' => ['required', 'exists:jenis_fasilitas,id'],
            'objek_wisata_id' => ['nullable', 'exists:objek_wisata,id'],
            'keterangan_lokasi' => ['required', 'string', 'max:500'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'bisa_disewa' => ['nullable', 'boolean'],
            'harga_sewa_per_hari' => ['nullable', 'integer', 'min:0'],
            'kapasitas' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'fasilitas_include' => ['nullable', 'string', 'max:1000'],
            'status_ketersediaan' => ['nullable', 'in:tersedia,perbaikan,nonaktif'],
        ], [
            'nama.required' => 'Nama fasilitas wajib diisi.',
            'jenis_fasilitas_id.required' => 'Pilih jenis fasilitas.',
            'keterangan_lokasi.required' => 'Keterangan lokasi penempatan fasilitas wajib diisi.',
            'foto.image' => 'File foto harus berupa gambar valid.',
            'foto.max' => 'Ukuran foto maksimal adalah 3 MB.',
        ]);

        $bisaDisewa = $request->boolean('bisa_disewa');
        $hargaSewa = $bisaDisewa ? (int) ($validated['harga_sewa_per_hari'] ?? 0) : 0;

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fasilitas', 'public');
        }

        $fasilitas = Fasilitas::create([
            'nama' => $validated['nama'],
            'jenis_fasilitas_id' => $validated['jenis_fasilitas_id'],
            'objek_wisata_id' => $validated['objek_wisata_id'] ?? null,
            'keterangan_lokasi' => $validated['keterangan_lokasi'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'foto' => $fotoPath,
            'bisa_disewa' => $bisaDisewa,
            'harga_sewa_per_hari' => $hargaSewa,
            'kapasitas' => $validated['kapasitas'] ?? null,
            'fasilitas_include' => $validated['fasilitas_include'] ?? null,
            'status_ketersediaan' => $validated['status_ketersediaan'] ?? 'tersedia',
            'created_by' => auth()->id(),
        ]);

        ActivityLog::log(
            aksi: 'CREATE_FASILITAS',
            entitasTipe: 'Fasilitas',
            entitasId: $fasilitas->id,
            keterangan: ['nama' => $fasilitas->nama, 'jenis' => $fasilitas->jenis?->nama, 'bisa_disewa' => $fasilitas->bisa_disewa]
        );

        return redirect()->route('pengelola.fasilitas.index')->with(
            'success',
            "Fasilitas '{$fasilitas->nama}' berhasil ditambahkan."
        );
    }

    public function edit(Fasilitas $fasilitas): View
    {
        $jenisList = JenisFasilitas::orderBy('nama')->get();
        $wisataList = ObjekWisata::orderBy('nama')->get();

        return view('pengelola.fasilitas.edit', compact('fasilitas', 'jenisList', 'wisataList'));
    }

    public function update(Request $request, Fasilitas $fasilitas): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis_fasilitas_id' => ['required', 'exists:jenis_fasilitas,id'],
            'objek_wisata_id' => ['nullable', 'exists:objek_wisata,id'],
            'keterangan_lokasi' => ['required', 'string', 'max:500'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'bisa_disewa' => ['nullable', 'boolean'],
            'harga_sewa_per_hari' => ['nullable', 'integer', 'min:0'],
            'kapasitas' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'fasilitas_include' => ['nullable', 'string', 'max:1000'],
            'status_ketersediaan' => ['nullable', 'in:tersedia,perbaikan,nonaktif'],
        ], [
            'nama.required' => 'Nama fasilitas wajib diisi.',
            'jenis_fasilitas_id.required' => 'Pilih jenis fasilitas.',
            'keterangan_lokasi.required' => 'Keterangan lokasi penempatan fasilitas wajib diisi.',
            'foto.max' => 'Ukuran foto maksimal adalah 3 MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($fasilitas->foto && Storage::disk('public')->exists($fasilitas->foto)) {
                Storage::disk('public')->delete($fasilitas->foto);
            }
            $validated['foto'] = $request->file('foto')->store('fasilitas', 'public');
        }

        $bisaDisewa = $request->boolean('bisa_disewa');
        $validated['bisa_disewa'] = $bisaDisewa;
        $validated['harga_sewa_per_hari'] = $bisaDisewa ? (int) ($validated['harga_sewa_per_hari'] ?? 0) : 0;
        $validated['objek_wisata_id'] = $validated['objek_wisata_id'] ?? null;
        $validated['deskripsi'] = $validated['deskripsi'] ?? null;
        $validated['status_ketersediaan'] = $validated['status_ketersediaan'] ?? $fasilitas->status_ketersediaan ?? 'tersedia';
        $fasilitas->update($validated);

        ActivityLog::log(
            aksi: 'UPDATE_FASILITAS',
            entitasTipe: 'Fasilitas',
            entitasId: $fasilitas->id,
            keterangan: ['nama' => $fasilitas->nama, 'lokasi' => $fasilitas->keterangan_lokasi, 'bisa_disewa' => $fasilitas->bisa_disewa]
        );

        return redirect()->route('pengelola.fasilitas.index')->with(
            'success',
            "Data fasilitas '{$fasilitas->nama}' berhasil diperbarui."
        );
    }

    public function destroy(Fasilitas $fasilitas): RedirectResponse
    {
        $nama = $fasilitas->nama;
        $id = $fasilitas->id;
        $fasilitas->delete();

        ActivityLog::log(
            aksi: 'DELETE_FASILITAS',
            entitasTipe: 'Fasilitas',
            entitasId: $id,
            keterangan: ['nama' => $nama]
        );

        return redirect()->route('pengelola.fasilitas.index')->with(
            'success',
            "Fasilitas '{$nama}' berhasil dihapus."
        );
    }
}
