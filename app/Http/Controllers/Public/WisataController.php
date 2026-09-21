<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\KategoriWisata;
use App\Models\ObjekWisata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WisataController extends Controller
{
    public function index(Request $request): View
    {
        $query = ObjekWisata::with(['kategori'])
            ->withCount('fasilitas')
            ->where('status', 'aktif');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search): void {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategoriSlug = $request->input('kategori')) {
            $query->whereHas('kategori', function ($q) use ($kategoriSlug): void {
                $q->where('slug', $kategoriSlug);
            });
        }

        $sort = $request->input('sort', 'terbaru');
        if ($sort === 'nama_asc') {
            $query->orderBy('nama', 'asc');
        } elseif ($sort === 'nama_desc') {
            $query->orderBy('nama', 'desc');
        } else {
            $query->latest('id');
        }

        $wisataList = $query->paginate(9)->withQueryString();
        $kategoriList = KategoriWisata::has('objekWisata')->orderBy('nama')->get();

        // Data JSON ringan untuk perhitungan LBS di browser
        $wisataJson = $wisataList->map(fn ($w) => [
            'id' => $w->id,
            'nama' => $w->nama,
            'slug' => $w->slug,
            'latitude' => $w->latitude,
            'longitude' => $w->longitude,
            'kategori' => $w->kategori?->nama,
            'foto_url' => $w->foto_url,
        ]);

        return view('wisata.index', compact('wisataList', 'kategoriList', 'wisataJson'));
    }

    public function show(string $slug): View
    {
        $wisata = ObjekWisata::with(['kategori', 'fasilitas.jenis', 'creator'])
            ->where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        $wisataLain = ObjekWisata::with('kategori')
            ->where('status', 'aktif')
            ->where('id', '!=', $wisata->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('wisata.show', compact('wisata', 'wisataLain'));
    }

    public function api(): JsonResponse
    {
        $wisata = ObjekWisata::with(['kategori', 'fasilitas'])
            ->where('status', 'aktif')
            ->get();

        $features = $wisata->map(fn ($w) => [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [(float) $w->longitude, (float) $w->latitude],
            ],
            'properties' => [
                'id' => $w->id,
                'nama' => $w->nama,
                'slug' => $w->slug,
                'kategori' => $w->kategori?->nama,
                'alamat' => $w->alamat,
                'jam_operasional' => $w->jam_operasional,
                'harga_tiket' => $w->harga_tiket,
                'foto_url' => $w->foto_url,
                'url' => route('wisata.show', $w->slug),
            ],
        ]);

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
