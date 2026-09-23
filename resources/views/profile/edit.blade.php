<x-app-layout>
    <x-slot:title>Profil Pengguna</x-slot:title>
    <x-slot:header>Pengaturan Profil Pengguna</x-slot:header>

    <div class="space-y-6">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Keamanan & Data Diri</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Pengaturan Profil Akun
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Kelola identitas akun Anda, alamat email resmi, serta pembaruan kata sandi secara aman.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-semibold capitalize">
                    Peran: {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pengelola Objek Wisata' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi Akun -->
            <div class="p-6 sm:p-8 bg-white border border-slate-200/90 rounded-2xl shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Perbarui Kata Sandi -->
            <div class="p-6 sm:p-8 bg-white border border-slate-200/90 rounded-2xl shadow-sm">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Hapus Akun -->
        <div class="p-6 sm:p-8 bg-white border border-rose-200/80 rounded-2xl shadow-sm">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>

