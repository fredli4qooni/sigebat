<x-app-layout>
    <x-slot:title>Dashboard Admin</x-slot:title>
    <x-slot:header>Dashboard Administrator</x-slot:header>

    <div class="space-y-6">
        <!-- Papan Selamat Datang (DESIGN.md 8.7) -->
        <x-papan warna="aspal" class="p-6">
            <h2 class="text-2xl font-bold font-papan text-putih">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-putih/80 text-[16px] mt-1">
                Panel pengelolaan data master, akun pengguna, verifikasi pendaftaran pengelola, dan pemantauan log audit.
            </p>
        </x-papan>

        <!-- Kartu Ringkasan Status Tindakan (DESIGN.md 8.7: "Apa yang perlu saya kerjakan?") -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-6">
            <h3 class="text-xl font-bold font-papan text-aspal mb-4">Perlu tindakan</h3>
            
            @php
                $pendingCount = \App\Models\User::where('status', 'pending')->count();
            @endphp

            @if($pendingCount > 0)
                <div class="p-4 rounded-kontrol bg-kuning text-aspal border-2 border-aspal flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-aspal inline-block animate-pulse"></span>
                        <span class="font-bold text-[16px]">{{ $pendingCount }} pengelola baru menunggu verifikasi akun.</span>
                    </div>
                    <a href="/admin/verifikasi" class="px-4 py-1.5 bg-aspal text-putih rounded-kontrol text-sm font-papan font-bold no-underline hover:bg-black">
                        Periksa sekarang &rarr;
                    </a>
                </div>
            @else
                <p class="text-sm text-abu">Tidak ada pengelola yang sedang menunggu verifikasi.</p>
            @endif
        </div>
    </div>
</x-app-layout>
