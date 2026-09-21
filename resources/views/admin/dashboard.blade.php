<x-app-layout>
    <x-slot:title>Dashboard Administrator</x-slot:title>
    <x-slot:header>Dashboard Administrator</x-slot:header>

    <div class="space-y-8">
        <!-- Banner Sambutan Eksekutif Solid Slate -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-3xl">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">Selamat Datang, {{ auth()->user()->name }}</h2>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed font-normal">
                    Panel pemantauan sistem, data master, verifikasi pengelola, dan audit aktivitas Desa Wisata Kampung Gedung Batin.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Administrator Sistem</span>
                </span>
            </div>
        </div>

        <!-- Bagian: Perlu Tindakan -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold tracking-tight text-slate-900">Perlu Tindakan</h3>
                    <p class="text-xs text-slate-500">Antrean sistem yang memerlukan perhatian administrator</p>
                </div>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                    Prioritas Kerja
                </span>
            </div>

            <div class="space-y-3">
                @if($pengelolaPending > 0)
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-slate-900 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span class="font-semibold text-sm text-amber-900">
                                {{ $pengelolaPending }} calon pengelola menunggu verifikasi akun.
                            </span>
                        </div>
                        <a href="{{ route('admin.verifikasi.index') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold no-underline inline-flex items-center justify-center gap-1.5 transition-colors shadow-xs">
                            <span>Periksa Permohonan</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                @endif

                @if($eventPending > 0)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-slate-700">
                                Ada {{ $eventPending }} event budaya berstatus Pending (draf).
                            </span>
                        </div>
                        <span class="text-xs text-slate-400">Tersimpan di sistem pengelola</span>
                    </div>
                @endif

                @if($wisataPending > 0)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-slate-700">
                                Ada {{ $wisataPending }} objek wisata berstatus Pending (draf).
                            </span>
                        </div>
                        <span class="text-xs text-slate-400">Tersimpan di sistem pengelola</span>
                    </div>
                @endif

                @if($pengelolaPending === 0 && $eventPending === 0 && $wisataPending === 0)
                    <div class="py-6 text-center text-xs sm:text-sm text-slate-500 flex flex-col items-center justify-center gap-1.5">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium text-slate-700">Semua data telah tertata rapi</span>
                        <span class="text-slate-400">Tidak ada antrean verifikasi atau tindakan mendesak saat ini.</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ringkasan Statistik -->
        <div>
            <h3 class="text-lg font-bold tracking-tight text-slate-900 mb-4">Ringkasan data desa</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Wisata Aktif -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tempat wisata aktif</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 256 256">
                                <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold tracking-tight text-slate-900">{{ $wisataAktif }}</div>
                        <span class="text-xs text-slate-400 mt-1 block">Tayang di halaman publik</span>
                    </div>
                </div>

                <!-- Event Aktif -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Event budaya aktif</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold tracking-tight text-slate-900">{{ $eventAktif }}</div>
                        <span class="text-xs text-slate-400 mt-1 block">Terjadwal di kalender</span>
                    </div>
                </div>

                <!-- Fasilitas Total -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Fasilitas pendukung</span>
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold tracking-tight text-slate-900">{{ $fasilitasTotal }}</div>
                        <span class="text-xs text-slate-400 mt-1 block">Umum & di objek wisata</span>
                    </div>
                </div>

                <!-- Pengelola Aktif -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengelola aktif</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold tracking-tight text-slate-900">{{ $pengelolaAktif }}</div>
                        <span class="text-xs text-slate-400 mt-1 block">Pengurus desa terdaftar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dua Kolom Data Terbaru: Wisata & Log Aktivitas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Data Wisata Terbaru -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-bold tracking-tight text-slate-900">Data wisata terbaru</h3>
                            <span class="text-xs text-slate-400">5 entri terakhir</span>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($dataWisataTerbaru as $wisata)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50/70 transition-colors">
                                <div>
                                    <div class="font-semibold text-sm text-slate-900">{{ $wisata->nama }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $wisata->kategori?->nama }} &bull; Oleh {{ $wisata->creator?->name }}</div>
                                </div>
                                <x-tag-status :status="$wisata->status" />
                            </div>
                        @empty
                            <p class="text-xs sm:text-sm text-slate-400 py-6 text-center">Belum ada objek wisata yang ditambahkan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Log Aktivitas Terbaru -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-bold tracking-tight text-slate-900">Log aktivitas terkini</h3>
                            <span class="text-xs text-slate-400">Audit jejak digital sistem</span>
                        </div>
                        <a href="{{ route('admin.log') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold no-underline">
                            Lihat semua &rarr;
                        </a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($logTerbaru as $log)
                            <div class="p-3 rounded-xl border border-slate-100 text-xs space-y-1 hover:bg-slate-50/70 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-slate-900">{{ $log->aksi }}</span>
                                    <span class="text-slate-400">{{ $log->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y H:i') }} WIB</span>
                                </div>
                                <div class="text-slate-500">
                                    Pengguna: <strong class="text-slate-700">{{ $log->user?->name ?? 'Sistem / Anonim' }}</strong>
                                    @if($log->entitas_tipe)
                                        &bull; Entitas: {{ $log->entitas_tipe }} (ID: {{ $log->entitas_id ?? '-' }})
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs sm:text-sm text-slate-400 py-6 text-center">Belum ada catatan aktivitas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
