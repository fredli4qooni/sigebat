<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\KategoriWisata;
use App\Models\ObjekWisata;
use Illuminate\Support\Str;
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
                'latitude' => (float) $w->latitude,
                'longitude' => (float) $w->longitude,
                'alamat' => $w->alamat,
                'deskripsi_singkat' => Str::limit(strip_tags((string) $w->deskripsi), 85),
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
                    'latitude' => (float) $lat,
                    'longitude' => (float) $lng,
                    'is_umum' => (bool) $f->is_umum,
                    'objek_wisata' => $f->objekWisata?->nama,
                ];
            });

        $kategoriList = KategoriWisata::has('objekWisata')->orderBy('nama')->get();

        $presetLocations = [
            [
                'id' => 'gerbang',
                'nama' => 'Pintu Gerbang Desa Gedung Batin',
                'keterangan' => 'Titik masuk utama gapura adat kampung',
                'latitude' => -4.538500,
                'longitude' => 104.663200,
            ],
            [
                'id' => 'balai',
                'nama' => 'Balai Adat / Kantor Kampung',
                'keterangan' => 'Pusat pemerintahan & titik kumpul warga',
                'latitude' => -4.540583,
                'longitude' => 104.664984,
            ],
            [
                'id' => 'jembatan',
                'nama' => 'Jembatan Gantung Way Besai',
                'keterangan' => 'Akses penyeberangan sungai panorama alam',
                'latitude' => -4.542100,
                'longitude' => 104.666800,
            ],
        ];

        return view('peta.index', compact('wisataList', 'fasilitasList', 'kategoriList', 'presetLocations'));
    }
}
