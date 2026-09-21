<x-portal-layout>
    <x-slot:title>{{ $event->judul }} — Kalender Event Budaya SIGEBAT</x-slot:title>
    <x-slot:description>{{ Str::limit(strip_tags($event->deskripsi), 160) }}</x-slot:description>
    @if($event->poster)
        <x-slot:image>{{ $event->poster_url }}</x-slot:image>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 space-y-8" x-data="{ copied: false }">
        <!-- Breadcrumb & Tombol Kembali -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
                <a href="/" class="hover:text-slate-900 no-underline text-slate-500">Beranda</a>
                <span>/</span>
                <a href="{{ route('kalender.index') }}" class="hover:text-slate-900 no-underline text-slate-500">Kalender Event</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold truncate max-w-[200px] sm:max-w-md">{{ $event->judul }}</span>
            </nav>

            <a
                href="{{ route('kalender.index') }}"
                class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-semibold text-xs rounded-xl hover:bg-slate-50 no-underline flex items-center gap-1.5 transition-all shadow-xs"
            >
                <span>&larr;</span>
                <span>Kembali ke Kalender</span>
            </a>
        </div>

        <!-- Kartu Utama Detail Event Modern -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <!-- Header Judul & Status Badge -->
            <div class="p-6 md:p-8 bg-gradient-to-r from-slate-900 via-slate-850 to-amber-950 text-white space-y-4">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full backdrop-blur-md shadow-xs
                        {{ $event->status_turunan === 'Berlangsung' ? 'bg-emerald-500 text-slate-950 font-bold' : ($event->status_turunan === 'Akan datang' ? 'bg-amber-400 text-slate-950 font-bold' : 'bg-slate-700 text-white') }}
                    ">
                        {{ $event->status_turunan }}
                    </span>
                    <span class="px-3 py-1 bg-white/15 border border-white/20 text-white text-xs font-medium rounded-full backdrop-blur-sm">
                        {{ $event->kategori?->nama ?? 'Event Budaya' }}
                    </span>
                    @if($event->is_multi_hari)
                        <span class="px-3 py-1 bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold rounded-full">
                            Kegiatan Multi-Hari
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white leading-tight">
                    {{ $event->judul }}
                </h1>
            </div>

            <!-- Konten 2 Kolom: Kiri Poster & Aksi Kalender, Kanan Jadwal & Deskripsi -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 p-6 md:p-8">
                <!-- Kolom Kiri: Poster & Tindakan (5 Kolom di Desktop) -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Poster Event -->
                    <div class="card-modern overflow-hidden p-0 bg-slate-100">
                        @if($event->poster)
                            <img
                                src="{{ $event->poster_url }}"
                                alt="{{ $event->judul }}"
                                class="w-full h-auto max-h-[480px] object-cover"
                            >
                        @else
                            <div class="py-20 px-6 flex flex-col items-center justify-center text-center text-slate-400 space-y-2">
                                <svg class="w-12 h-12 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-bold text-sm text-slate-700">Desa Wisata Kampung Gedung Batin</span>
                                <span class="text-xs">Poster visual kegiatan belum diunggah</span>
                            </div>
                        @endif
                    </div>

                    <!-- Panel Simpan ke Kalender & Bagikan -->
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 space-y-5">
                        <div class="font-bold text-sm text-slate-900 pb-2 border-b border-slate-200">
                            Tambahkan ke Kalender Pribadi
                        </div>

                        <div class="space-y-3">
                            <!-- Google Calendar Button -->
                            <a
                                href="{{ $gcalUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full h-11 px-4 rounded-xl bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs sm:text-sm no-underline flex items-center justify-center gap-2.5 transition-all shadow-xs"
                            >
                                <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                                </svg>
                                <span>Simpan di Google Calendar</span>
                            </a>

                            <!-- iCal / .ics Download Button -->
                            <a
                                href="{{ route('event.ics', $event->slug) }}"
                                class="w-full h-11 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm no-underline flex items-center justify-center gap-2.5 transition-all shadow-xs"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span>Unduh Berkas Kalender (.ICS)</span>
                            </a>
                        </div>

                        <div class="pt-3 border-t border-slate-200 space-y-2.5">
                            <div class="text-xs font-semibold text-slate-500">Bagikan Agenda Ini:</div>
                            <div class="flex items-center gap-2.5">
                                <!-- WhatsApp Share -->
                                @php
                                    $waText = urlencode("Ikuti acara budaya: *{$event->judul}* di Kampung Gedung Batin, Way Kanan pada " . ($event->tanggal_mulai ? $event->tanggal_mulai->translatedFormat('d F Y') : '') . ". Rincian selengkapnya: " . route('event.show', $event->slug));
                                @endphp
                                <a
                                    href="https://api.whatsapp.com/send?text={{ $waText }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex-1 h-10 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl no-underline flex items-center justify-center gap-1.5 transition-all shadow-xs"
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
                                    class="flex-1 h-10 px-3 bg-white border border-slate-300 text-slate-700 font-semibold text-xs rounded-xl hover:bg-slate-50 flex items-center justify-center gap-1.5 transition-all shadow-xs"
                                >
                                    <span x-show="!copied">🔗 Salin Tautan</span>
                                    <span x-show="copied" class="text-emerald-700 font-bold">✓ Tersalin!</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Waktu, Lokasi, Narasi (7 Kolom di Desktop) -->
                <div class="lg:col-span-7 space-y-8">
                    <!-- Ringkasan Waktu & Tempat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Tanggal Pelaksanaan -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 space-y-1">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Pelaksanaan</span>
                            <div class="text-base sm:text-lg font-bold text-slate-900">
                                📅 {{ $event->tanggal_mulai ? $event->tanggal_mulai->translatedFormat('l, d F Y') : '' }}
                            </div>
                            @if($event->is_multi_hari)
                                <div class="text-xs text-amber-700 font-semibold mt-1">
                                    Sampai {{ $event->tanggal_selesai ? $event->tanggal_selesai->translatedFormat('l, d F Y') : '' }}
                                </div>
                            @endif
                        </div>

                        <!-- Jam Operasional -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 space-y-1">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu Pelaksanaan</span>
                            <div class="text-base sm:text-lg font-bold text-slate-900">
                                ⏰ {{ substr($event->jam_mulai, 0, 5) }} - {{ substr($event->jam_selesai, 0, 5) }} WIB
                            </div>
                            <div class="text-xs text-slate-500 mt-1 font-mono">
                                Waktu Indonesia Barat (WIB)
                            </div>
                        </div>

                        <!-- Tempat & Lokasi -->
                        <div class="sm:col-span-2 bg-slate-50 rounded-2xl border border-slate-200 p-5 space-y-1">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lokasi Kegiatan</span>
                            <div class="text-base sm:text-lg font-bold text-slate-900">
                                📍 {{ $event->lokasi ?: 'Kawasan Adat Kampung Gedung Batin' }}
                            </div>
                            <div class="text-xs text-slate-500">
                                Kampung Gedung Batin, Kec. Umpu Semenguk, Kab. Way Kanan, Lampung
                            </div>
                        </div>
                    </div>

                    <!-- Narasi & Deskripsi Lengkap Acara -->
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight border-b border-slate-200 pb-3">
                            Tentang Acara Budaya Ini
                        </h2>
                        <div class="text-sm sm:text-base leading-relaxed text-slate-700 whitespace-pre-line font-normal">
                            {{ $event->deskripsi }}
                        </div>
                    </div>

                    <!-- Etika Berkunjung & Kearifan Lokal -->
                    <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-6 space-y-3">
                        <div class="flex items-center gap-2 font-bold text-sm text-amber-900">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>Etika dan Tata Tertib Wisatawan Adat</span>
                        </div>
                        <ul class="list-disc list-inside text-xs sm:text-sm text-slate-700 space-y-1.5 pl-1 leading-relaxed">
                            <li>Berpakaian sopan dan rapi saat menghadiri kegiatan upacara atau ritual adat.</li>
                            <li>Menghormati pemuka adat (Penyimbang Marga) dan masyarakat lokal setempat.</li>
                            <li>Meminta izin terlebih dahulu sebelum mengambil dokumentasi foto saat prosesi ritual sakral.</li>
                            <li>Menjaga kebersihan dan tidak membuang sampah sembarangan di kawasan cagar budaya.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekomendasi Kegiatan Budaya Lainnya -->
        @if($eventLain->count() > 0)
            <div class="space-y-6 pt-6 border-t border-slate-200">
                <div>
                    <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase">Eksplorasi Lanjutan</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-1">
                        Agenda Budaya Lainnya
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($eventLain as $evLain)
                        <div class="card-modern p-6 flex flex-col justify-between group">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $evLain->status_turunan === 'Berlangsung' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($evLain->status_turunan === 'Akan datang' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}
                                    ">
                                        {{ $evLain->status_turunan }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-500">{{ $evLain->kategori?->nama }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition-colors">
                                    <a href="{{ route('event.show', $evLain->slug) }}" class="no-underline text-inherit">
                                        {{ $evLain->judul }}
                                    </a>
                                </h3>
                                <div class="text-xs text-slate-500 font-medium">
                                    📅 {{ $evLain->tanggal_mulai ? $evLain->tanggal_mulai->translatedFormat('d M Y') : '' }}
                                    @if($evLain->is_multi_hari)
                                        s/d {{ $evLain->tanggal_selesai ? $evLain->tanggal_selesai->translatedFormat('d M Y') : '' }}
                                    @endif
                                    &bull; 📍 {{ $evLain->lokasi }}
                                </div>
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $evLain->deskripsi }}
                                </p>
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-100">
                                <a
                                    href="{{ route('event.show', $evLain->slug) }}"
                                    class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 no-underline flex items-center justify-between"
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
