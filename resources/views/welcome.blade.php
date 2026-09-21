<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jelajah Gedung Batin — Desa Wisata Kebudayaan Way Kanan</title>
    <meta name="description" content="Sistem Informasi Manajemen Desa Wisata Kampung Gedung Batin berbasis Location Based Services (LBS) dan Kalender Event Budaya Digital.">

    <!-- Open Graph Meta (PUB-08) -->
    <meta property="og:title" content="Desa Wisata Kampung Gedung Batin">
    <meta property="og:description" content="Informasi objek wisata adat pepadun, peta terdekat, dan jadwal event budaya digital.">
    <meta property="og:type" content="website">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-putih text-aspal font-sans antialiased selection:bg-kuning selection:text-aspal min-h-screen flex flex-col">

    <!-- Kepala Navigasi Atas (Desktop & Mobile) -->
    <header class="sticky top-0 z-40 bg-putih border-b-2 border-aspal">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 h-[68px] flex items-center justify-between">
            <!-- Logo Wordmark (DESIGN.md 10.4) -->
            <a href="/" class="flex items-center gap-3 no-underline">
                <div class="w-10 h-10 bg-cokelat border-2 border-putih rounded-kontrol flex items-center justify-center text-putih">
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
                <a href="/" class="px-3.5 py-2 rounded-kontrol bg-aspal text-putih font-semibold text-[15px] no-underline">
                    Beranda
                </a>
                <a href="/wisata" class="px-3.5 py-2 rounded-kontrol text-aspal hover:bg-beton font-semibold text-[15px] no-underline">
                    Wisata
                </a>
                <a href="/peta" class="px-3.5 py-2 rounded-kontrol text-aspal hover:bg-beton font-semibold text-[15px] no-underline">
                    Peta & LBS
                </a>
                <a href="/kalender" class="px-3.5 py-2 rounded-kontrol text-aspal hover:bg-beton font-semibold text-[15px] no-underline">
                    Kalender Event
                </a>

                <div class="h-6 w-[2px] bg-beton mx-2"></div>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="/admin" class="px-4 py-2 rounded-kontrol bg-cokelat text-putih font-papan font-bold text-[15px] no-underline hover:bg-cokelat-gelap">
                            Panel Admin
                        </a>
                    @else
                        <a href="/pengelola" class="px-4 py-2 rounded-kontrol bg-cokelat text-putih font-papan font-bold text-[15px] no-underline hover:bg-cokelat-gelap">
                            Panel Pengelola
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-kontrol border-2 border-aspal text-aspal font-papan font-bold text-[15px] no-underline hover:bg-beton">
                        Masuk Pengelola
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-1 pb-20 md:pb-12">
        <!-- Hero Section (DESIGN.md 8.1) -->
        <section class="border-b-2 border-beton bg-putih py-8 md:py-14">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <!-- Kolom Kiri: Papan Cokelat & Tombol Arah -->
                    <div class="lg:col-span-6 space-y-6">
                        <x-papan warna="cokelat" class="p-6 sm:p-8">
                            <div class="text-sm text-putih opacity-90 mb-1 font-semibold">Desa wisata kebudayaan</div>
                            <h1 class="text-3xl sm:text-5xl font-bold font-papan text-putih leading-tight">
                                Kampung Gedung Batin
                            </h1>
                            <p class="mt-3 text-[17px] text-putih leading-relaxed max-w-prose">
                                Rumah adat Lampung, kegiatan adat, dan jadwal acara kampung, dalam satu situs terpadu.
                            </p>
                        </x-papan>

                        <!-- Tombol Penunjuk Rute Rambu -->
                        <div class="flex flex-col gap-3.5 pt-2">
                            <div>
                                <x-tombol-arah warna="hijau" href="/peta">
                                    Cari wisata terdekat
                                </x-tombol-arah>
                            </div>
                            <div>
                                <x-tombol-arah warna="kuning" href="/kalender">
                                    Lihat kalender acara
                                </x-tombol-arah>
                            </div>
                            <div>
                                <x-tombol-arah warna="cokelat" href="/wisata">
                                    Semua tempat wisata
                                </x-tombol-arah>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Foto Representasi / Karakter Kampung -->
                    <div class="lg:col-span-6">
                        <div class="border-2 border-aspal rounded-kartu overflow-hidden bg-beton relative">
                            <!-- Visual Banner Ilustrasi Khas Rumah Panggung Kayu -->
                            <div class="aspect-[4/3] bg-cokelat-gelap text-putih p-8 flex flex-col justify-between relative overflow-hidden">
                                <div class="absolute inset-0 opacity-10 pointer-events-none">
                                    <svg width="100%" height="100%">
                                        <defs>
                                            <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#ffffff" stroke-width="1"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100%" height="100%" fill="url(#grid-pattern)" />
                                    </svg>
                                </div>
                                <div class="relative z-10">
                                    <span class="inline-block px-3 py-1 bg-hijau text-putih text-xs font-bold rounded-tag mb-3">
                                        Cagar Budaya Way Kanan
                                    </span>
                                    <h2 class="text-2xl sm:text-3xl font-papan font-bold text-putih leading-snug">
                                        Warisan Adat Pepadun Berusia Ratusan Tahun
                                    </h2>
                                </div>
                                <div class="relative z-10 pt-4 border-t border-putih/20 flex items-center justify-between text-sm text-putih/90">
                                    <span>Kabupaten Way Kanan</span>
                                    <span>Kecamatan Umpu Semenguk</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Seksi Acara Budaya Terdekat (PUB-01) -->
        <section class="py-10 border-b-2 border-beton bg-putih">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-sm font-semibold text-abu">Agenda kegiatan</span>
                        <h2 class="text-2xl sm:text-3xl font-papan font-bold text-aspal mt-0.5">Acara Berikutnya</h2>
                    </div>
                    <a href="/kalender" class="text-aspal underline font-semibold text-[15px] hover:text-cokelat">
                        Buka kalender lengkap
                    </a>
                </div>

                @php
                    $events = \App\Models\EventBudaya::active()
                        ->orderBy('tanggal_mulai', 'asc')
                        ->take(2)
                        ->get();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($events as $event)
                        <div class="papan-acara">
                            <x-papan warna="kuning" class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <span class="text-sm font-bold text-aspal opacity-80">
                                            {{ $event->kategori?->nama }}
                                        </span>
                                        <h3 class="text-xl sm:text-2xl font-bold font-papan text-aspal mt-1">
                                            {{ $event->judul }}
                                        </h3>
                                    </div>
                                    <div>
                                        <x-tag-status :status="$event->status_turunan" />
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-aspal/20 space-y-1.5 text-sm text-aspal">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 fill-current" viewBox="0 0 256 256"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM72,48v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80H48V48ZM208,208H48V96H208V208Z"/></svg>
                                        <span class="font-semibold">
                                            {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}
                                            @if($event->is_multi_hari)
                                                s.d. {{ \Carbon\Carbon::parse($event->tanggal_selesai)->translatedFormat('d F Y') }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 fill-current" viewBox="0 0 256 256"><path d="M128,64a40,40,0,1,0,40,40A40,40,0,0,0,128,64Zm0,64a24,24,0,1,1,24-24A24,24,0,0,1,128,128Zm0-112a88.1,88.1,0,0,0-88,88c0,31.4,14.51,64.68,42,96.25a254.19,254.19,0,0,0,41.45,38.3,8,8,0,0,0,9.18,0A254.19,254.19,0,0,0,174,204.25c27.46-31.57,42-64.85,42-96.25A88.1,88.1,0,0,0,128,16Zm0,206c-16.53-13-72-60.75-72-118a72,72,0,0,1,144,0C200,161.25,144.53,209,128,222Z"/></svg>
                                        <span>{{ $event->lokasi }}</span>
                                    </div>
                                </div>
                            </x-papan>
                        </div>
                    @empty
                        <div class="col-span-2 p-8 border-2 border-beton rounded-papan text-center text-abu">
                            Belum ada agenda acara mendatang.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Seksi Tempat Wisata Unggulan (PUB-01) -->
        <section class="py-10 bg-putih">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-sm font-semibold text-abu">Objek dan cagar budaya</span>
                        <h2 class="text-2xl sm:text-3xl font-papan font-bold text-aspal mt-0.5">Tempat Wisata Kampung</h2>
                    </div>
                    <a href="/wisata" class="text-aspal underline font-semibold text-[15px] hover:text-cokelat">
                        Lihat semua wisata
                    </a>
                </div>

                @php
                    $wisatas = \App\Models\ObjekWisata::active()->with('kategori')->take(3)->get();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wisatas as $wisata)
                        <div class="border-2 border-aspal rounded-kartu overflow-hidden flex flex-col bg-putih">
                            <!-- Foto / Thumbnail (DESIGN.md 7.3) -->
                            <div class="aspect-[4/3] bg-beton flex items-center justify-center relative overflow-hidden">
                                @if($wisata->foto_url)
                                    <img src="{{ $wisata->foto_url }}" alt="{{ $wisata->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center p-4">
                                        <svg class="w-12 h-12 fill-current text-abu mx-auto mb-1" viewBox="0 0 256 256">
                                            <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                                        </svg>
                                        <span class="text-xs text-abu">{{ $wisata->kategori?->nama }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Papan Nama Cokelat (DESIGN.md 7.3) -->
                            <div class="papan papan--cokelat !rounded-none !border-x-0 !border-b-0 border-t-2 border-aspal p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="text-xs text-putih/80 font-semibold mb-1">
                                        {{ $wisata->kategori?->nama }}
                                    </div>
                                    <h3 class="text-lg font-bold font-papan text-putih leading-tight">
                                        {{ $wisata->nama }}
                                    </h3>
                                </div>

                                <div class="mt-4 pt-3 border-t border-putih/20 flex items-center justify-between text-xs text-putih">
                                    <span>{{ $wisata->jam_operasional ?? 'Buka setiap hari' }}</span>
                                    <a href="/wisata/{{ $wisata->slug }}" class="text-putih underline font-bold hover:text-kuning">
                                        Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <!-- Bilah Navigasi Bawah Mobile (DESIGN.md 7.8) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-[64px] bg-putih border-t-[3px] border-aspal flex items-center justify-around z-50">
        <a href="/" class="flex flex-col items-center justify-center text-aspal text-xs font-semibold">
            <span class="p-1 rounded-kontrol bg-aspal text-putih mb-0.5">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 256 256"><path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/></svg>
            </span>
            Beranda
        </a>
        <a href="/wisata" class="flex flex-col items-center justify-center text-aspal text-xs font-semibold">
            <span class="p-1 rounded-kontrol text-cokelat mb-0.5">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 256 256"><path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/></svg>
            </span>
            Wisata
        </a>
        <a href="/peta" class="flex flex-col items-center justify-center text-aspal text-xs font-semibold">
            <span class="p-1 rounded-kontrol text-hijau mb-0.5">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 256 256"><path d="M228.92,49.69a8,8,0,0,0-6.86-1.45L160.93,63.5,99.94,33a8,8,0,0,0-6.88.23l-64,32A8,8,0,0,0,24,72.37V192a8,8,0,0,0,11.08,7.44l60.99-15.26,61,30.49a8,8,0,0,0,6.88-.23l64-32A8,8,0,0,0,232,174.63V55A8,8,0,0,0,228.92,49.69ZM96,52.28l48,24V203.72l-48-24ZM40,82.72l40-20V173.28l-40,10ZM216,164.28l-40,20V91.72l40-10Z"/></svg>
            </span>
            Peta LBS
        </a>
        <a href="/kalender" class="flex flex-col items-center justify-center text-aspal text-xs font-semibold">
            <span class="p-1 rounded-kontrol text-kuning mb-0.5">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 256 256"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM72,48v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80H48V48ZM208,208H48V96H208V208Z"/></svg>
            </span>
            Kalender
        </a>
    </nav>

    <!-- Footer Sistem -->
    <footer class="border-t-2 border-aspal bg-putih py-6">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-abu">
            <div>
                &copy; {{ date('Y') }} Sistem Informasi Manajemen Desa Wisata Kampung Gedung Batin
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span>Peta: &copy; OpenStreetMap contributors</span>
                <span>Zona Waktu: WIB (Asia/Jakarta)</span>
            </div>
        </div>
    </footer>

</body>
</html>
