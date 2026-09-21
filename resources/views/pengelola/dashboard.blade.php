<x-app-layout>
    <x-slot:title>Dashboard Pengelola</x-slot:title>
    <x-slot:header>Ruang Kerja Pengelola Wisata</x-slot:header>

    <div class="space-y-8">
        <!-- Banner Sambutan Eksekutif Solid Slate -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-3xl">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">Selamat Datang, {{ auth()->user()->name }}</h2>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed font-normal">
                    Ruang pengelolaan data objek wisata adat, sarana fasilitas desa, dan kalender kegiatan budaya Kampung Gedung Batin.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Status Akun: Aktif</span>
                </span>
            </div>
        </div>

        <!-- Aksi Cepat Pengelola -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold tracking-tight text-slate-900">Tindakan Cepat</h3>
                    <p class="text-xs text-slate-500">Pintasan cepat untuk menambahkan konten wisata baru</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('pengelola.wisata.create') }}"
                    class="h-11 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm no-underline inline-flex items-center gap-2 shadow-xs transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Objek Wisata</span>
                </a>

                <a
                    href="{{ route('pengelola.fasilitas.create') }}"
                    class="h-11 px-5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs sm:text-sm no-underline inline-flex items-center gap-2 shadow-xs transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Fasilitas Desa</span>
                </a>

                <a
                    href="{{ route('pengelola.event.create') }}"
                    class="h-11 px-5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs sm:text-sm no-underline inline-flex items-center gap-2 shadow-xs transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Jadwalkan Event Budaya</span>
                </a>
            </div>
        </div>

        <!-- Ringkasan Statistik Konten -->
        <div>
            <h3 class="text-lg font-bold tracking-tight text-slate-900 mb-4">Ringkasan konten desa</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Objek Wisata -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Objek Wisata</span>
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 256 256">
                                    <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $totalWisata }}</div>
                        <p class="text-xs text-slate-400 mt-1">{{ $totalWisataAktif }} wisata berstatus aktif tayang</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('pengelola.wisata.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 no-underline flex items-center justify-between">
                            <span>Kelola daftar wisata</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Fasilitas Desa -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Fasilitas Desa</span>
                            <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $totalFasilitas }}</div>
                        <p class="text-xs text-slate-400 mt-1">{{ $totalFasilitasUmum }} fasilitas umum kampung</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('pengelola.fasilitas.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700 no-underline flex items-center justify-between">
                            <span>Kelola daftar fasilitas</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Event Budaya -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Event Budaya</span>
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ $totalEvent }}</div>
                        <p class="text-xs text-slate-400 mt-1">{{ $totalEventAktif }} agenda budaya aktif</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('pengelola.event.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700 no-underline flex items-center justify-between">
                            <span>Buka kalender kegiatan</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dua Kolom: Wisata Terbaru & Event Mendatang -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Wisata Terbaru -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold tracking-tight text-slate-900">Objek wisata terkini</h3>
                        <span class="text-xs text-slate-400">Terdaftar dalam sistem</span>
                    </div>
                    <a href="{{ route('pengelola.wisata.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold no-underline">
                        Lihat semua &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($wisataTerbaru as $w)
                        <div class="p-3 border border-slate-100 rounded-xl flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                            <div class="flex items-center gap-3">
                                @if($w->foto_utama)
                                    <img src="{{ $w->foto_url }}" alt="{{ $w->nama }}" class="w-11 h-11 object-cover rounded-lg border border-slate-200 flex-shrink-0">
                                @else
                                    <div class="w-11 h-11 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                                        Foto
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-sm text-slate-900 leading-tight">{{ $w->nama }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $w->kategori?->nama ?? 'Wisata' }}</div>
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-2">
                                <x-tag-status :status="$w->status" />
                                <a href="{{ route('pengelola.wisata.edit', $w) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-lg no-underline transition-colors">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs sm:text-sm text-slate-400 py-6 text-center">Belum ada objek wisata yang terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <!-- Event Mendatang -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold tracking-tight text-slate-900">Agenda event mendatang</h3>
                        <span class="text-xs text-slate-400">Jadwal pagelaran adat terdekat</span>
                    </div>
                    <a href="{{ route('pengelola.event.index') }}" class="text-xs text-amber-600 hover:text-amber-700 font-semibold no-underline">
                        Buka agenda &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($eventMendatang as $ev)
                        <div class="p-3 border border-slate-100 rounded-xl flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                            <div>
                                <div class="font-semibold text-sm text-slate-900 leading-tight">{{ $ev->judul }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">
                                    {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d M Y') : '' }}
                                    @if($ev->is_multi_hari)
                                        s/d {{ $ev->tanggal_selesai ? $ev->tanggal_selesai->translatedFormat('d M Y') : '' }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $ev->status_turunan_badge_class }}">
                                    {{ $ev->status_turunan }}
                                </span>
                                <a href="{{ route('pengelola.event.edit', $ev) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-lg no-underline transition-colors">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs sm:text-sm text-slate-400 py-6 text-center">Tidak ada event budaya dalam waktu dekat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
