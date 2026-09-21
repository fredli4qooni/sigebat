<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\EventBudaya;
use App\Models\Fasilitas;
use App\Models\ObjekWisata;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalWisata = ObjekWisata::count();
        $totalWisataAktif = ObjekWisata::where('status', 'aktif')->count();

        $totalFasilitas = Fasilitas::count();
        $totalFasilitasUmum = Fasilitas::whereNull('objek_wisata_id')->count();

        $totalEvent = EventBudaya::count();
        $totalEventAktif = EventBudaya::where('status', 'aktif')->count();

        $wisataTerbaru = ObjekWisata::with('kategori')
            ->latest('id')
            ->take(5)
            ->get();

        $eventMendatang = EventBudaya::with('kategori')
            ->where('status', 'aktif')
            ->whereDate('tanggal_selesai', '>=', now('Asia/Jakarta')->startOfDay())
            ->orderBy('tanggal_mulai')
            ->take(5)
            ->get();

        return view('pengelola.dashboard', compact(
            'totalWisata',
            'totalWisataAktif',
            'totalFasilitas',
            'totalFasilitasUmum',
            'totalEvent',
            'totalEventAktif',
            'wisataTerbaru',
            'eventMendatang'
        ));
    }
}
