<x-portal-layout>
    <x-slot:title>Kalender Event Budaya {{ $namaBulan }} {{ $tahun }} — SIGEBAT Desa Wisata</x-slot:title>
    <x-slot:description>Jadwal lengkap kegiatan adat pepadun, ritual budaya, dan festival seni tahunan di Desa Wisata Kampung Gedung Batin, Way Kanan.</x-slot:description>

    <div
        class="max-w-[1240px] mx-auto px-4 sm:px-6 py-8 md:py-12 space-y-8"
        x-data="{
            selectedDate: '{{ $selectedDate ?? '' }}',
            viewMode: 'kalender',
            selectDay(date) {
                if (this.selectedDate === date) {
                    this.selectedDate = '';
                } else {
                    this.selectedDate = date;
                }
            },
            clearDate() {
                this.selectedDate = '';
            }
        }"
    >
        <!-- Papan Kuning Pengantar (DESIGN.md Rambu Kuning / Peringatan & Agenda) -->
        <div class="bg-kuning text-aspal border-2 border-aspal rounded-papan p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <span class="inline-block px-2.5 py-1 bg-aspal text-putih text-xs font-bold rounded-tag">
                        Agenda Budaya Desa
                    </span>
                    <h1 class="text-3xl md:text-4xl font-bold font-papan text-aspal tracking-tight leading-tight">
                        Kalender Event Budaya Digital
                    </h1>
                    <p class="text-sm md:text-base text-aspal/80 leading-relaxed font-medium">
                        Jadwal resmi pagelaran seni tradisi, ritual adat Pepadun, upacara Begawi, dan perayaan kebudayaan tahunan Kampung Gedung Batin, Kabupaten Way Kanan.
                    </p>
                </div>

                <!-- Indikator Ringkas Event Bulan Ini -->
                <div class="bg-putih border-2 border-aspal rounded-papan p-4 flex-shrink-0 text-center min-w-[180px]">
                    <div class="text-3xl font-bold font-papan text-aspal">{{ $allMonthEvents->count() }}</div>
                    <div class="text-xs font-semibold text-abu mt-0.5">Kegiatan pada {{ $namaBulan }} {{ $tahun }}</div>
                </div>
            </div>
        </div>

        <!-- Bar Navigasi Bulan & Tahun (Papan Kontrol) -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Tombol Navigasi Bulan -->
            <div class="flex items-center gap-2 w-full md:w-auto justify-between md:justify-start">
                <a
                    href="{{ route('kalender.index', array_merge(request()->query(), ['bulan' => $prevBulan, 'tahun' => $prevTahun])) }}"
                    class="h-[44px] px-3.5 bg-putih border-2 border-aspal text-aspal font-bold text-xs md:text-sm rounded-kontrol hover:bg-beton no-underline flex items-center gap-1"
                    title="Bulan Sebelumnya"
                >
                    <span>&larr;</span>
                    <span class="hidden sm:inline">Bulan Lalu</span>
                </a>

                <div class="text-center px-4">
                    <span class="block font-papan font-bold text-xl md:text-2xl text-aspal leading-tight">
                        {{ $namaBulan }} {{ $tahun }}
                    </span>
                </div>

                <a
                    href="{{ route('kalender.index', array_merge(request()->query(), ['bulan' => $nextBulan, 'tahun' => $nextTahun])) }}"
                    class="h-[44px] px-3.5 bg-putih border-2 border-aspal text-aspal font-bold text-xs md:text-sm rounded-kontrol hover:bg-beton no-underline flex items-center gap-1"
                    title="Bulan Berikutnya"
                >
                    <span class="hidden sm:inline">Bulan Depan</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Tombol Hari Ini & Pengalih Tampilan -->
            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                @php
                    $isBulanIni = ($bulan === (int) now('Asia/Jakarta')->format('n') && $tahun === (int) now('Asia/Jakarta')->format('Y'));
                @endphp
                @if(!$isBulanIni)
                    <a
                        href="{{ route('kalender.index') }}"
                        class="h-[44px] px-3 bg-beton border-2 border-aspal text-aspal font-bold text-xs rounded-kontrol hover:bg-putih no-underline flex items-center"
                    >
                        Ke Bulan Ini
                    </a>
                @endif

                <!-- Toggle Mode: Kalender vs Daftar -->
                <div class="flex items-center border-2 border-aspal rounded-kontrol overflow-hidden bg-beton p-0.5">
                    <button
                        type="button"
                        @click="viewMode = 'kalender'"
                        :class="viewMode === 'kalender' ? 'bg-aspal text-putih' : 'text-aspal hover:bg-putih'"
                        class="px-3 py-1.5 text-xs font-bold rounded-[3px] transition-colors"
                    >
                        📅 Kalender
                    </button>
                    <button
                        type="button"
                        @click="viewMode = 'daftar'"
                        :class="viewMode === 'daftar' ? 'bg-aspal text-putih' : 'text-aspal hover:bg-putih'"
                        class="px-3 py-1.5 text-xs font-bold rounded-[3px] transition-colors"
                    >
                        📋 Daftar ({{ $allMonthEvents->count() }})
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Kategori Event -->
        @if($kategoriList->count() > 0)
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                <span class="font-bold text-abu flex-shrink-0">Kategori:</span>
                <a
                    href="{{ route('kalender.index', array_filter(['bulan' => $bulan, 'tahun' => $tahun])) }}"
                    class="px-3 py-1.5 rounded-tag border-2 font-bold no-underline whitespace-nowrap {{ !$selectedKategori ? 'bg-aspal text-putih border-aspal' : 'bg-putih text-aspal border-aspal hover:bg-beton' }}"
                >
                    Semua Agenda
                </a>
                @foreach($kategoriList as $kat)
                    <a
                        href="{{ route('kalender.index', array_filter(['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => $kat->slug])) }}"
                        class="px-3 py-1.5 rounded-tag border-2 font-bold no-underline whitespace-nowrap {{ $selectedKategori === $kat->slug ? 'bg-cokelat text-putih border-aspal' : 'bg-putih text-aspal border-aspal hover:bg-beton' }}"
                    >
                        {{ $kat->nama }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- 1. Tampilan Grid Kalender Bulanan 7 Kolom (Senin - Minggu) -->
        <div x-show="viewMode === 'kalender'" class="space-y-4">
            <div class="bg-aspal border-2 border-aspal rounded-papan overflow-hidden">
                <!-- Header Hari (Senin s/d Minggu) -->
                <div class="grid grid-cols-7 text-center bg-aspal text-putih font-papan font-bold text-xs md:text-sm uppercase tracking-wider border-b-2 border-aspal">
                    <div class="py-2.5">Senin</div>
                    <div class="py-2.5">Selasa</div>
                    <div class="py-2.5">Rabu</div>
                    <div class="py-2.5">Kamis</div>
                    <div class="py-2.5">Jumat</div>
                    <div class="py-2.5 text-kuning">Sabtu</div>
                    <div class="py-2.5 text-kuning">Minggu</div>
                </div>

                <!-- Grid Hari-Hari dalam Bulan -->
                <div class="grid grid-cols-7 gap-[1px] bg-aspal/30">
                    @foreach($calendarDays as $cell)
                        @php
                            $hasEvents = count($cell['events']) > 0;
                            $cellDate = $cell['date'];
                        @endphp
                        <div
                            @click="selectDay('{{ $cellDate }}')"
                            class="relative min-h-[80px] sm:min-h-[105px] md:min-h-[120px] p-1.5 md:p-2 transition-colors cursor-pointer flex flex-col justify-between
                                {{ $cell['is_current_month'] ? 'bg-putih hover:bg-beton/40' : 'bg-beton/80 text-abu/50' }}
                                {{ $cell['is_today'] ? 'ring-2 ring-inset ring-kuning-gelap bg-kuning/10' : '' }}
                            "
                            :class="selectedDate === '{{ $cellDate }}' ? '!bg-kuning/30 ring-2 ring-inset ring-aspal' : ''"
                        >
                            <!-- Header Tanggal -->
                            <div class="flex items-center justify-between">
                                <span class="font-papan font-bold text-xs md:text-sm {{ $cell['is_today'] ? 'text-aspal underline decoration-kuning-gelap decoration-2' : '' }} {{ $cell['is_current_month'] ? 'text-aspal' : 'text-abu/60' }}">
                                    {{ $cell['day'] }}
                                </span>

                                @if($cell['is_today'])
                                    <span class="text-[9px] font-bold px-1.5 py-0.2 bg-kuning text-aspal rounded-tag border border-aspal">
                                        Hari ini
                                    </span>
                                @endif
                            </div>

                            <!-- Indikator Event dalam Sel Tanggal -->
                            <div class="space-y-1 mt-1 overflow-hidden">
                                @foreach($cell['events']->take(2) as $ev)
                                    <a
                                        href="{{ route('event.show', $ev->slug) }}"
                                        @click.stop
                                        class="block no-underline text-[10px] md:text-[11px] font-bold px-1.5 py-0.5 rounded-[3px] border border-aspal truncate
                                            {{ $ev->status_turunan === 'Berlangsung' ? 'bg-hijau text-putih' : ($ev->status_turunan === 'Akan datang' ? 'bg-kuning text-aspal' : 'bg-beton text-abu') }}
                                        "
                                        title="{{ $ev->judul }} ({{ substr($ev->jam_mulai, 0, 5) }} WIB)"
                                    >
                                        <span class="hidden sm:inline">{{ substr($ev->jam_mulai, 0, 5) }}</span>
                                        {{ $ev->judul }}
                                    </a>
                                @endforeach

                                @if(count($cell['events']) > 2)
                                    <div class="text-[9px] md:text-[10px] font-bold text-cokelat px-1">
                                        +{{ count($cell['events']) - 2 }} acara lain
                                    </div>
                                @endif
                            </div>

                            <!-- Indikator Dot Mobile jika ada event -->
                            @if($hasEvents)
                                <div class="sm:hidden flex items-center justify-end mt-1">
                                    <span class="w-2 h-2 rounded-full bg-cokelat"></span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Petunjuk Penggunaan -->
            <div class="flex flex-wrap items-center justify-between text-xs text-abu px-1 gap-2">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 bg-kuning border border-aspal rounded-[2px]"></span>
                        <span>Akan datang</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 bg-hijau border border-aspal rounded-[2px]"></span>
                        <span>Sedang Berlangsung</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 bg-beton border border-abu rounded-[2px]"></span>
                        <span>Selesai</span>
                    </span>
                </div>
                <div>
                    <em>💡 Klik salah satu tanggal di kalender untuk menyaring agenda pada hari tersebut.</em>
                </div>
            </div>
        </div>

        <!-- 2. Panel Rincian Agenda Acara (Tampil di kedua mode, disaring bila tanggal dipilih) -->
        <div class="space-y-6 pt-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-aspal pb-3">
                <div>
                    <span class="text-xs font-bold text-kuning-gelap tracking-wider uppercase">Daftar Agenda Kegiatan</span>
                    <h2 class="text-2xl md:text-3xl font-bold font-papan text-aspal mt-0.5">
                        <template x-if="selectedDate">
                            <span>Kegiatan Budaya pada <span class="underline decoration-kuning" x-text="selectedDate"></span></span>
                        </template>
                        <template x-if="!selectedDate">
                            <span>Seluruh Agenda {{ $namaBulan }} {{ $tahun }}</span>
                        </template>
                    </h2>
                </div>

                <div x-show="selectedDate" class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="clearDate()"
                        class="px-3 py-1.5 bg-putih border-2 border-aspal text-aspal text-xs font-bold rounded-kontrol hover:bg-beton"
                    >
                        ✕ Tampilkan Semua Bulan Ini
                    </button>
                </div>
            </div>

            <!-- Grid Kartu Event -->
            @if($allMonthEvents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($allMonthEvents as $ev)
                        @php
                            $evStart = $ev->tanggal_mulai ? $ev->tanggal_mulai->format('Y-m-d') : '';
                            $evEnd = $ev->tanggal_selesai ? $ev->tanggal_selesai->format('Y-m-d') : '';
                        @endphp
                        <div
                            class="bg-putih border-2 border-aspal rounded-papan overflow-hidden flex flex-col justify-between hover:translate-y-[-2px] transition-transform"
                            x-show="!selectedDate || (selectedDate >= '{{ $evStart }}' && selectedDate <= '{{ $evEnd }}')"
                        >
                            <div>
                                <!-- Poster / Visual Agenda -->
                                <div class="relative h-44 bg-beton overflow-hidden border-b-2 border-aspal">
                                    @if($ev->poster)
                                        <img src="{{ $ev->poster_url }}" alt="{{ $ev->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-beton text-abu font-semibold text-xs p-4 text-center">
                                            <span class="text-2xl mb-1">🎭</span>
                                            <span>Poster belum diunggah</span>
                                        </div>
                                    @endif

                                    <!-- Status Turunan Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-tag {{ $ev->status_turunan_badge_class }}">
                                            {{ $ev->status_turunan }}
                                        </span>
                                    </div>

                                    <!-- Kategori Badge -->
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2.5 py-1 bg-aspal text-putih text-xs font-bold rounded-tag">
                                            {{ $ev->kategori?->nama ?? 'Budaya' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Konten Rincian -->
                                <div class="p-5 space-y-3">
                                    <h3 class="text-xl font-bold font-papan text-aspal leading-snug">
                                        <a href="{{ route('event.show', $ev->slug) }}" class="no-underline text-aspal hover:text-cokelat">
                                            {{ $ev->judul }}
                                        </a>
                                    </h3>

                                    <div class="space-y-1.5 text-xs text-aspal">
                                        <div class="flex items-center gap-2 font-semibold">
                                            <span>📅</span>
                                            <span>
                                                {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d F Y') : '' }}
                                                @if($ev->is_multi_hari)
                                                    s/d {{ $ev->tanggal_selesai ? $ev->tanggal_selesai->translatedFormat('d F Y') : '' }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-abu font-mono">
                                            <span>⏰</span>
                                            <span>{{ substr($ev->jam_mulai, 0, 5) }} - {{ substr($ev->jam_selesai, 0, 5) }} WIB</span>
                                        </div>
                                        <div class="flex items-start gap-2 text-abu">
                                            <span>📍</span>
                                            <span class="line-clamp-1">{{ $ev->lokasi ?: 'Kampung Gedung Batin, Way Kanan' }}</span>
                                        </div>
                                    </div>

                                    <p class="text-xs text-abu line-clamp-3 leading-relaxed border-t border-beton pt-2">
                                        {{ $ev->deskripsi }}
                                    </p>
                                </div>
                            </div>

                            <!-- Aksi Buka Detail & Unduh .ics -->
                            <div class="p-5 pt-0 flex items-center gap-2">
                                <a
                                    href="{{ route('event.show', $ev->slug) }}"
                                    class="flex-1 h-[40px] px-3 bg-putih border-2 border-aspal text-aspal font-bold text-xs rounded-kontrol hover:bg-beton no-underline flex items-center justify-center gap-1"
                                >
                                    <span>Lihat Detail</span>
                                    <span>&rarr;</span>
                                </a>
                                <a
                                    href="{{ route('event.ics', $ev->slug) }}"
                                    class="h-[40px] px-3 bg-beton border-2 border-aspal text-aspal font-bold text-xs rounded-kontrol hover:bg-putih no-underline flex items-center justify-center gap-1"
                                    title="Unduh Pengingat Kalender (.ics)"
                                >
                                    <span>📅</span>
                                    <span class="hidden sm:inline">.ICS</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Pesan Informatif Bulan Kosong (PRD 6.2) -->
                <div class="bg-putih border-2 border-aspal rounded-papan p-8 md:p-12 text-center space-y-4 max-w-xl mx-auto">
                    <div class="text-4xl">📅</div>
                    <h3 class="text-2xl font-bold font-papan text-aspal">
                        Belum Ada Event pada Bulan {{ $namaBulan }} {{ $tahun }}
                    </h3>
                    <p class="text-sm text-abu leading-relaxed">
                        Saat ini belum ada agenda kegiatan budaya atau festival adat yang dijadwalkan pada bulan ini. Anda dapat memeriksa jadwal bulan lainnya atau kembali ke bulan berjalan.
                    </p>
                    <div class="pt-2">
                        <a
                            href="{{ route('kalender.index') }}"
                            class="inline-block px-5 py-2.5 bg-kuning border-2 border-aspal text-aspal font-bold text-sm rounded-kontrol hover:bg-kuning-gelap no-underline"
                        >
                            Kembali ke Kalender Bulan Ini
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-portal-layout>
