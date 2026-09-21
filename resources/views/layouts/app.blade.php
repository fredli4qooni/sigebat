<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Panel Kerja' }} — {{ config('app.name', 'SIGEBAT') }}</title>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-beton text-aspal font-sans antialiased min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar Panel (DESIGN.md 7.11: Sidebar Aspal 240px) -->
        <aside class="w-full md:w-[260px] bg-aspal text-putih flex-shrink-0 flex flex-col justify-between border-b-2 md:border-b-0 md:border-r-2 border-aspal">
            <div>
                <!-- Brand & Role -->
                <div class="p-5 border-b border-putih/15 flex items-center justify-between">
                    <a href="/" class="flex items-center gap-3 no-underline text-putih">
                        <div class="w-9 h-9 bg-cokelat border-2 border-putih rounded-kontrol flex items-center justify-center text-putih">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 256 256">
                                <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-papan font-bold text-lg leading-tight tracking-tight">SIGEBAT</div>
                            <div class="text-xs text-putih/70">
                                {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pengelola wisata' }}
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Navigasi Sidebar Berdasarkan Peran -->
                <nav class="p-3 space-y-1.5">
                    @if(auth()->user()->isAdmin())
                        <div class="px-3 pt-3 pb-1 text-xs font-bold text-putih/50">Menu admin</div>
                        
                        <a href="/admin" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('admin') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-aspal text-putih text-xs">📊</span>
                            Dashboard Admin
                        </a>
                        
                        <a href="/admin/master/wisata" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('admin/master*') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-cokelat text-putih text-xs">📁</span>
                            Data Master
                        </a>

                        <a href="/admin/pengguna" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('admin/pengguna*') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-biru text-putih text-xs">👥</span>
                            Akun Pengguna
                        </a>

                        <a href="/admin/verifikasi" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('admin/verifikasi*') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-hijau text-putih text-xs">✓</span>
                            Verifikasi Pengelola
                        </a>

                        <a href="/admin/log" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('admin/log*') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-kuning text-aspal text-xs">📋</span>
                            Log Aktivitas
                        </a>
                    @else
                        <div class="px-3 pt-3 pb-1 text-xs font-bold text-putih/50">Ruang pengelola</div>

                        <a href="/pengelola" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('pengelola') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-aspal text-putih text-xs">🏠</span>
                            Dashboard
                        </a>

                        <a href="/pengelola/wisata" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('pengelola/wisata*') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-cokelat text-putih text-xs">🏡</span>
                            Objek Wisata
                        </a>

                        <a href="/pengelola/fasilitas" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('pengelola/fasilitas*') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-biru text-putih text-xs">🚻</span>
                            Fasilitas Desa
                        </a>

                        <a href="/pengelola/event" class="flex items-center gap-3 px-3.5 py-2.5 rounded-kontrol text-[15px] font-semibold no-underline {{ request()->is('pengelola/event*') ? 'bg-putih text-aspal font-bold' : 'text-putih hover:bg-putih/10' }}">
                            <span class="w-6 h-6 flex items-center justify-center rounded-sm bg-kuning text-aspal text-xs">📅</span>
                            Event Budaya
                        </a>
                    @endif

                    <div class="pt-4 border-t border-putih/15">
                        <a href="/" target="_blank" class="flex items-center gap-3 px-3.5 py-2 rounded-kontrol text-[14px] text-putih/80 hover:text-putih hover:bg-putih/10 no-underline">
                            <span>↗</span>
                            Lihat Web Publik
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Akun & Logout -->
            <div class="p-4 border-t border-putih/15">
                <div class="mb-3 px-1">
                    <div class="text-[14px] font-semibold text-putih truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[12px] text-putih/60 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 h-[40px] px-3 bg-putih/10 hover:bg-merah text-putih text-sm font-semibold rounded-kontrol border border-putih/20 transition-none cursor-pointer">
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>

        <!-- Area Konten Kerja -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar Ringkas -->
            <header class="bg-putih border-b-2 border-aspal px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold font-papan text-aspal leading-tight">
                        {{ $header ?? $title ?? 'Panel Kerja' }}
                    </h1>
                </div>
                <div class="text-xs font-semibold text-abu">
                    WIB (Asia/Jakarta) &bull; {{ now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 sm:p-8 max-w-[1400px] w-full mx-auto">
                <!-- Flash Notification Banner -->
                @if(session('success'))
                    <div class="mb-6">
                        <x-banner jenis="sukses">{{ session('success') }}</x-banner>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6">
                        <x-banner jenis="galat">{{ session('error') }}</x-banner>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6">
                        <x-banner jenis="peringatan">{{ session('warning') }}</x-banner>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
