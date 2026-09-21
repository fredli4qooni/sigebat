<x-app-layout>
    <x-slot:title>Dashboard Pengelola</x-slot:title>
    <x-slot:header>Ruang Kerja Pengelola Wisata</x-slot:header>

    <div class="space-y-8">
        <!-- Papan Hijau Sambutan (DESIGN.md 7.11: Persetujuan/Pengelola) -->
        <x-papan warna="hijau" class="p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-putih">Selamat Datang, {{ auth()->user()->name }}</h2>
                    <p class="text-putih/90 text-[15px] mt-1">
                        Ruang pengelolaan data objek wisata adat, sarana fasilitas desa, dan kalender kegiatan budaya Kampung Gedung Batin.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 bg-putih text-aspal font-bold text-xs rounded-kontrol">
                        Status akun: Aktif
                    </span>
                </div>
            </div>
        </x-papan>

        <!-- Aksi Cepat Pengelola (DESIGN.md 8.7: Action-First) -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-6">
            <h3 class="text-lg font-bold font-papan text-aspal mb-3">Tindakan cepat</h3>
            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('pengelola.wisata.create') }}"
                    class="h-[44px] px-4 bg-cokelat text-putih font-bold text-sm rounded-kontrol hover:bg-cokelat-gelap no-underline flex items-center gap-2"
                >
                    <span class="text-lg leading-none">+</span>
                    <span>Tambah Objek Wisata</span>
                </a>

                <a
                    href="{{ route('pengelola.fasilitas.create') }}"
                    class="h-[44px] px-4 bg-biru text-putih font-bold text-sm rounded-kontrol hover:bg-biru-gelap no-underline flex items-center gap-2"
                >
                    <span class="text-lg leading-none">+</span>
                    <span>Tambah Fasilitas Desa</span>
                </a>

                <a
                    href="{{ route('pengelola.event.create') }}"
                    class="h-[44px] px-4 bg-kuning text-aspal border-2 border-aspal font-bold text-sm rounded-kontrol hover:bg-kuning-gelap no-underline flex items-center gap-2"
                >
                    <span class="text-lg leading-none">+</span>
                    <span>Jadwalkan Event Budaya</span>
                </a>
            </div>
        </div>

        <!-- Ringkasan Statistik Konten -->
        <div>
            <h3 class="text-xl font-bold font-papan text-aspal mb-4">Ringkasan konten desa</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Objek Wisata -->
                <div class="bg-putih border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-abu">Objek Wisata</span>
                            <span class="w-8 h-8 rounded-kontrol bg-cokelat text-putih flex items-center justify-center text-sm font-bold">🏡</span>
                        </div>
                        <div class="mt-3 text-3xl font-bold font-papan text-cokelat">{{ $totalWisata }}</div>
                        <p class="text-xs text-abu mt-1">{{ $totalWisataAktif }} wisata berstatus aktif tayang</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-beton">
                        <a href="{{ route('pengelola.wisata.index') }}" class="text-xs font-bold text-aspal underline hover:text-cokelat flex items-center justify-between">
                            <span>Kelola daftar wisata</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Fasilitas Desa -->
                <div class="bg-putih border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-abu">Fasilitas Desa</span>
                            <span class="w-8 h-8 rounded-kontrol bg-biru text-putih flex items-center justify-center text-sm font-bold">🚻</span>
                        </div>
                        <div class="mt-3 text-3xl font-bold font-papan text-biru">{{ $totalFasilitas }}</div>
                        <p class="text-xs text-abu mt-1">{{ $totalFasilitasUmum }} fasilitas umum kampung</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-beton">
                        <a href="{{ route('pengelola.fasilitas.index') }}" class="text-xs font-bold text-aspal underline hover:text-biru flex items-center justify-between">
                            <span>Kelola daftar fasilitas</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Event Budaya -->
                <div class="bg-putih border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-abu">Event Budaya</span>
                            <span class="w-8 h-8 rounded-kontrol bg-kuning text-aspal flex items-center justify-center text-sm font-bold">📅</span>
                        </div>
                        <div class="mt-3 text-3xl font-bold font-papan text-aspal">{{ $totalEvent }}</div>
                        <p class="text-xs text-abu mt-1">{{ $totalEventAktif }} agenda budaya aktif</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-beton">
                        <a href="{{ route('pengelola.event.index') }}" class="text-xs font-bold text-aspal underline hover:text-kuning-gelap flex items-center justify-between">
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
            <div class="bg-putih border-2 border-aspal rounded-papan p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-beton">
                    <h3 class="text-lg font-bold font-papan text-aspal">Objek wisata terkini</h3>
                    <a href="{{ route('pengelola.wisata.index') }}" class="text-xs text-aspal underline font-semibold hover:text-cokelat">
                        Lihat semua &rarr;
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($wisataTerbaru as $w)
                        <div class="p-3 border-2 border-beton rounded-kontrol flex items-center justify-between hover:border-aspal">
                            <div class="flex items-center gap-3">
                                @if($w->foto_utama)
                                    <img src="{{ $w->foto_url }}" alt="{{ $w->nama }}" class="w-10 h-10 object-cover rounded-kontrol border border-aspal flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-kontrol bg-beton text-abu flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        Foto
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-sm text-aspal leading-tight">{{ $w->nama }}</div>
                                    <div class="text-xs text-abu">{{ $w->kategori?->nama ?? 'Wisata' }}</div>
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-2">
                                <x-tag-status :status="$w->status" />
                                <a href="{{ route('pengelola.wisata.edit', $w) }}" class="px-2 py-1 bg-beton text-aspal font-bold text-xs rounded-kontrol hover:bg-abu/20 no-underline">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-abu py-4 text-center">Belum ada objek wisata yang terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <!-- Event Mendatang -->
            <div class="bg-putih border-2 border-aspal rounded-papan p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-beton">
                    <h3 class="text-lg font-bold font-papan text-aspal">Agenda event mendatang</h3>
                    <a href="{{ route('pengelola.event.index') }}" class="text-xs text-aspal underline font-semibold hover:text-kuning-gelap">
                        Buka agenda &rarr;
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($eventMendatang as $ev)
                        <div class="p-3 border-2 border-beton rounded-kontrol flex items-center justify-between hover:border-aspal">
                            <div>
                                <div class="font-bold text-sm text-aspal leading-tight">{{ $ev->judul }}</div>
                                <div class="text-xs text-abu font-mono mt-0.5">
                                    {{ $ev->tanggal_mulai ? $ev->tanggal_mulai->translatedFormat('d M Y') : '' }}
                                    @if($ev->is_multi_hari)
                                        s/d {{ $ev->tanggal_selesai ? $ev->tanggal_selesai->translatedFormat('d M Y') : '' }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-tag text-xs font-bold {{ $ev->status_turunan_badge_class }}">
                                    {{ $ev->status_turunan }}
                                </span>
                                <a href="{{ route('pengelola.event.edit', $ev) }}" class="px-2 py-1 bg-beton text-aspal font-bold text-xs rounded-kontrol hover:bg-abu/20 no-underline">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-abu py-4 text-center">Tidak ada event budaya dalam waktu dekat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
