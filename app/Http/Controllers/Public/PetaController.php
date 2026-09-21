<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\KategoriWisata;
use App\Models\ObjekWisata;
use Illuminate\View\View;

class PetaController extends Controller
{
    public function index(): View
    {
        $wisataList = ObjekWisata::with('kategori')
            ->where('status', 'aktif')
            ->get()
            ->map(fn ($w) => [
                'id' => $w->id,
                'nama' => $w->nama,
                'slug' => $w->slug,
                'kategori' => $w->kategori?->nama ?? 'Wisata',
                'kategori_id' => $w->kategori_wisata_id,
                'latitude' => $w->latitude,
                'longitude' => $w->longitude,
                'alamat' => $w->alamat,
                'jam_operasional' => $w->jam_operasional ?: '08.00 - 17.00 WIB',
                'harga_tiket' => $w->harga_tiket ?: 'Gratis',
                'foto_url' => $w->foto_url,
                'detail_url' => route('wisata.show', $w->slug),
                'google_maps_url' => $w->google_maps_url,
            ]);

        $fasilitasList = Fasilitas::with(['jenis', 'objekWisata'])
            ->get()
            ->map(function ($f) {
                $lat = $f->objekWisata?->latitude ?? -4.540583;
                $lng = $f->objekWisata?->longitude ?? 104.664984;

                return [
                    'id' => $f->id,
                    'nama' => $f->nama,
                    'jenis' => $f->jenis?->nama ?? 'Fasilitas',
                    'lokasi' => $f->keterangan_lokasi,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'is_umum' => $f->is_umum,
                    'objek_wisata' => $f->objekWisata?->nama,
                ];
            });

        $kategoriList = KategoriWisata::has('objekWisata')->orderBy('nama')->get();

        return view('peta.index', compact('wisataList', 'fasilitasList', 'kategoriList'));
    }
}
