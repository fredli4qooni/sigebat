<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(): View
    {
        $pendingPengelola = User::where('role', 'pengelola')
            ->where('status', 'pending')
            ->latest('id')
            ->paginate(10, ['*'], 'pending_page');

        $riwayatVerifikasi = User::where('role', 'pengelola')
            ->whereIn('status', ['aktif', 'ditolak'])
            ->latest('updated_at')
            ->take(10)
            ->get();

        return view('admin.verifikasi.index', compact('pendingPengelola', 'riwayatVerifikasi'));
    }

    public function approve(User $user): RedirectResponse
    {
        if ($user->role !== 'pengelola') {
            return back()->with('error', 'Hanya akun pengelola yang dapat diverifikasi.');
        }

        $user->update([
            'status' => 'aktif',
            'rejection_reason' => null,
            'email_verified_at' => now(),
        ]);

        ActivityLog::log(
            aksi: 'VERIFY_APPROVE',
            entitasTipe: 'User',
            entitasId: $user->id,
            keterangan: [
                'name' => $user->name,
                'email' => $user->email,
                'keputusan' => 'disetujui',
            ]
        );

        return redirect()->route('admin.verifikasi.index')->with(
            'success',
            "Akun pengelola '{$user->name}' berhasil disetujui. Pengguna kini dapat login."
        );
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'pengelola') {
            return back()->with('error', 'Hanya akun pengelola yang dapat diverifikasi.');
        }

        $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $alasan = $request->input('rejection_reason') ?: 'Tidak memenuhi persyaratan pengelola desa.';

        $user->update([
            'status' => 'ditolak',
            'rejection_reason' => $alasan,
        ]);

        ActivityLog::log(
            aksi: 'VERIFY_REJECT',
            entitasTipe: 'User',
            entitasId: $user->id,
            keterangan: [
                'name' => $user->name,
                'email' => $user->email,
                'keputusan' => 'ditolak',
                'alasan' => $alasan,
            ]
        );

        return redirect()->route('admin.verifikasi.index')->with(
            'success',
            "Pendaftaran akun '{$user->name}' telah ditolak."
        );
    }
}
