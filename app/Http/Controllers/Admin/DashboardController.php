<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EventBudaya;
use App\Models\Fasilitas;
use App\Models\ObjekWisata;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $wisataAktif = ObjekWisata::active()->count();
        $wisataPending = ObjekWisata::where('status', 'pending')->count();

        $eventAktif = EventBudaya::active()->count();
        $eventPending = EventBudaya::where('status', 'pending')->count();

        $fasilitasTotal = Fasilitas::count();

        $pengelolaAktif = User::where('role', 'pengelola')->where('status', 'aktif')->count();
        $pengelolaPending = User::where('role', 'pengelola')->where('status', 'pending')->count();

        $dataWisataTerbaru = ObjekWisata::latest('id')
            ->with(['kategori', 'creator'])
            ->take(5)
            ->get();

        $logTerbaru = ActivityLog::latest('id')
            ->with('user')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'wisataAktif',
            'wisataPending',
            'eventAktif',
            'eventPending',
            'fasilitasTotal',
            'pengelolaAktif',
            'pengelolaPending',
            'dataWisataTerbaru',
            'logTerbaru'
        ));
    }
}
