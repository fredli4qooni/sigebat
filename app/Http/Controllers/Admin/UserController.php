<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->latest('id');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', Rule::in(['admin', 'pengelola'])],
            'status' => ['required', Rule::in(['pending', 'aktif', 'nonaktif', 'ditolak'])],
            'password' => ['required', 'string', Password::defaults()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'status' => $validated['status'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        ActivityLog::log(
            aksi: 'CREATE_USER',
            entitasTipe: 'User',
            entitasId: $user->id,
            keterangan: ['name' => $user->name, 'email' => $user->email, 'role' => $user->role, 'status' => $user->status]
        );

        return redirect()->route('admin.users.index')->with('success', "Akun '{$user->name}' berhasil dibuat.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', Rule::in(['admin', 'pengelola'])],
            'status' => ['required', Rule::in(['pending', 'aktif', 'nonaktif', 'ditolak'])],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email sudah digunakan.',
            'phone.required' => 'Nomor HP wajib diisi.',
        ]);

        // Proteksi: Admin tidak dapat menonaktifkan atau menurunkan dirinya sendiri
        if ($user->id === auth()->id()) {
            if ($validated['role'] !== 'admin' || $validated['status'] !== 'aktif') {
                return back()->with('error', 'Anda tidak dapat menonaktifkan atau menurunkan peran akun Anda sendiri.');
            }
        }

        // Proteksi: Minimal satu Admin aktif harus selalu ada
        if ($user->isAdmin() && $user->isActive() && ($validated['role'] !== 'admin' || $validated['status'] !== 'aktif')) {
            $activeAdminCount = User::where('role', 'admin')->where('status', 'aktif')->count();
            if ($activeAdminCount <= 1) {
                return back()->with('error', 'Minimal satu Administrator aktif harus selalu tersedia dalam sistem.');
            }
        }

        $user->update($validated);

        ActivityLog::log(
            aksi: 'UPDATE_USER',
            entitasTipe: 'User',
            entitasId: $user->id,
            keterangan: ['name' => $user->name, 'email' => $user->email, 'role' => $user->role, 'status' => $user->status]
        );

        return redirect()->route('admin.users.index')->with('success', "Data akun '{$user->name}' berhasil diperbarui.");
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::log(
            aksi: 'RESET_PASSWORD_BY_ADMIN',
            entitasTipe: 'User',
            entitasId: $user->id,
            keterangan: ['name' => $user->name, 'email' => $user->email]
        );

        return redirect()->route('admin.users.index')->with('success', "Kata sandi untuk '{$user->name}' berhasil direset.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->isAdmin()) {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Administrator terakhir tidak dapat dihapus.');
            }
        }

        $name = $user->name;
        $id = $user->id;
        $user->delete();

        ActivityLog::log(
            aksi: 'DELETE_USER',
            entitasTipe: 'User',
            entitasId: $id,
            keterangan: ['name' => $name]
        );

        return redirect()->route('admin.users.index')->with('success', "Akun '{$name}' berhasil dihapus.");
    }
}
