<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Jelajah Gedung Batin' }} — SIGEBAT Desa Wisata</title>
    <meta name="description" content="{{ $description ?? 'Sistem Informasi Manajemen Desa Wisata Kampung Gedung Batin berbasis Location Based Services (LBS) dan Kalender Event Budaya Digital.' }}">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="{{ $title ?? 'Desa Wisata Kampung Gedung Batin' }}">
    <meta property="og:description" content="{{ $description ?? 'Informasi objek wisata adat pepadun, peta LBS terdekat, dan jadwal event budaya digital Way Kanan.' }}">
    <meta property="og:type" content="website">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-beton text-aspal font-sans antialiased selection:bg-kuning selection:text-aspal min-h-screen flex flex-col">

    <!-- Kepala Navigasi Atas (Desktop & Mobile) -->
    <header class="sticky top-0 z-40 bg-putih border-b-2 border-aspal">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 h-[68px] flex items-center justify-between">
            <!-- Logo Wordmark (DESIGN.md 10.4) -->
            <a href="/" class="flex items-center gap-3 no-underline">
                <div class="w-10 h-10 bg-cokelat border-2 border-putih rounded-kontrol flex items-center justify-center text-putih flex-shrink-0">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 256 256">
                        <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                    </svg>
                </div>
                <div class="leading-none">
                    <span class="block font-papan font-bold text-xl text-aspal tracking-tight">Jelajah Gedung Batin</span>
                    <span class="text-xs text-abu">Way Kanan, Lampung</span>
                </div>
            </a>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center gap-2">
                <a href="/" class="px-3.5 py-2 rounded-kontrol font-semibold text-[15px] no-underline {{ request()->is('/') ? 'bg-aspal text-putih' : 'text-aspal hover:bg-beton' }}">
                    Beranda
                </a>
                <a href="/wisata" class="px-3.5 py-2 rounded-kontrol font-semibold text-[15px] no-underline {{ request()->is('wisata*') ? 'bg-cokelat text-putih' : 'text-aspal hover:bg-beton' }}">
                    Wisata
                </a>
                <a href="/peta" class="px-3.5 py-2 rounded-kontrol font-semibold text-[15px] no-underline {{ request()->is('peta*') ? 'bg-hijau text-putih' : 'text-aspal hover:bg-beton' }}">
                    Peta & LBS
                </a>
                <a href="/fasilitas" class="px-3.5 py-2 rounded-kontrol font-semibold text-[15px] no-underline {{ request()->is('fasilitas*') ? 'bg-biru text-putih' : 'text-aspal hover:bg-beton' }}">
                    Fasilitas
                </a>
                <a href="/kalender" class="px-3.5 py-2 rounded-kontrol font-semibold text-[15px] no-underline {{ request()->is('kalender*') ? 'bg-kuning text-aspal' : 'text-aspal hover:bg-beton' }}">
                    Kalender Event
                </a>

                <div class="h-6 w-[2px] bg-beton mx-2"></div>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="/admin" class="px-4 py-2 rounded-kontrol bg-aspal text-putih font-papan font-bold text-[14px] no-underline hover:bg-black">
                            Panel Admin
                        </a>
                    @else
                        <a href="/pengelola" class="px-4 py-2 rounded-kontrol bg-cokelat text-putih font-papan font-bold text-[14px] no-underline hover:bg-cokelat-gelap">
                            Panel Pengelola
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-kontrol border-2 border-aspal text-aspal font-papan font-bold text-[14px] no-underline hover:bg-beton">
                        Masuk Pengelola
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Konten Utama Halaman -->
    <main class="flex-1 pb-24 md:pb-12">
        {{ $slot }}
    </main>

    <!-- Mobile Bottom Navigation (DESIGN.md 7.10: Sticky 56px Bar) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-[60px] bg-putih border-t-2 border-aspal z-50 flex items-center justify-around px-2">
        <a href="/" class="flex flex-col items-center justify-center flex-1 py-1 no-underline {{ request()->is('/') ? 'text-aspal font-bold' : 'text-abu' }}">
            <span class="text-base leading-none">🏠</span>
            <span class="text-[11px] mt-1 font-semibold">Beranda</span>
        </a>
        <a href="/wisata" class="flex flex-col items-center justify-center flex-1 py-1 no-underline {{ request()->is('wisata*') ? 'text-cokelat font-bold' : 'text-abu' }}">
            <span class="text-base leading-none">🏡</span>
            <span class="text-[11px] mt-1 font-semibold">Wisata</span>
        </a>
        <a href="/peta" class="flex flex-col items-center justify-center flex-1 py-1 no-underline {{ request()->is('peta*') ? 'text-hijau font-bold' : 'text-abu' }}">
            <span class="text-base leading-none">🗺️</span>
            <span class="text-[11px] mt-1 font-semibold">Peta LBS</span>
        </a>
        <a href="/kalender" class="flex flex-col items-center justify-center flex-1 py-1 no-underline {{ request()->is('kalender*') ? 'text-kuning-gelap font-bold' : 'text-abu' }}">
            <span class="text-base leading-none">📅</span>
            <span class="text-[11px] mt-1 font-semibold">Kalender</span>
        </a>
        <a href="/fasilitas" class="flex flex-col items-center justify-center flex-1 py-1 no-underline {{ request()->is('fasilitas*') ? 'text-biru font-bold' : 'text-abu' }}">
            <span class="text-base leading-none">🚻</span>
            <span class="text-[11px] mt-1 font-semibold">Fasilitas</span>
        </a>
    </nav>

    <!-- Footer Publik (DESIGN.md 8.8) -->
    <footer class="bg-aspal text-putih border-t-2 border-aspal mt-auto">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Kolom 1: Profil Desa -->
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-cokelat border border-putih rounded-kontrol flex items-center justify-center text-putih">
                            <span class="font-bold text-sm">GB</span>
                        </div>
                        <span class="font-papan font-bold text-xl text-putih tracking-tight">Desa Wisata Kampung Gedung Batin</span>
                    </div>
                    <p class="text-sm text-putih/75 max-w-md leading-relaxed">
                        Kawasan cagar budaya rumah panggung tradisional kayu ulin berusia ratusan tahun dan warisan adat Pepadun Kabupaten Way Kanan, Provinsi Lampung.
                    </p>
                    <div class="text-xs text-putih/60 font-mono">
                        Kec. Umpu Semenguk, Kab. Way Kanan, Lampung &bull; Titik Pusat: -4.540583, 104.664984
                    </div>
                </div>

                <!-- Kolom 2: Navigasi Cepat -->
                <div>
                    <div class="font-papan font-bold text-base text-putih mb-3 border-b border-putih/20 pb-1">
                        Eksplorasi
                    </div>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/wisata" class="text-putih/80 hover:text-kuning no-underline">Objek Wisata & Cagar Budaya</a></li>
                        <li><a href="/peta" class="text-putih/80 hover:text-kuning no-underline">Peta Interaktif & LBS</a></li>
                        <li><a href="/kalender" class="text-putih/80 hover:text-kuning no-underline">Kalender Event Budaya</a></li>
                        <li><a href="/fasilitas" class="text-putih/80 hover:text-kuning no-underline">Fasilitas Pendukung</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Ruang Pengelola -->
                <div>
                    <div class="font-papan font-bold text-base text-putih mb-3 border-b border-putih/20 pb-1">
                        Akses Pengelola
                    </div>
                    <ul class="space-y-2 text-sm">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <li><a href="/admin" class="text-putih/80 hover:text-kuning no-underline">Panel Administrator</a></li>
                            @else
                                <li><a href="/pengelola" class="text-putih/80 hover:text-kuning no-underline">Panel Pengelola Wisata</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}" class="text-putih/80 hover:text-kuning no-underline">Masuk ke Sistem</a></li>
                            <li><a href="{{ route('register') }}" class="text-putih/80 hover:text-kuning no-underline">Pendaftaran Pengelola</a></li>
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="pt-6 border-t border-putih/15 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-putih/60">
                <div>
                    &copy; {{ date('Y') }} Sistem Informasi Manajemen Desa Wisata (SIGEBAT) Kampung Gedung Batin.
                </div>
                <div>
                    Standar Desain Rambu Kampung &bull; Tanpa Gradien &bull; Aksesibel
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
