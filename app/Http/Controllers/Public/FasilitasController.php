<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\JenisFasilitas;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function index(Request $request): View
    {
        $query = Fasilitas::with(['jenis', 'objekWisata'])->latest('id');

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

        $fasilitasList = $query->paginate(12)->withQueryString();
        $jenisList = JenisFasilitas::has('fasilitas')->orderBy('nama')->get();

        return view('fasilitas.index', compact('fasilitasList', 'jenisList'));
    }
}
