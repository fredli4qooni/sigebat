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
    <body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-emerald-100 selection:text-emerald-900">
        
        <!-- Sidebar Panel Modern (260px) -->
        <aside class="w-full md:w-[260px] bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between border-b md:border-b-0 md:border-r border-slate-800">
            <div>
                <!-- Brand & Role -->
                <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                    <a href="/" class="flex items-center gap-3 no-underline text-white group">
                        <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 256 256">
                                <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-base leading-tight tracking-tight text-white">SIGEBAT</div>
                            <div class="text-xs text-slate-400 capitalize">
                                {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pengelola Wisata' }}
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Navigasi Sidebar Berdasarkan Peran -->
                <nav class="p-3.5 space-y-1.5">
                    @if(auth()->user()->isAdmin())
                        <div class="px-3 pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Menu Admin</div>
                        
                        <a href="/admin" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('admin') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard Admin</span>
                        </a>
                        
                        <a href="/admin/master/wisata" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('admin/master*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <span>Data Master</span>
                        </a>

                        <a href="/admin/pengguna" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('admin/pengguna*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Akun Pengguna</span>
                        </a>

                        <a href="/admin/verifikasi" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('admin/verifikasi*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Verifikasi Pengelola</span>
                        </a>

                        <a href="/admin/log" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('admin/log*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span>Log Aktivitas</span>
                        </a>
                    @else
                        <div class="px-3 pt-3 pb-1 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Ruang Pengelola</div>

                        <a href="/pengelola" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('pengelola') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="/pengelola/wisata" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('pengelola/wisata*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Objek Wisata</span>
                        </a>

                        <a href="/pengelola/fasilitas" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('pengelola/fasilitas*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Fasilitas Desa</span>
                        </a>

                        <a href="/pengelola/event" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold no-underline transition-all {{ request()->is('pengelola/event*') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Event Budaya</span>
                        </a>
                    @endif

                    <div class="pt-4 border-t border-slate-800">
                        <a href="/" target="_blank" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/70 no-underline transition-all">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            <span>Lihat Web Publik</span>
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Akun & Logout -->
            <div class="p-4 border-t border-slate-800">
                <div class="mb-3 px-1">
                    <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 h-9 px-3 bg-slate-800 hover:bg-rose-900/40 text-slate-300 hover:text-rose-300 text-xs font-semibold rounded-xl border border-slate-700 hover:border-rose-800/60 transition-all cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Area Konten Kerja -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar Ringkas Modern -->
            <header class="bg-white border-b border-slate-200/80 px-8 py-4 flex items-center justify-between shadow-xs">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">
                        {{ $header ?? $title ?? 'Panel Kerja' }}
                    </h1>
                </div>
                <div class="text-xs font-medium text-slate-500">
                    WIB (Asia/Jakarta) &bull; {{ now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 sm:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Notification Banner -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
                        <span class="text-emerald-600">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center gap-3">
                        <span class="text-rose-600">✕</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm font-semibold flex items-center gap-3">
                        <span class="text-amber-600">⚠️</span>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
