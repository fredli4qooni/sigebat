<x-portal-layout>
    <x-slot:title>Jelajah Gedung Batin — Desa Wisata Kebudayaan Way Kanan</x-slot:title>
    <x-slot:description>Situs resmi Desa Wisata Kampung Gedung Batin. Eksplorasi cagar budaya rumah panggung tua, peta terdekat berbasis LBS, dan jadwal event budaya digital.</x-slot:description>

    <!-- 1. Hero Section (DESIGN.md 8.1) -->
    <section class="border-b-2 border-beton bg-putih py-8 md:py-14">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Kolom Kiri: Papan Rambu & Tombol Arah -->
                <div class="lg:col-span-6 space-y-6">
                    <x-papan warna="cokelat" class="p-6 sm:p-8">
                        <div class="text-sm text-putih opacity-90 mb-1 font-semibold">Desa wisata kebudayaan</div>
                        <h1 class="text-3xl sm:text-5xl font-bold font-papan text-putih leading-tight">
                            Kampung Gedung Batin
                        </h1>
                        <p class="mt-3 text-[17px] text-putih leading-relaxed max-w-prose">
                            Kawasan cagar budaya rumah panggung kayu berusia ratusan tahun, tradisi adat Pepadun, dan agenda festival tahunan terpadu.
                        </p>
                    </x-papan>

                    <!-- Tombol Penunjuk Rute Rambu -->
                    <div class="flex flex-col gap-3 pt-2">
                        <div>
                            <x-tombol-arah warna="hijau" href="/peta">
                                Cari wisata terdekat di peta (LBS)
                            </x-tombol-arah>
                        </div>
                        <div>
                            <x-tombol-arah warna="kuning" href="/kalender">
                                Lihat kalender event budaya
                            </x-tombol-arah>
                        </div>
                        <div>
                            <x-tombol-arah warna="cokelat" href="/wisata">
                                Jelajahi semua objek wisata
                            </x-tombol-arah>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Visual Representasi Cagar Budaya -->
                <div class="lg:col-span-6">
                    <div class="border-2 border-aspal rounded-papan overflow-hidden bg-beton">
                        <div class="aspect-[4/3] bg-cokelat-gelap text-putih p-8 flex flex-col justify-between relative overflow-hidden">
                            <div class="absolute inset-0 opacity-10 pointer-events-none">
                                <svg width="100%" height="100%">
                                    <defs>
                                        <pattern id="hero-grid" width="32" height="32" patternUnits="userSpaceOnUse">
                                            <path d="M 32 0 L 0 0 0 32" fill="none" stroke="#ffffff" stroke-width="1"/>
                                        </pattern>
                                    </defs>
                                    <rect width="100%" height="100%" fill="url(#hero-grid)" />
                                </svg>
                            </div>

                            <div class="relative z-10">
                                <span class="inline-block px-3 py-1 bg-hijau text-putih text-xs font-bold rounded-tag mb-3">
                                    Cagar Budaya Nasional &bull; SK Bupati Way Kanan
                                </span>
                                <h2 class="text-2xl sm:text-3xl font-papan font-bold text-putih leading-snug">
                                    Warisan Rumah Panggung Kayu Ulin Tertua di Way Kanan
                                </h2>
                                <p class="text-xs text-putih/80 mt-2 line-clamp-2">
                                    Dibangun tanpa paku besi dan bertahan kokoh melewati lintas generasi sejak abad ke-17 di tepi Sungai Way Besai.
                                </p>
                            </div>

                            <div class="relative z-10 pt-4 border-t border-putih/20 flex items-center justify-between text-xs text-putih/90 font-mono">
                                <span>Kecamatan Umpu Semenguk</span>
                                <span>Titik: -4.540583, 104.664984</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Statistik Cepat Desa -->
    <section class="border-b-2 border-beton bg-putih py-6">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 border-2 border-aspal rounded-kontrol bg-beton/40 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-kontrol bg-cokelat text-putih flex items-center justify-center text-xl font-bold flex-shrink-0">
                        🏡
                    </div>
                    <div>
                        <div class="text-2xl font-bold font-papan text-aspal">{{ $countWisata }} Objek Wisata</div>
                        <div class="text-xs text-abu">Cagar budaya & keindahan alam</div>
                    </div>
                </div>

                <div class="p-4 border-2 border-aspal rounded-kontrol bg-beton/40 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-kontrol bg-kuning text-aspal flex items-center justify-center text-xl font-bold flex-shrink-0">
                        📅
                    </div>
                    <div>
                        <div class="text-2xl font-bold font-papan text-aspal">{{ $countEvent }} Agenda Budaya</div>
                        <div class="text-xs text-abu">Pentas seni tari & upacara adat</div>
                    </div>
                </div>

                <div class="p-4 border-2 border-aspal rounded-kontrol bg-beton/40 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-kontrol bg-biru text-putih flex items-center justify-center text-xl font-bold flex-shrink-0">
                        🚻
                    </div>
                    <div>
                        <div class="text-2xl font-bold font-papan text-aspal">{{ $countFasilitas }} Sarana Fasilitas</div>
                        <div class="text-xs text-abu">Musala, toilet & sarana umum</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Objek Wisata Unggulan (DESIGN.md 8.2) -->
    <section class="py-12 md:py-16">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b-2 border-aspal pb-4">
                <div>
                    <span class="text-xs font-bold text-cokelat tracking-wider uppercase">Destinasi Pilihan</span>
                    <h2 class="text-3xl font-bold font-papan text-aspal mt-1">Tempat Wisata & Cagar Budaya</h2>
                </div>
                <a href="/wisata" class="text-sm font-bold text-aspal underline hover:text-cokelat">
                    Lihat semua wisata &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($wisataUnggulan as $w)
                    <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden flex flex-col justify-between hover:translate-y-[-2px] transition-transform">
                        <div>
                            <!-- Foto Objek Wisata -->
                            <div class="relative h-48 bg-beton overflow-hidden border-b-2 border-aspal">
                                @if($w->foto_utama)
                                    <img src="{{ $w->foto_url }}" alt="{{ $w->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-beton text-abu font-semibold text-sm">
                                        Foto belum tersedia
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 bg-cokelat text-putih text-xs font-bold rounded-tag">
                                        {{ $w->kategori?->nama ?? 'Wisata' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Detail Ringkas -->
                            <div class="p-5 space-y-2">
                                <h3 class="text-xl font-bold font-papan text-aspal leading-snug">
                                    <a href="{{ route('wisata.show', $w->slug) }}" class="no-underline text-aspal hover:text-cokelat">
                                        {{ $w->nama }}
                                    </a>
                                </h3>
                                <p class="text-xs text-abu line-clamp-2 leading-relaxed">
                                    {{ $w->deskripsi }}
                                </p>
                                <div class="pt-2 text-xs text-aspal flex items-center justify-between border-t border-beton">
                                    <span class="font-semibold">🎟️ {{ $w->harga_tiket ?: 'Gratis' }}</span>
                                    <span class="text-abu">⏰ {{ $w->jam_operasional ?: '08.00 - 17.00 WIB' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Buka Rincian -->
                        <div class="p-5 pt-0">
                            <a
                                href="{{ route('wisata.show', $w->slug) }}"
                                class="w-full h-[40px] px-4 bg-putih border-2 border-aspal text-aspal font-bold text-xs rounded-kontrol hover:bg-beton no-underline flex items-center justify-center gap-1.5"
                            >
                                <span>Buka Informasi Lengkap</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-abu">
                        Belum ada data objek wisata aktif yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 4. Peta & LBS Promo Banner (DESIGN.md 8.4) -->
    <section class="border-y-2 border-aspal bg-hijau text-putih py-12">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <div class="md:col-span-8 space-y-3">
                    <span class="px-3 py-1 bg-putih text-hijau text-xs font-bold rounded-tag">
                        Location Based Services (LBS)
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-bold font-papan text-putih leading-tight">
                        Temukan Destinasi & Fasilitas Terdekat dari Posisi Anda
                    </h2>
                    <p class="text-putih/90 text-sm sm:text-base max-w-xl leading-relaxed">
                        Sistem SIGEBAT menggunakan GPS perangkat Anda untuk menghitung jarak akurat (Haversine Formula) ke setiap rumah panggung adat dan fasilitas umum desa secara langsung tanpa perlu koneksi internet berkecepatan tinggi.
                    </p>
                </div>
                <div class="md:col-span-4 flex flex-col sm:flex-row md:flex-col gap-3">
                    <a
                        href="/peta"
                        class="h-[48px] px-6 bg-putih text-aspal border-2 border-aspal font-bold text-sm rounded-kontrol hover:bg-beton no-underline flex items-center justify-center gap-2"
                    >
                        <span>🗺️</span>
                        <span>Buka Peta Interaktif</span>
                    </a>
                    <a
                        href="/fasilitas"
                        class="h-[48px] px-6 bg-hijau-gelap text-putih border border-putih/30 font-bold text-sm rounded-kontrol hover:bg-hijau-gelap/80 no-underline flex items-center justify-center gap-2"
                    >
                        <span>🚻</span>
                        <span>Lihat Fasilitas Desa</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Agenda Event Budaya Digital (DESIGN.md 8.3) -->
    <section class="py-12 md:py-16 bg-putih border-b-2 border-beton">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b-2 border-aspal pb-4">
                <div>
                    <span class="text-xs font-bold text-kuning-gelap tracking-wider uppercase">Kalender Budaya</span>
                    <h2 class="text-3xl font-bold font-papan text-aspal mt-1">Kegiatan Adat & Festival Mendatang</h2>
                </div>
                <a href="/kalender" class="text-sm font-bold text-aspal underline hover:text-kuning-gelap">
                    Buka kalender acara lengkap &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($eventMendatang as $ev)
                    <div class="bg-beton/40 border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-tag text-xs font-bold {{ $ev->status_turunan_badge_class }}">
                                    {{ $ev->status_turunan }}
                                </span>
                                <span class="text-xs font-bold text-cokelat">{{ $ev->kategori?->nama }}</span>
                            </div>

                            <h3 class="text-xl font-bold font-papan text-aspal leading-tight">
                                {{ $ev->judul }}
                            </h3>

                            <div class="text-xs font-mono text-abu space-y-1">
                                <div class="flex items-center gap-1.5 text-aspal font-semibold">
                                    <span>📅</span>
                                    <span>
                                        {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d M Y') : '' }}
                                        @if($ev->is_multi_hari)
                                            s/d {{ $ev->tanggal_selesai ? $ev->tanggal_selesai->translatedFormat('d M Y') : '' }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span>⏰</span>
                                    <span>{{ substr($ev->jam_mulai, 0, 5) }} - {{ substr($ev->jam_selesai, 0, 5) }} WIB</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span>📍</span>
                                    <span class="line-clamp-1">{{ $ev->lokasi }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-abu line-clamp-3 leading-relaxed">
                                {{ $ev->deskripsi }}
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-beton">
                            <a href="{{ route('event.show', $ev->slug) }}" class="text-xs font-bold text-aspal underline hover:text-kuning-gelap flex items-center justify-between">
                                <span>Detail jadwal acara</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-abu">
                        Belum ada kegiatan budaya yang dijadwalkan dalam waktu dekat.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-portal-layout>
