<x-app-layout>
    <x-slot:title>Dashboard Administrator</x-slot:title>
    <x-slot:header>Dashboard Administrator</x-slot:header>

    <div class="space-y-8">
        <!-- Papan Selamat Datang (DESIGN.md 8.7) -->
        <x-papan warna="aspal" class="p-6">
            <h2 class="text-2xl font-bold font-papan text-putih">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-putih/80 text-[16px] mt-1">
                Panel pemantauan sistem, data master, verifikasi pengelola, dan audit aktivitas Desa Wisata Kampung Gedung Batin.
            </p>
        </x-papan>

        <!-- Bagian: Perlu Tindakan (DESIGN.md 8.7: Action-First) -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-beton">
                <h3 class="text-xl font-bold font-papan text-aspal">Perlu tindakan</h3>
                <span class="text-xs text-abu">Memerlukan perhatian admin</span>
            </div>

            <div class="space-y-3">
                @if($pengelolaPending > 0)
                    <div class="p-4 rounded-kontrol bg-kuning text-aspal border-2 border-aspal flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-aspal inline-block animate-pulse"></span>
                            <span class="font-bold text-[16px]">
                                {{ $pengelolaPending }} calon pengelola menunggu verifikasi akun.
                            </span>
                        </div>
                        <a href="{{ route('admin.verifikasi.index') }}" class="px-4 py-2 bg-aspal text-putih rounded-kontrol text-sm font-papan font-bold no-underline hover:bg-black">
                            Periksa &rarr;
                        </a>
                    </div>
                @endif

                @if($eventPending > 0)
                    <div class="p-4 rounded-kontrol bg-beton text-aspal border-2 border-dashed border-aspal flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold">
                                Ada {{ $eventPending }} event budaya berstatus Pending (draf).
                            </span>
                        </div>
                        <span class="text-xs text-abu">Tersimpan di sistem pengelola</span>
                    </div>
                @endif

                @if($wisataPending > 0)
                    <div class="p-4 rounded-kontrol bg-beton text-aspal border-2 border-dashed border-aspal flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold">
                                Ada {{ $wisataPending }} objek wisata berstatus Pending (draf).
                            </span>
                        </div>
                        <span class="text-xs text-abu">Tersimpan di sistem pengelola</span>
                    </div>
                @endif

                @if($pengelolaPending === 0 && $eventPending === 0 && $wisataPending === 0)
                    <div class="py-4 text-center text-sm text-abu">
                        Semua data telah tertata rapi. Tidak ada antrean verifikasi atau tindakan mendesak saat ini.
                    </div>
                @endif
            </div>
        </div>

        <!-- Ringkasan Statistik (DESIGN.md 8.7) -->
        <div>
            <h3 class="text-xl font-bold font-papan text-aspal mb-4">Ringkasan data desa</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-putih border-2 border-aspal rounded-kartu p-5 flex flex-col justify-between">
                    <span class="text-xs font-bold text-abu">Tempat wisata aktif</span>
                    <div class="mt-2 text-3xl font-bold font-papan text-cokelat">{{ $wisataAktif }}</div>
                    <span class="text-xs text-abu mt-1">Tayang di halaman publik</span>
                </div>

                <div class="bg-putih border-2 border-aspal rounded-kartu p-5 flex flex-col justify-between">
                    <span class="text-xs font-bold text-abu">Event budaya aktif</span>
                    <div class="mt-2 text-3xl font-bold font-papan text-kuning-gelap">{{ $eventAktif }}</div>
                    <span class="text-xs text-abu mt-1">Terjadwal di kalender</span>
                </div>

                <div class="bg-putih border-2 border-aspal rounded-kartu p-5 flex flex-col justify-between">
                    <span class="text-xs font-bold text-abu">Fasilitas pendukung</span>
                    <div class="mt-2 text-3xl font-bold font-papan text-biru">{{ $fasilitasTotal }}</div>
                    <span class="text-xs text-abu mt-1">Umum & di objek wisata</span>
                </div>

                <div class="bg-putih border-2 border-aspal rounded-kartu p-5 flex flex-col justify-between">
                    <span class="text-xs font-bold text-abu">Pengelola aktif</span>
                    <div class="mt-2 text-3xl font-bold font-papan text-hijau">{{ $pengelolaAktif }}</div>
                    <span class="text-xs text-abu mt-1">Pengurus desa terdaftar</span>
                </div>
            </div>
        </div>

        <!-- Dua Kolom Data Terbaru: Wisata & Log Aktivitas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Data Wisata Terbaru (PRD ADM-01) -->
            <div class="bg-putih border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-beton">
                        <h3 class="text-lg font-bold font-papan text-aspal">Data wisata terbaru</h3>
                        <span class="text-xs text-abu">5 entri terakhir</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($dataWisataTerbaru as $wisata)
                            <div class="flex items-center justify-between p-3 rounded-kontrol border border-beton">
                                <div>
                                    <div class="font-semibold text-[15px] text-aspal">{{ $wisata->nama }}</div>
                                    <div class="text-xs text-abu">{{ $wisata->kategori?->nama }} &bull; Oleh {{ $wisata->creator?->name }}</div>
                                </div>
                                <x-tag-status :status="$wisata->status" />
                            </div>
                        @empty
                            <p class="text-sm text-abu py-4 text-center">Belum ada objek wisata yang ditambahkan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Log Aktivitas Terbaru (PRD ADM-01) -->
            <div class="bg-putih border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-beton">
                        <h3 class="text-lg font-bold font-papan text-aspal">Log aktivitas terkini</h3>
                        <a href="{{ route('admin.log') }}" class="text-xs text-aspal underline font-semibold hover:text-cokelat">
                            Lihat semua &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($logTerbaru as $log)
                            <div class="p-3 rounded-kontrol border border-beton text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-aspal">{{ $log->aksi }}</span>
                                    <span class="text-abu">{{ $log->created_at->setTimezone('Asia/Jakarta')->translatedFormat('d M Y H:i') }} WIB</span>
                                </div>
                                <div class="text-abu">
                                    Pengguna: <strong class="text-aspal">{{ $log->user?->name ?? 'Sistem / Anonim' }}</strong>
                                    @if($log->entitas_tipe)
                                        &bull; Entitas: {{ $log->entitas_tipe }} (ID: {{ $log->entitas_id ?? '-' }})
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-abu py-4 text-center">Belum ada catatan aktivitas.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
