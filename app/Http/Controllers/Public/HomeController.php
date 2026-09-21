<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventBudaya;
use App\Models\Fasilitas;
use App\Models\ObjekWisata;
use Carbon\Carbon;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $wisataUnggulan = ObjekWisata::with(['kategori'])
            ->withCount('fasilitas')
            ->where('status', 'aktif')
            ->latest('id')
            ->take(6)
            ->get();

        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $eventMendatang = EventBudaya::with('kategori')
            ->where('status', 'aktif')
            ->whereDate('tanggal_selesai', '>=', $today)
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        $fasilitasSorotan = Fasilitas::with(['jenis', 'objekWisata'])
            ->latest('id')
            ->take(6)
            ->get();

        $countWisata = ObjekWisata::where('status', 'aktif')->count();
        $countEvent = EventBudaya::where('status', 'aktif')->count();
        $countFasilitas = Fasilitas::count();

        return view('welcome', compact(
            'wisataUnggulan',
            'eventMendatang',
            'fasilitasSorotan',
            'countWisata',
            'countEvent',
            'countFasilitas'
        ));
    }
}
