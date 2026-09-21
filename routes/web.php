<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\FasilitasController as PublicFasilitasController;
use App\Http\Controllers\Public\HomeController as PublicHomeController;
use App\Http\Controllers\Public\KalenderController as PublicKalenderController;
use App\Http\Controllers\Public\PetaController as PublicPetaController;
use App\Http\Controllers\Public\WisataController as PublicWisataController;
use Illuminate\Support\Facades\Route;

// Rute Publik Wisatawan (Milestone 6 & 7)
Route::get('/', [PublicHomeController::class, 'index'])->name('home');
Route::get('/wisata', [PublicWisataController::class, 'index'])->name('wisata.index');
Route::get('/wisata/{slug}', [PublicWisataController::class, 'show'])->name('wisata.show');
Route::get('/peta', [PublicPetaController::class, 'index'])->name('peta.index');
Route::get('/fasilitas', [PublicFasilitasController::class, 'index'])->name('fasilitas.index');
Route::get('/api/wisata', [PublicWisataController::class, 'api'])->name('api.wisata');
Route::get('/kalender', [PublicKalenderController::class, 'index'])->name('kalender.index');
Route::get('/event/{slug}', [PublicKalenderController::class, 'show'])->name('event.show');
Route::get('/event/{slug}/ics', [PublicKalenderController::class, 'downloadIcs'])->name('event.ics');
Route::get('/kalender/{slug}', [PublicKalenderController::class, 'redirectSlug']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LogController as AdminLogController;
use App\Http\Controllers\Admin\MasterDataController as AdminMasterDataController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Data Master
    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/wisata', [AdminMasterDataController::class, 'wisata'])->name('wisata');
        Route::post('/wisata', [AdminMasterDataController::class, 'storeWisata'])->name('wisata.store');
        Route::put('/wisata/{kategoriWisata}', [AdminMasterDataController::class, 'updateWisata'])->name('wisata.update');
        Route::delete('/wisata/{kategoriWisata}', [AdminMasterDataController::class, 'destroyWisata'])->name('wisata.destroy');

        Route::get('/event', [AdminMasterDataController::class, 'event'])->name('event');
        Route::post('/event', [AdminMasterDataController::class, 'storeEvent'])->name('event.store');
        Route::put('/event/{kategoriEvent}', [AdminMasterDataController::class, 'updateEvent'])->name('event.update');
        Route::delete('/event/{kategoriEvent}', [AdminMasterDataController::class, 'destroyEvent'])->name('event.destroy');

        Route::get('/fasilitas', [AdminMasterDataController::class, 'fasilitas'])->name('fasilitas');
        Route::post('/fasilitas', [AdminMasterDataController::class, 'storeFasilitas'])->name('fasilitas.store');
        Route::put('/fasilitas/{jenisFasilitas}', [AdminMasterDataController::class, 'updateFasilitas'])->name('fasilitas.update');
        Route::delete('/fasilitas/{jenisFasilitas}', [AdminMasterDataController::class, 'destroyFasilitas'])->name('fasilitas.destroy');
    });

    // Pengguna
    Route::prefix('pengguna')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::put('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('reset-password');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
    });

    // Verifikasi Pengelola
    Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
        Route::get('/', [AdminVerificationController::class, 'index'])->name('index');
        Route::post('/{user}/setujui', [AdminVerificationController::class, 'approve'])->name('approve');
        Route::post('/{user}/tolak', [AdminVerificationController::class, 'reject'])->name('reject');
    });

    // Log Aktivitas
    Route::get('/log', [AdminLogController::class, 'index'])->name('log');
});

use App\Http\Controllers\Pengelola\DashboardController as PengelolaDashboardController;
use App\Http\Controllers\Pengelola\EventController as PengelolaEventController;
use App\Http\Controllers\Pengelola\FasilitasController as PengelolaFasilitasController;
use App\Http\Controllers\Pengelola\WisataController as PengelolaWisataController;

Route::middleware(['auth', 'role:pengelola'])->prefix('pengelola')->name('pengelola.')->group(function () {
    Route::get('/', [PengelolaDashboardController::class, 'index'])->name('dashboard');

    // Objek Wisata
    Route::resource('wisata', PengelolaWisataController::class)
        ->parameters(['wisata' => 'wisata'])
        ->except(['show']);

    // Fasilitas Desa
    Route::resource('fasilitas', PengelolaFasilitasController::class)
        ->parameters(['fasilitas' => 'fasilitas'])
        ->except(['show']);

    // Event Budaya
    Route::resource('event', PengelolaEventController::class)
        ->parameters(['event' => 'event'])
        ->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
