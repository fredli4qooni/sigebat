<x-portal-layout>
    <x-slot:title>Desa Wisata Cagar Budaya Kampung Gedung Batin — SIGEBAT</x-slot:title>
    <x-slot:description>Sistem Informasi Manajemen Desa Wisata Kampung Gedung Batin, Way Kanan berbasis LBS dan Kalender Event Budaya Digital.</x-slot:description>

    <!-- 1. Hero Section Modern & Berwibawa (Solid Deep Slate) -->
    <section class="relative bg-slate-900 text-white overflow-hidden py-16 md:py-24 lg:py-28 border-b border-slate-800">

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl space-y-6">
                <!-- Status Cagar Budaya Badge -->
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-400/30 text-emerald-300 text-xs font-semibold backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Cagar Budaya Resmi &bull; Way Kanan, Lampung</span>
                </div>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-[1.15]">
                    Warisan Rumah Panggung & Pesona Adat Pepadun
                </h1>

                <!-- Deskripsi -->
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed max-w-2xl font-normal">
                    Jelajahi keagungan arsitektur kayu ulin berusia ratusan tahun di Kampung Gedung Batin, ikuti kalender ritual budaya tahunan, dan temukan rute terdekat langsung dengan teknologi <em>Location Based Services</em>.
                </p>

                <!-- Tombol CTA -->
                <div class="pt-3 flex flex-wrap items-center gap-3.5">
                    <a
                        href="/wisata"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md hover:shadow-emerald-600/30 hover:-translate-y-0.5 transition-all no-underline"
                    >
                        <span>Eksplorasi Destinasi</span>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>

                    <a
                        href="/peta"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-sm backdrop-blur-md hover:-translate-y-0.5 transition-all no-underline"
                    >
                        <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <span>Peta Interaktif & LBS</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Bar Statistik Melayang (Floating Metric Cards) -->
    <section class="relative -mt-10 sm:-mt-12 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xl p-6 sm:p-7 lg:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y divide-slate-100 md:divide-y-0 md:divide-x md:divide-slate-200">
                    <!-- Metrik 1 -->
                    <div class="flex items-center gap-4.5 pb-5 md:pb-0 md:pr-6 lg:pr-8">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50/90 border border-emerald-100/90 flex items-center justify-center flex-shrink-0 shadow-xs p-2.5">
                            <img src="{{ asset('images/icons/point-objects.png') }}" 
                                 onerror="this.src='https://img.icons8.com/parakeet/48/point-objects.png'" 
                                 alt="Objek Cagar Budaya" 
                                 class="w-9 h-9 object-contain">
                        </div>
                        <div class="min-w-0">
                            <div class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight">{{ $countWisata }} Objek Cagar Budaya</div>
                            <div class="text-xs sm:text-[13px] text-slate-500 font-medium mt-1 leading-snug">Rumah adat panggung, situs bersejarah & makam leluhur</div>
                        </div>
                    </div>

                    <!-- Metrik 2 -->
                    <div class="flex items-center gap-4.5 py-5 md:py-0 md:px-6 lg:px-8">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50/90 border border-amber-100/90 flex items-center justify-center flex-shrink-0 shadow-xs p-2.5">
                            <img src="{{ asset('images/icons/planner.png') }}" 
                                 onerror="this.src='https://img.icons8.com/parakeet/48/planner.png'" 
                                 alt="Event & Ritual Budaya" 
                                 class="w-9 h-9 object-contain">
                        </div>
                        <div class="min-w-0">
                            <div class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight">{{ $countEvent }} Event & Ritual Budaya</div>
                            <div class="text-xs sm:text-[13px] text-slate-500 font-medium mt-1 leading-snug">Pagelaran tari cangget, upacara begawi & festival</div>
                        </div>
                    </div>

                    <!-- Metrik 3 -->
                    <div class="flex items-center gap-4.5 pt-5 md:pt-0 md:pl-6 lg:pl-8">
                        <div class="w-14 h-14 rounded-2xl bg-sky-50/90 border border-sky-100/90 flex items-center justify-center flex-shrink-0 shadow-xs p-2.5">
                            <img src="{{ asset('images/icons/toilet.png') }}" 
                                 onerror="this.src='https://img.icons8.com/parakeet/48/toilet.png'" 
                                 alt="Sarana Pendukung" 
                                 class="w-9 h-9 object-contain">
                        </div>
                        <div class="min-w-0">
                            <div class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight">{{ $countFasilitas }} Sarana Pendukung</div>
                            <div class="text-xs sm:text-[13px] text-slate-500 font-medium mt-1 leading-snug">Musala, toilet umum, pos pemandu & area parkir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Destinasi Wisata Unggulan -->
    <section class="py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Header Seksi -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200 pb-5">
                <div>
                    <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase">Destinasi Pilihan</span>
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight mt-1">Cagar Budaya & Objek Wisata</h2>
                </div>
                <a href="/wisata" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition-colors no-underline">
                    <span>Lihat seluruh destinasi</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Grid Kartu Wisata -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @forelse($wisataUnggulan as $w)
                    <div class="card-modern overflow-hidden group flex flex-col justify-between">
                        <div>
                            <!-- Foto Objek Wisata -->
                            <div class="relative h-52 bg-slate-100 overflow-hidden">
                                @if($w->foto_utama)
                                    <img
                                        src="{{ $w->foto_url }}"
                                        alt="{{ $w->nama }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 text-sm font-medium">
                                        Foto belum tersedia
                                    </div>
                                @endif

                                <!-- Kategori Tag -->
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 bg-slate-900/80 backdrop-blur-md text-white text-xs font-medium rounded-md shadow-xs">
                                        {{ $w->kategori?->nama ?? 'Wisata' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Detail Ringkas -->
                            <div class="p-6 space-y-3">
                                <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug group-hover:text-emerald-700 transition-colors">
                                    <a href="{{ route('wisata.show', $w->slug) }}" class="no-underline text-inherit">
                                        {{ $w->nama }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $w->deskripsi }}
                                </p>

                                <div class="pt-3 flex items-center justify-between text-xs text-slate-600 border-t border-slate-100">
                                    <span class="font-semibold text-emerald-700 inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        <span>{{ $w->harga_tiket ?: 'Gratis' }}</span>
                                    </span>
                                    <span class="text-slate-400 font-medium inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $w->jam_operasional ?: '08.00 - 17.00 WIB' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Buka Rincian -->
                        <div class="p-6 pt-0">
                            <a
                                href="{{ route('wisata.show', $w->slug) }}"
                                class="w-full h-10 px-4 rounded-lg bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-700 font-semibold text-xs no-underline flex items-center justify-center gap-1.5 transition-all"
                            >
                                <span>Informasi Selengkapnya</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-slate-400">
                        Belum ada destinasi wisata yang ditampilkan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 4. Banner Edukasi Fitur LBS (Location Based Services) -->
    <section class="py-12 md:py-16 bg-slate-900 border border-slate-800 text-white rounded-3xl mx-4 sm:mx-6 lg:mx-8 shadow-sm overflow-hidden my-6">
        <div class="max-w-6xl mx-auto px-6 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-4 max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-semibold backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5 fill-current text-emerald-400" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Navigasi Cerdas Wisatawan</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-white leading-tight">
                    Temukan Destinasi Wisata Terdekat dari Titik Anda
                </h2>
                <p class="text-sm md:text-base text-slate-300 leading-relaxed font-normal">
                    Manfaatkan teknologi <em>Location Based Services</em> berbasis Geolocation API dan formula Haversine untuk mendeteksi objek wisata terdekat secara instan, aman, dan tanpa biaya.
                </p>
                <div class="text-xs text-slate-400 flex items-center gap-2 pt-1">
                    <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Privasi terjaga: koordinat GPS Anda hanya diproses di browser tanpa disimpan di server.</span>
                </div>
            </div>

            <div class="flex-shrink-0">
                <a
                    href="/peta"
                    class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm shadow-lg hover:shadow-emerald-500/30 transition-all no-underline"
                >
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l3.707 3.707A1 1 0 0019 17.414V7a1 1 0 00-.293-.707z" clip-rule="evenodd"/>
                    </svg>
                    <span>Buka Peta & Deteksi Lokasi</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 5. Agenda Event Budaya Terdekat -->
    <section class="py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Header Seksi -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200 pb-5">
                <div>
                    <span class="text-xs font-bold text-amber-700 tracking-wider uppercase">Kalender Budaya</span>
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight mt-1">Kegiatan Adat & Festival Mendatang</h2>
                </div>
                <a href="/kalender" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition-colors no-underline">
                    <span>Buka kalender acara lengkap</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Grid Kartu Event -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                @forelse($eventMendatang as $ev)
                    <div class="card-modern p-6 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $ev->status_turunan === 'Berlangsung' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($ev->status_turunan === 'Akan datang' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}
                                ">
                                    {{ $ev->status_turunan }}
                                </span>
                                <span class="text-xs font-medium text-slate-500">{{ $ev->kategori?->nama }}</span>
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug">
                                <a href="{{ route('event.show', $ev->slug) }}" class="no-underline text-inherit hover:text-emerald-700 transition-colors">
                                    {{ $ev->judul }}
                                </a>
                            </h3>

                            <div class="text-xs text-slate-600 space-y-2 pt-1 border-t border-slate-100">
                                <div class="flex items-center gap-2 font-medium text-slate-900">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>
                                        {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d M Y') : '' }}
                                        @if($ev->is_multi_hari)
                                            s/d {{ $ev->tanggal_selesai ? $ev->tanggal_selesai->translatedFormat('d M Y') : '' }}
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
                                    <span class="line-clamp-1">{{ $ev->lokasi }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                                {{ $ev->deskripsi }}
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100">
                            <a href="{{ route('event.show', $ev->slug) }}" class="inline-flex items-center justify-between w-full text-xs font-semibold text-emerald-700 hover:text-emerald-800 no-underline">
                                <span>Detail jadwal acara</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12 text-center text-slate-400">
                        Belum ada kegiatan budaya yang dijadwalkan dalam waktu dekat.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-portal-layout>
