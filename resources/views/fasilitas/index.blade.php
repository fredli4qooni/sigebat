<x-portal-layout>
    <x-slot:title>Direktori Fasilitas Desa — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Daftar fasilitas penunjang kenyamanan wisatawan di Desa Wisata Kampung Gedung Batin: musala, toilet umum, pos ronda, dan area parkir.</x-slot:description>

    <div class="py-8 md:py-12">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 space-y-8">
            <!-- Papan Biru Judul Halaman -->
            <x-papan warna="biru" class="p-6 sm:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-putih/80 uppercase tracking-wider">Sarana & Prasarana</span>
                        <h1 class="text-3xl sm:text-4xl font-bold font-papan text-putih mt-1">
                            Fasilitas Pendukung Desa
                        </h1>
                        <p class="text-putih/90 text-sm mt-1 max-w-xl leading-relaxed">
                            Informasi ketersediaan musala, toilet umum, pos pengamanan adat, dan fasilitas penunjang lainnya demi kenyamanan wisatawan.
                        </p>
                    </div>
                    <div>
                        <a
                            href="/peta"
                            class="h-[44px] px-5 bg-putih text-aspal border-2 border-aspal font-bold text-sm rounded-kontrol hover:bg-beton no-underline flex items-center gap-2 whitespace-nowrap"
                        >
                            <span>🗺️</span>
                            <span>Lihat di Peta Desa</span>
                        </a>
                    </div>
                </div>
            </x-papan>

            <!-- Bar Filter & Pencarian -->
            <div class="bg-putih border-2 border-aspal rounded-papan p-5">
                <form method="GET" action="{{ route('fasilitas.index') }}" class="flex flex-wrap items-center gap-3">
                    <div class="w-full sm:w-64">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari fasilitas atau lokasi..."
                            class="h-[44px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal placeholder-abu focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                    </div>

                    <div class="w-48">
                        <select
                            name="jenis_id"
                            class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            <option value="">Semua jenis fasilitas</option>
                            @foreach($jenisList as $j)
                                <option value="{{ $j->id }}" {{ request('jenis_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-48">
                        <select
                            name="lingkup"
                            class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            <option value="">Semua lingkup</option>
                            <option value="umum" {{ request('lingkup') === 'umum' ? 'selected' : '' }}>Fasilitas Umum Desa</option>
                            <option value="khusus" {{ request('lingkup') === 'khusus' ? 'selected' : '' }}>Di Objek Wisata</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="h-[44px] px-5 bg-aspal text-putih font-bold text-sm rounded-kontrol hover:bg-black cursor-pointer">
                            Filter
                        </button>

                        @if(request()->hasAny(['q', 'jenis_id', 'lingkup']))
                            <a href="{{ route('fasilitas.index') }}" class="h-[44px] px-3 flex items-center bg-beton text-aspal font-semibold text-xs border border-abu rounded-kontrol hover:bg-abu/20 no-underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Grid Kartu Fasilitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($fasilitasList as $f)
                    <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden flex flex-col justify-between hover:translate-y-[-2px] transition-transform">
                        <div>
                            <!-- Foto Fasilitas / Thumbnail -->
                            @if($f->foto)
                                <div class="h-44 bg-beton overflow-hidden border-b-2 border-aspal">
                                    <img src="{{ $f->foto_url }}" alt="{{ $f->nama }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="p-5 space-y-3">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-0.5 bg-biru text-putih text-xs font-bold rounded-tag">
                                        {{ $f->jenis?->nama ?? 'Fasilitas' }}
                                    </span>

                                    @if($f->is_umum)
                                        <span class="px-2 py-0.5 bg-hijau/20 border border-hijau text-hijau-gelap text-[11px] font-bold rounded-tag">
                                            Umum Desa
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 bg-cokelat/15 border border-cokelat text-cokelat-gelap text-[11px] font-bold rounded-tag">
                                            Di Objek Wisata
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold font-papan text-aspal leading-snug">
                                    {{ $f->nama }}
                                </h3>

                                <div class="text-xs text-abu flex items-start gap-1.5 font-mono">
                                    <span>📍</span>
                                    <span>{{ $f->keterangan_lokasi }}</span>
                                </div>

                                @if($f->objekWisata)
                                    <div class="p-2.5 bg-beton/40 rounded-kontrol border border-beton text-xs">
                                        <span class="text-abu">Tersedia di:</span>
                                        <a href="{{ route('wisata.show', $f->objekWisata->slug) }}" class="font-bold text-aspal hover:text-cokelat underline ml-1">
                                            {{ $f->objekWisata->nama }} &rarr;
                                        </a>
                                    </div>
                                @endif

                                @if($f->deskripsi)
                                    <p class="text-xs text-aspal/80 line-clamp-3 leading-relaxed">
                                        {{ $f->deskripsi }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Kartu -->
                        <div class="p-5 pt-0 border-t border-beton mt-3">
                            <div class="pt-3 flex items-center justify-between text-xs text-abu">
                                <span>Status: Tersedia</span>
                                <a href="/peta" class="text-biru font-bold hover:underline">
                                    Buka Peta &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-16 text-center text-abu">
                        Tidak ada fasilitas yang sesuai dengan kriteria pencarian Anda.
                    </div>
                @endforelse
            </div>

            @if($fasilitasList->hasPages())
                <div class="pt-4">
                    {{ $fasilitasList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-portal-layout>
