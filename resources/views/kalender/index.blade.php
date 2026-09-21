<x-portal-layout>
    <x-slot:title>Kalender Event Budaya {{ $namaBulan }} {{ $tahun }} — SIGEBAT Desa Wisata</x-slot:title>
    <x-slot:description>Jadwal lengkap kegiatan adat pepadun, ritual budaya, dan festival seni tahunan di Desa Wisata Kampung Gedung Batin, Way Kanan.</x-slot:description>

    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 space-y-8"
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
        <!-- Header Kalender Budaya Modern -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-850 to-amber-950 text-white rounded-2xl p-8 md:p-10 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3 max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-400/30 text-amber-300 text-xs font-semibold backdrop-blur-sm">
                        Agenda Kebudayaan & Tradisi
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                        Kalender Event Budaya Digital
                    </h1>
                    <p class="text-slate-300 text-sm md:text-base leading-relaxed font-normal">
                        Jadwal resmi pagelaran seni tradisi, ritual adat Pepadun, upacara Begawi, dan perayaan kebudayaan tahunan Kampung Gedung Batin, Kabupaten Way Kanan.
                    </p>
                </div>

                <!-- Indikator Ringkas Event Bulan Ini -->
                <div class="bg-white/10 border border-white/15 rounded-2xl p-5 backdrop-blur-md flex-shrink-0 text-center min-w-[200px]">
                    <div class="text-4xl font-bold text-white tracking-tight">{{ $allMonthEvents->count() }}</div>
                    <div class="text-xs font-medium text-slate-300 mt-1">Kegiatan pada {{ $namaBulan }} {{ $tahun }}</div>
                </div>
            </div>
        </div>

        <!-- Bar Navigasi Bulan & Tahun (Papan Kontrol) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Tombol Navigasi Bulan -->
            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
                <a
                    href="{{ route('kalender.index', array_merge(request()->query(), ['bulan' => $prevBulan, 'tahun' => $prevTahun])) }}"
                    class="h-10 px-4 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs md:text-sm no-underline flex items-center gap-1.5 transition-all shadow-xs"
                    title="Bulan Sebelumnya"
                >
                    <span>&larr;</span>
                    <span class="hidden sm:inline">Bulan Lalu</span>
                </a>

                <div class="text-center px-4">
                    <span class="block font-bold text-xl md:text-2xl text-slate-900 tracking-tight leading-tight">
                        {{ $namaBulan }} {{ $tahun }}
                    </span>
                </div>

                <a
                    href="{{ route('kalender.index', array_merge(request()->query(), ['bulan' => $nextBulan, 'tahun' => $nextTahun])) }}"
                    class="h-10 px-4 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs md:text-sm no-underline flex items-center gap-1.5 transition-all shadow-xs"
                    title="Bulan Berikutnya"
                >
                    <span class="hidden sm:inline">Bulan Depan</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Tombol Hari Ini & Pengalih Tampilan -->
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                @php
                    $isBulanIni = ($bulan === (int) now('Asia/Jakarta')->format('n') && $tahun === (int) now('Asia/Jakarta')->format('Y'));
                @endphp
                @if(!$isBulanIni)
                    <a
                        href="{{ route('kalender.index') }}"
                        class="h-10 px-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs no-underline flex items-center transition-colors"
                    >
                        Bulan Ini
                    </a>
                @endif

                <!-- Toggle Mode: Kalender vs Daftar (Segmented Control) -->
                <div class="flex items-center rounded-xl bg-slate-100 p-1 border border-slate-200">
                    <button
                        type="button"
                        @click="viewMode = 'kalender'"
                        :class="viewMode === 'kalender' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all"
                    >
                        📅 Kalender
                    </button>
                    <button
                        type="button"
                        @click="viewMode = 'daftar'"
                        :class="viewMode === 'daftar' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all"
                    >
                        📋 Daftar ({{ $allMonthEvents->count() }})
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Kategori Event Chips -->
        @if($kategoriList->count() > 0)
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                <span class="font-semibold text-slate-400 flex-shrink-0">Kategori:</span>
                <a
                    href="{{ route('kalender.index', array_filter(['bulan' => $bulan, 'tahun' => $tahun])) }}"
                    class="px-3.5 py-1.5 rounded-full border text-xs font-semibold no-underline whitespace-nowrap transition-all {{ !$selectedKategori ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-emerald-400' }}"
                >
                    Semua Agenda
                </a>
                @foreach($kategoriList as $kat)
                    <a
                        href="{{ route('kalender.index', array_filter(['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => $kat->slug])) }}"
                        class="px-3.5 py-1.5 rounded-full border text-xs font-semibold no-underline whitespace-nowrap transition-all {{ $selectedKategori === $kat->slug ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:border-emerald-400' }}"
                    >
                        {{ $kat->nama }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- 1. Tampilan Grid Kalender Bulanan 7 Kolom (Senin - Minggu) -->
        <div x-show="viewMode === 'kalender'" class="space-y-4">
            <div class="card-modern p-0 overflow-hidden shadow-md">
                <!-- Header Hari (Senin s/d Minggu) -->
                <div class="grid grid-cols-7 text-center bg-slate-900 text-white font-bold text-xs md:text-sm uppercase tracking-wider">
                    <div class="py-3">Senin</div>
                    <div class="py-3">Selasa</div>
                    <div class="py-3">Rabu</div>
                    <div class="py-3">Kamis</div>
                    <div class="py-3">Jumat</div>
                    <div class="py-3 text-amber-400">Sabtu</div>
                    <div class="py-3 text-amber-400">Minggu</div>
                </div>

                <!-- Grid Hari-Hari dalam Bulan -->
                <div class="grid grid-cols-7 gap-[1px] bg-slate-200">
                    @foreach($calendarDays as $cell)
                        @php
                            $hasEvents = count($cell['events']) > 0;
                            $cellDate = $cell['date'];
                        @endphp
                        <div
                            @click="selectDay('{{ $cellDate }}')"
                            class="relative min-h-[85px] sm:min-h-[110px] md:min-h-[125px] p-2 md:p-2.5 transition-colors cursor-pointer flex flex-col justify-between
                                {{ $cell['is_current_month'] ? 'bg-white hover:bg-slate-50' : 'bg-slate-100/60 text-slate-400' }}
                                {{ $cell['is_today'] ? 'ring-2 ring-inset ring-emerald-500 bg-emerald-50/15' : '' }}
                            "
                            :class="selectedDate === '{{ $cellDate }}' ? '!bg-emerald-50/70 ring-2 ring-inset ring-emerald-600' : ''"
                        >
                            <!-- Header Tanggal -->
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs md:text-sm {{ $cell['is_today'] ? 'text-emerald-700 font-extrabold' : ($cell['is_current_month'] ? 'text-slate-800' : 'text-slate-400') }}">
                                    {{ $cell['day'] }}
                                </span>

                                @if($cell['is_today'])
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 bg-emerald-600 text-white rounded-md shadow-xs">
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
                                        class="block no-underline text-[10px] md:text-[11px] font-semibold px-2 py-0.5 rounded-md truncate transition-transform hover:scale-[1.02]
                                            {{ $ev->status_turunan === 'Berlangsung' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($ev->status_turunan === 'Akan datang' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}
                                        "
                                        title="{{ $ev->judul }} ({{ substr($ev->jam_mulai, 0, 5) }} WIB)"
                                    >
                                        <span class="hidden sm:inline">{{ substr($ev->jam_mulai, 0, 5) }}</span>
                                        {{ $ev->judul }}
                                    </a>
                                @endforeach

                                @if(count($cell['events']) > 2)
                                    <div class="text-[9px] md:text-[10px] font-semibold text-emerald-700 px-1">
                                        +{{ count($cell['events']) - 2 }} acara lain
                                    </div>
                                @endif
                            </div>

                            <!-- Indikator Dot Mobile jika ada event -->
                            @if($hasEvents)
                                <div class="sm:hidden flex items-center justify-end mt-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Petunjuk Penggunaan Modern -->
            <div class="flex flex-wrap items-center justify-between text-xs text-slate-500 px-1 gap-2">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 bg-amber-100 border border-amber-300 rounded-full"></span>
                        <span>Akan datang</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 bg-emerald-100 border border-emerald-400 rounded-full"></span>
                        <span>Sedang Berlangsung</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 bg-slate-200 border border-slate-300 rounded-full"></span>
                        <span>Selesai</span>
                    </span>
                </div>
                <div>
                    <em>💡 Klik salah satu tanggal di kalender untuk menyaring agenda pada hari tersebut.</em>
                </div>
            </div>
        </div>

        <!-- 2. Panel Rincian Agenda Acara -->
        <div class="space-y-6 pt-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
                <div>
                    <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase">Daftar Agenda Kegiatan</span>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight mt-0.5">
                        <template x-if="selectedDate">
                            <span>Kegiatan Budaya pada <span class="text-emerald-700" x-text="selectedDate"></span></span>
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
                        class="px-3.5 py-2 bg-white border border-slate-300 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-50 transition-colors shadow-xs"
                    >
                        ✕ Tampilkan Semua Bulan Ini
                    </button>
                </div>
            </div>

            <!-- Grid Kartu Event -->
            @if($allMonthEvents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($allMonthEvents as $ev)
                        @php
                            $evStart = $ev->tanggal_mulai ? $ev->tanggal_mulai->format('Y-m-d') : '';
                            $evEnd = $ev->tanggal_selesai ? $ev->tanggal_selesai->format('Y-m-d') : '';
                        @endphp
                        <div
                            class="card-modern overflow-hidden flex flex-col justify-between group"
                            x-show="!selectedDate || (selectedDate >= '{{ $evStart }}' && selectedDate <= '{{ $evEnd }}')"
                        >
                            <div>
                                <!-- Poster / Visual Agenda -->
                                <div class="relative h-48 bg-slate-100 overflow-hidden">
                                    @if($ev->poster)
                                        <img src="{{ $ev->poster_url }}" alt="{{ $ev->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-400 font-medium text-xs p-4 text-center">
                                            <svg class="w-8 h-8 mb-1.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>Poster belum diunggah</span>
                                        </div>
                                    @endif

                                    <!-- Status Turunan Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-md backdrop-blur-md shadow-xs
                                            {{ $ev->status_turunan === 'Berlangsung' ? 'bg-emerald-600 text-white' : ($ev->status_turunan === 'Akan datang' ? 'bg-amber-600 text-white' : 'bg-slate-700 text-white') }}
                                        ">
                                            {{ $ev->status_turunan }}
                                        </span>
                                    </div>

                                    <!-- Kategori Badge -->
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2.5 py-1 bg-slate-900/80 backdrop-blur-md text-white text-xs font-medium rounded-md shadow-xs">
                                            {{ $ev->kategori?->nama ?? 'Budaya' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Konten Rincian -->
                                <div class="p-6 space-y-3">
                                    <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug group-hover:text-emerald-700 transition-colors">
                                        <a href="{{ route('event.show', $ev->slug) }}" class="no-underline text-inherit">
                                            {{ $ev->judul }}
                                        </a>
                                    </h3>

                                    <div class="space-y-2 text-xs text-slate-600 pt-1">
                                        <div class="flex items-center gap-2 font-medium text-slate-900">
                                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>
                                                {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d F Y') : '' }}
                                                @if($ev->is_multi_hari)
                                                    s/d {{ $ev->tanggal_selesai ? $ev->tanggal_selesai->translatedFormat('d F Y') : '' }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500 font-mono">
                                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ substr($ev->jam_mulai, 0, 5) }} - {{ substr($ev->jam_selesai, 0, 5) }} WIB</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500">
                                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            <span class="line-clamp-1">{{ $ev->lokasi ?: 'Kampung Gedung Batin, Way Kanan' }}</span>
                                        </div>
                                    </div>

                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed border-t border-slate-100 pt-3">
                                        {{ $ev->deskripsi }}
                                    </p>
                                </div>
                            </div>

                            <!-- Aksi Buka Detail & Unduh .ics -->
                            <div class="p-6 pt-0 flex items-center gap-2">
                                <a
                                    href="{{ route('event.show', $ev->slug) }}"
                                    class="flex-1 h-10 px-4 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-700 font-semibold text-xs no-underline flex items-center justify-center gap-1.5 transition-all"
                                >
                                    <span>Lihat Rincian</span>
                                    <span>&rarr;</span>
                                </a>
                                <a
                                    href="{{ route('event.ics', $ev->slug) }}"
                                    class="h-10 px-3.5 rounded-xl border border-slate-200 hover:border-slate-300 bg-white text-slate-700 font-medium text-xs no-underline flex items-center justify-center gap-1.5 transition-all shadow-xs"
                                    title="Unduh Pengingat Kalender (.ics)"
                                >
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span class="hidden sm:inline">.ICS</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Pesan Informatif Bulan Kosong Modern -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-10 md:p-14 text-center space-y-4 max-w-xl mx-auto">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center mx-auto text-xl font-bold">
                        📅
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                        Belum Ada Agenda pada {{ $namaBulan }} {{ $tahun }}
                    </h3>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-md mx-auto">
                        Saat ini belum ada agenda kegiatan budaya atau festival adat yang dijadwalkan pada bulan ini. Anda dapat memeriksa jadwal bulan lainnya.
                    </p>
                    <div class="pt-2">
                        <a
                            href="{{ route('kalender.index') }}"
                            class="inline-flex px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs no-underline shadow-xs transition-colors"
                        >
                            Kembali ke Kalender Bulan Ini
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-portal-layout>
