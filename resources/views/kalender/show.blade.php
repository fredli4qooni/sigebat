<x-portal-layout>
    <x-slot:title>{{ $event->judul }} — Kalender Event Budaya SIGEBAT</x-slot:title>
    <x-slot:description>{{ Str::limit(strip_tags($event->deskripsi), 160) }}</x-slot:description>

    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 py-8 md:py-12 space-y-8" x-data="{ copied: false }">
        <!-- Breadcrumb & Tombol Kembali -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <nav class="flex items-center gap-2 text-xs font-semibold text-abu">
                <a href="/" class="hover:text-aspal no-underline text-abu">Beranda</a>
                <span>/</span>
                <a href="{{ route('kalender.index') }}" class="hover:text-aspal no-underline text-abu">Kalender Event</a>
                <span>/</span>
                <span class="text-aspal truncate max-w-[200px] sm:max-w-md">{{ $event->judul }}</span>
            </nav>

            <a
                href="{{ route('kalender.index') }}"
                class="px-3.5 py-1.5 bg-putih border-2 border-aspal text-aspal font-bold text-xs rounded-kontrol hover:bg-beton no-underline flex items-center gap-1.5"
            >
                <span>&larr;</span>
                <span>Kembali ke Kalender</span>
            </a>
        </div>

        <!-- Kartu Utama Detail Event -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <!-- Header Judul & Status Badge -->
            <div class="p-6 md:p-8 bg-beton/40 border-b-2 border-aspal space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 text-xs font-bold rounded-tag {{ $event->status_turunan_badge_class }}">
                        {{ $event->status_turunan }}
                    </span>
                    <span class="px-3 py-1 bg-aspal text-putih text-xs font-bold rounded-tag">
                        {{ $event->kategori?->nama ?? 'Event Budaya' }}
                    </span>
                    @if($event->is_multi_hari)
                        <span class="px-2.5 py-1 bg-cokelat text-putih text-xs font-bold rounded-tag">
                            Kegiatan Multi-Hari
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl md:text-5xl font-bold font-papan text-aspal leading-tight">
                    {{ $event->judul }}
                </h1>
            </div>

            <!-- Konten 2 Kolom: Kiri Poster & Aksi Kalender, Kanan Jadwal & Deskripsi -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 p-6 md:p-8">
                <!-- Kolom Kiri: Poster & Tindakan (4 Kolom di Desktop) -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Poster Event -->
                    <div class="border-2 border-aspal rounded-papan overflow-hidden bg-beton">
                        @if($event->poster)
                            <img
                                src="{{ $event->poster_url }}"
                                alt="{{ $event->judul }}"
                                class="w-full h-auto max-h-[480px] object-cover"
                            >
                        @else
                            <div class="py-20 px-6 flex flex-col items-center justify-center text-center text-abu space-y-2">
                                <span class="text-5xl">🎭</span>
                                <span class="font-bold text-sm text-aspal">Desa Wisata Kampung Gedung Batin</span>
                                <span class="text-xs">Poster visual kegiatan belum diunggah</span>
                            </div>
                        @endif
                    </div>

                    <!-- Panel Simpan ke Kalender & Bagikan -->
                    <div class="bg-beton/60 border-2 border-aspal rounded-papan p-5 space-y-4">
                        <div class="font-papan font-bold text-base text-aspal pb-1 border-b border-aspal/20">
                            Tambahkan ke Kalender Anda
                        </div>

                        <div class="space-y-2">
                            <!-- Google Calendar Button -->
                            <a
                                href="{{ $gcalUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full h-[46px] px-4 bg-putih border-2 border-aspal text-aspal font-bold text-xs sm:text-sm rounded-kontrol hover:bg-beton no-underline flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 fill-current text-biru" viewBox="0 0 24 24">
                                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                                </svg>
                                <span>Simpan di Google Calendar</span>
                            </a>

                            <!-- iCal / .ics Download Button -->
                            <a
                                href="{{ route('event.ics', $event->slug) }}"
                                class="w-full h-[46px] px-4 bg-putih border-2 border-aspal text-aspal font-bold text-xs sm:text-sm rounded-kontrol hover:bg-beton no-underline flex items-center justify-center gap-2"
                            >
                                <span>📥</span>
                                <span>Unduh Berkas Kalender (.ICS)</span>
                            </a>
                        </div>

                        <div class="pt-2 border-t border-aspal/20 space-y-2">
                            <div class="text-xs font-bold text-abu">Bagikan Informasi Ini:</div>
                            <div class="flex items-center gap-2">
                                <!-- WhatsApp Share -->
                                @php
                                    $waText = urlencode("Ikuti acara budaya: *{$event->judul}* di Kampung Gedung Batin, Way Kanan pada " . ($event->tanggal_mulai ? $event->tanggal_mulai->translatedFormat('d F Y') : '') . ". Rincian selengkapnya: " . route('event.show', $event->slug));
                                @endphp
                                <a
                                    href="https://api.whatsapp.com/send?text={{ $waText }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex-1 h-[40px] px-3 bg-hijau text-putih font-bold text-xs rounded-kontrol hover:bg-hijau/90 no-underline flex items-center justify-center gap-1.5"
                                >
                                    <span>💬</span>
                                    <span>WhatsApp</span>
                                </a>

                                <!-- Salin Tautan -->
                                <button
                                    type="button"
                                    @click="
                                        navigator.clipboard.writeText(window.location.href);
                                        copied = true;
                                        setTimeout(() => copied = false, 2500);
                                    "
                                    class="flex-1 h-[40px] px-3 bg-putih border-2 border-aspal text-aspal font-bold text-xs rounded-kontrol hover:bg-beton flex items-center justify-center gap-1.5"
                                >
                                    <span x-show="!copied">🔗 Salin Tautan</span>
                                    <span x-show="copied" class="text-hijau font-bold">✓ Tersalin!</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Waktu, Lokasi, Narasi (7 Kolom di Desktop) -->
                <div class="lg:col-span-7 space-y-8">
                    <!-- Ringkasan Waktu & Tempat (Papan Rambu) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Tanggal Pelaksanaan -->
                        <div class="bg-beton/40 border-2 border-aspal rounded-papan p-4 space-y-1">
                            <span class="text-xs font-bold text-abu uppercase tracking-wider">Tanggal Pelaksanaan</span>
                            <div class="text-base sm:text-lg font-bold font-papan text-aspal">
                                📅 {{ $event->tanggal_mulai ? $event->tanggal_mulai->translatedFormat('l, d F Y') : '' }}
                            </div>
                            @if($event->is_multi_hari)
                                <div class="text-xs text-cokelat font-bold mt-1">
                                    Sampai {{ $event->tanggal_selesai ? $event->tanggal_selesai->translatedFormat('l, d F Y') : '' }}
                                </div>
                            @endif
                        </div>

                        <!-- Jam Operasional -->
                        <div class="bg-beton/40 border-2 border-aspal rounded-papan p-4 space-y-1">
                            <span class="text-xs font-bold text-abu uppercase tracking-wider">Waktu Acara</span>
                            <div class="text-base sm:text-lg font-bold font-papan text-aspal">
                                ⏰ {{ substr($event->jam_mulai, 0, 5) }} - {{ substr($event->jam_selesai, 0, 5) }} WIB
                            </div>
                            <div class="text-xs text-abu mt-1 font-mono">
                                Waktu Indonesia Barat (WIB)
                            </div>
                        </div>

                        <!-- Tempat & Lokasi -->
                        <div class="sm:col-span-2 bg-beton/40 border-2 border-aspal rounded-papan p-4 space-y-1">
                            <span class="text-xs font-bold text-abu uppercase tracking-wider">Lokasi Kegiatan</span>
                            <div class="text-base sm:text-lg font-bold font-papan text-aspal">
                                📍 {{ $event->lokasi ?: 'Kawasan Adat Kampung Gedung Batin' }}
                            </div>
                            <div class="text-xs text-abu">
                                Kampung Gedung Batin, Kec. Umpu Semenguk, Kab. Way Kanan, Lampung
                            </div>
                        </div>
                    </div>

                    <!-- Narasi & Deskripsi Lengkap Acara -->
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold font-papan text-aspal border-b-2 border-aspal pb-2">
                            Tentang Acara Budaya Ini
                        </h2>
                        <div class="text-sm md:text-base leading-relaxed text-aspal/90 whitespace-pre-line font-normal">
                            {{ $event->deskripsi }}
                        </div>
                    </div>

                    <!-- Etika Berkunjung & Kearifan Lokal -->
                    <div class="bg-kuning/10 border-2 border-kuning-gelap rounded-papan p-5 space-y-2">
                        <div class="flex items-center gap-2 font-papan font-bold text-base text-aspal">
                            <span>ℹ️</span>
                            <span>Etika dan Tata Tertib Wisatawan Adat</span>
                        </div>
                        <ul class="list-disc list-inside text-xs md:text-sm text-aspal/80 space-y-1 pl-1 leading-relaxed">
                            <li>Berpakaian sopan dan rapi saat menghadiri kegiatan upacara atau ritual adat.</li>
                            <li>Menghormati pemuka adat (Penyimbang Marga) dan masyarakat lokal setempat.</li>
                            <li>Meminta izin terlebih dahulu sebelum mengambil foto dokumentasi ritual khusus.</li>
                            <li>Menjaga kebersihan dan tidak membuang sampah sembarangan di sekitar kawasan cagar budaya.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekomendasi Kegiatan Budaya Lainnya -->
        @if($eventLain->count() > 0)
            <div class="space-y-6 pt-6">
                <div class="border-b-2 border-aspal pb-3">
                    <span class="text-xs font-bold text-kuning-gelap tracking-wider uppercase">Eksplorasi Lanjutan</span>
                    <h2 class="text-2xl md:text-3xl font-bold font-papan text-aspal mt-0.5">
                        Agenda Budaya Lainnya
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($eventLain as $evLain)
                        <div class="bg-putih border-2 border-aspal rounded-papan p-5 flex flex-col justify-between hover:translate-y-[-2px] transition-transform">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-tag text-xs font-bold {{ $evLain->status_turunan_badge_class }}">
                                        {{ $evLain->status_turunan }}
                                    </span>
                                    <span class="text-xs font-bold text-cokelat">{{ $evLain->kategori?->nama }}</span>
                                </div>
                                <h3 class="text-xl font-bold font-papan text-aspal">
                                    <a href="{{ route('event.show', $evLain->slug) }}" class="no-underline text-aspal hover:text-cokelat">
                                        {{ $evLain->judul }}
                                    </a>
                                </h3>
                                <div class="text-xs text-abu font-semibold">
                                    📅 {{ $evLain->tanggal_mulai ? $evLain->tanggal_mulai->translatedFormat('d M Y') : '' }}
                                    @if($evLain->is_multi_hari)
                                        s/d {{ $evLain->tanggal_selesai ? $evLain->tanggal_selesai->translatedFormat('d M Y') : '' }}
                                    @endif
                                    &bull; 📍 {{ $evLain->lokasi }}
                                </div>
                                <p class="text-xs text-abu line-clamp-2 leading-relaxed">
                                    {{ $evLain->deskripsi }}
                                </p>
                            </div>

                            <div class="pt-4 mt-4 border-t border-beton">
                                <a
                                    href="{{ route('event.show', $evLain->slug) }}"
                                    class="text-xs font-bold text-aspal underline hover:text-cokelat flex items-center justify-between"
                                >
                                    <span>Buka Rincian Acara</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-portal-layout>
