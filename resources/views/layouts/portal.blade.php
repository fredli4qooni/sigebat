<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Jelajah Gedung Batin' }} — SIGEBAT Desa Wisata</title>
    <meta name="description" content="{{ $description ?? 'Sistem Informasi Manajemen Desa Wisata Kampung Gedung Batin berbasis Location Based Services (LBS) dan Kalender Event Budaya Digital.' }}">

    <!-- Open Graph & Meta Media Sosial -->
    <meta property="og:title" content="{{ $title ?? 'Desa Wisata Kampung Gedung Batin' }}">
    <meta property="og:description" content="{{ $description ?? 'Informasi objek wisata adat pepadun, peta LBS terdekat, dan jadwal event budaya digital Way Kanan.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @if(isset($image))
        <meta property="og:image" content="{{ $image }}">
    @endif

    <!-- Twitter Card Meta -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Desa Wisata Kampung Gedung Batin' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Informasi objek wisata adat pepadun, peta LBS terdekat, dan jadwal event budaya digital Way Kanan.' }}">
    @if(isset($image))
        <meta name="twitter:image" content="{{ $image }}">
    @endif

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-emerald-100 selection:text-emerald-900">

    <!-- Kepala Navigasi Atas (Desktop & Mobile) -->
    <header class="sticky top-0 z-40 glass-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <!-- Logo Brand Modern -->
            <a href="/" class="flex items-center gap-3.5 no-underline group">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 256 256">
                        <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <span class="block font-bold text-lg text-slate-900 tracking-tight group-hover:text-emerald-700 transition-colors">SIGEBAT</span>
                    <span class="text-xs font-medium text-slate-500">Kampung Gedung Batin, Way Kanan</span>
                </div>
            </a>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center gap-1.5">
                <a href="/" class="px-3.5 py-2 rounded-lg text-sm font-semibold no-underline transition-all {{ request()->is('/') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                    Beranda
                </a>
                <a href="/wisata" class="px-3.5 py-2 rounded-lg text-sm font-semibold no-underline transition-all {{ request()->is('wisata*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                    Wisata & Cagar Budaya
                </a>
                <a href="/peta" class="px-3.5 py-2 rounded-lg text-sm font-semibold no-underline transition-all {{ request()->is('peta*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                    Peta & LBS
                </a>
                <a href="/kalender" class="px-3.5 py-2 rounded-lg text-sm font-semibold no-underline transition-all {{ request()->is('kalender*') || request()->is('event*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                    Kalender Event
                </a>
                <a href="/fasilitas" class="px-3.5 py-2 rounded-lg text-sm font-semibold no-underline transition-all {{ request()->is('fasilitas*') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                    Fasilitas
                </a>

                <div class="h-5 w-[1px] bg-slate-200 mx-2"></div>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="/admin" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-900 text-white font-medium text-xs no-underline hover:bg-slate-800 shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                            </svg>
                            <span>Panel Admin</span>
                        </a>
                    @else
                        <a href="/pengelola" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-700 text-white font-medium text-xs no-underline hover:bg-emerald-800 shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                            <span>Panel Pengelola</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-slate-700 font-semibold text-xs no-underline hover:bg-slate-50 shadow-xs transition-all">
                        <svg class="w-3.5 h-3.5 fill-current text-slate-500" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Masuk Pengelola</span>
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Konten Utama Halaman -->
    <main class="flex-1 pb-20 md:pb-12">
        {{ $slot }}
    </main>

    <!-- Mobile Bottom Navigation Modern (Sticky 64px Bar with SVGs) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-white/95 backdrop-blur-md border-t border-slate-200/80 z-50 flex items-center justify-around px-2 shadow-lg">
        <a href="/" class="flex flex-col items-center justify-center flex-1 py-1 no-underline transition-colors {{ request()->is('/') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-5 h-5 mb-1 {{ request()->is('/') ? 'stroke-emerald-700' : 'stroke-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[11px]">Beranda</span>
        </a>
        <a href="/wisata" class="flex flex-col items-center justify-center flex-1 py-1 no-underline transition-colors {{ request()->is('wisata*') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-5 h-5 mb-1 {{ request()->is('wisata*') ? 'stroke-emerald-700' : 'stroke-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="text-[11px]">Wisata</span>
        </a>
        <a href="/peta" class="flex flex-col items-center justify-center flex-1 py-1 no-underline transition-colors {{ request()->is('peta*') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-5 h-5 mb-1 {{ request()->is('peta*') ? 'stroke-emerald-700' : 'stroke-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span class="text-[11px]">Peta LBS</span>
        </a>
        <a href="/kalender" class="flex flex-col items-center justify-center flex-1 py-1 no-underline transition-colors {{ request()->is('kalender*') || request()->is('event*') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-5 h-5 mb-1 {{ request()->is('kalender*') || request()->is('event*') ? 'stroke-emerald-700' : 'stroke-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-[11px]">Kalender</span>
        </a>
        <a href="/fasilitas" class="flex flex-col items-center justify-center flex-1 py-1 no-underline transition-colors {{ request()->is('fasilitas*') ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-5 h-5 mb-1 {{ request()->is('fasilitas*') ? 'stroke-emerald-700' : 'stroke-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span class="text-[11px]">Fasilitas</span>
        </a>
    </nav>

    <!-- Footer Publik Modern -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12 mb-10">
                <!-- Kolom 1: Profil & Identitas Desa -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-xs">
                            GB
                        </div>
                        <div>
                            <span class="font-bold text-lg text-white tracking-tight">Desa Wisata Kampung Gedung Batin</span>
                            <span class="block text-xs text-slate-400">Cagar Budaya Resmi Kabupaten Way Kanan, Lampung</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Sistem informasi resmi penjelajahan cagar budaya rumah panggung tradisional kayu ulin ratusan tahun, pelestarian adat Pepadun, dan direktori kegiatan budaya terpadu dengan Location Based Services (LBS).
                    </p>
                    <div class="text-xs text-slate-500 font-mono flex items-center gap-2">
                        <span>📍 Titik Pusat Koordinat:</span>
                        <span class="text-slate-400">-4.540583, 104.664984</span>
                    </div>
                </div>

                <!-- Kolom 2: Navigasi Cepat -->
                <div>
                    <div class="font-semibold text-sm text-white mb-4 uppercase tracking-wider">
                        Eksplorasi
                    </div>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/wisata" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Objek Wisata & Cagar Budaya</a></li>
                        <li><a href="/peta" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Peta Interaktif & LBS</a></li>
                        <li><a href="/kalender" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Kalender Event Budaya</a></li>
                        <li><a href="/fasilitas" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Sarana & Fasilitas Desa</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Akses Pengelola -->
                <div>
                    <div class="font-semibold text-sm text-white mb-4 uppercase tracking-wider">
                        Portal Manajemen
                    </div>
                    <ul class="space-y-2.5 text-sm">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <li><a href="/admin" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Panel Administrator</a></li>
                            @else
                                <li><a href="/pengelola" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Panel Pengelola Wisata</a></li>
                            @endif
                            <li><a href="/profile" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Pengaturan Profil</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Masuk Akun Pengelola</a></li>
                            <li><a href="{{ route('register') }}" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Pendaftaran Pengelola</a></li>
                        @endauth
                        <li><a href="/api/wisata" target="_blank" class="text-slate-400 hover:text-emerald-400 transition-colors no-underline">Endpoint GeoJSON (API)</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright & Credits -->
            <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <div>
                    &copy; {{ date('Y') }} Sistem Informasi Manajemen Desa Wisata Kampung Gedung Batin (SIGEBAT). Seluruh hak cipta dilindungi.
                </div>
                <div class="flex items-center gap-4 text-slate-400">
                    <span>UIN Raden Intan Lampung</span>
                    <span>&bull;</span>
                    <span>Kabupaten Way Kanan</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
