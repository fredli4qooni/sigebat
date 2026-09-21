<x-portal-layout>
    <x-slot:title>Daftar Objek Wisata — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Jelajahi cagar budaya rumah panggung adat, keindahan alam, dan situs bersejarah di Kampung Gedung Batin, Way Kanan.</x-slot:description>

    <div class="py-8 md:py-12" x-data="{
        userLat: null,
        userLng: null,
        geoActive: false,
        geoLoading: false,
        geoError: null,
        radiusFilter: '',
        activateGps() {
            if (!navigator.geolocation) {
                alert('Perangkat Anda tidak mendukung fitur geolokasi GPS.');
                return;
            }
            this.geoLoading = true;
            this.geoError = null;

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.userLat = pos.coords.latitude;
                    this.userLng = pos.coords.longitude;
                    this.geoActive = true;
                    this.geoLoading = false;
                    this.calculateAllDistances();
                },
                (err) => {
                    this.geoLoading = false;
                    this.geoError = 'Izin lokasi tidak diberikan.';
                    alert('Gagal mendeteksi lokasi. Pastikan izin lokasi browser telah diaktifkan.');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },
        calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        },
        calculateAllDistances() {
            document.querySelectorAll('[data-wisata-lat]').forEach(card => {
                const lat = parseFloat(card.getAttribute('data-wisata-lat'));
                const lng = parseFloat(card.getAttribute('data-wisata-lng'));
                if (!isNaN(lat) && !isNaN(lng) && this.userLat !== null) {
                    const dist = this.calculateDistance(this.userLat, this.userLng, lat, lng);
                    const badge = card.querySelector('.distance-display');
                    if (badge) {
                        badge.classList.remove('hidden');
                        if (dist < 1) {
                            badge.textContent = Math.round(dist * 1000) + ' m dari Anda';
                        } else {
                            badge.textContent = dist.toFixed(1) + ' km dari Anda';
                        }
                    }
                    card.setAttribute('data-distance', dist);
                }
            });
        }
    }">
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 space-y-8">
            <!-- Papan Judul Seksi -->
            <x-papan warna="cokelat" class="p-6 sm:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-putih/80 uppercase tracking-wider">Katalog Destinasi</span>
                        <h1 class="text-3xl sm:text-4xl font-bold font-papan text-putih mt-1">
                            Objek Wisata & Cagar Budaya
                        </h1>
                        <p class="text-putih/90 text-sm mt-1 max-w-xl leading-relaxed">
                            Temukan rumah adat panggung kayu ulin, situs bersejarah, dan titik wisata Kampung Gedung Batin lengkap dengan koordinat LBS.
                        </p>
                    </div>
                    <div>
                        <a
                            href="/peta"
                            class="h-[44px] px-5 bg-putih text-aspal border-2 border-aspal font-bold text-sm rounded-kontrol hover:bg-beton no-underline flex items-center gap-2 whitespace-nowrap"
                        >
                            <span>🗺️</span>
                            <span>Buka di Peta LBS</span>
                        </a>
                    </div>
                </div>
            </x-papan>

            <!-- Bar Filter, Pencarian & LBS Geolocation Trigger -->
            <div class="bg-putih border-2 border-aspal rounded-papan p-5 space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <!-- Form Filter & Pencarian -->
                    <form method="GET" action="{{ route('wisata.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="w-full sm:w-64">
                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Cari nama atau petunjuk lokasi..."
                                class="h-[44px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal placeholder-abu focus:outline-none focus:ring-0 focus:border-aspal"
                            >
                        </div>

                        <div class="w-48">
                            <select
                                name="kategori"
                                class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                            >
                                <option value="">Semua kategori</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->slug }}" {{ request('kategori') === $kat->slug ? 'selected' : '' }}>
                                        {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-40">
                            <select
                                name="sort"
                                class="h-[44px] px-3 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                            >
                                <option value="terbaru" {{ request('sort') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="nama_asc" {{ request('sort') === 'nama_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                                <option value="nama_desc" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit" class="h-[44px] px-5 bg-aspal text-putih font-bold text-sm rounded-kontrol hover:bg-black cursor-pointer">
                                Cari
                            </button>

                            @if(request()->hasAny(['q', 'kategori', 'sort']))
                                <a href="{{ route('wisata.index') }}" class="h-[44px] px-3 flex items-center bg-beton text-aspal font-semibold text-xs border border-abu rounded-kontrol hover:bg-abu/20 no-underline">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Tombol LBS Geolocation -->
                    <div class="flex-shrink-0">
                        <button
                            type="button"
                            @click="activateGps"
                            class="h-[44px] px-4 bg-hijau text-putih border-2 border-aspal rounded-kontrol font-bold text-xs hover:opacity-95 cursor-pointer flex items-center gap-2"
                        >
                            <span x-show="!geoLoading && !geoActive">📍</span>
                            <span x-show="geoLoading" class="animate-spin">⏳</span>
                            <span x-show="geoActive">✓</span>
                            <span x-text="geoActive ? 'GPS Aktif: Jarak Dihitung' : (geoLoading ? 'Mendeteksi...' : 'Hitung Jarak dari Lokasi Saya')"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid Kartu Objek Wisata -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="wisata-grid">
                @forelse($wisataList as $w)
                    <div
                        class="bg-putih border-2 border-aspal rounded-papan overflow-hidden flex flex-col justify-between hover:translate-y-[-2px] transition-transform wisata-card"
                        data-wisata-lat="{{ $w->latitude }}"
                        data-wisata-lng="{{ $w->longitude }}"
                        data-wisata-id="{{ $w->id }}"
                    >
                        <div>
                            <!-- Foto Objek Wisata -->
                            <div class="relative h-52 bg-beton overflow-hidden border-b-2 border-aspal">
                                @if($w->foto_utama)
                                    <img src="{{ $w->foto_url }}" alt="{{ $w->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-beton text-abu font-semibold text-sm">
                                        Foto belum tersedia
                                    </div>
                                @endif
                                
                                <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start">
                                    <span class="px-2.5 py-1 bg-cokelat text-putih text-xs font-bold rounded-tag">
                                        {{ $w->kategori?->nama ?? 'Wisata' }}
                                    </span>

                                    <!-- Badge Jarak Dinamis LBS (muncul saat GPS aktif) -->
                                    <span class="distance-display hidden px-2.5 py-1 bg-hijau text-putih text-xs font-bold rounded-tag font-mono">
                                    </span>
                                </div>
                            </div>

                            <!-- Konten Card -->
                            <div class="p-5 space-y-2.5">
                                <h3 class="text-xl font-bold font-papan text-aspal leading-snug">
                                    <a href="{{ route('wisata.show', $w->slug) }}" class="no-underline text-aspal hover:text-cokelat">
                                        {{ $w->nama }}
                                    </a>
                                </h3>

                                <div class="text-xs text-abu flex items-center gap-1 font-mono">
                                    <span>📍</span>
                                    <span class="truncate">{{ $w->alamat }}</span>
                                </div>

                                <p class="text-xs text-abu line-clamp-2 leading-relaxed">
                                    {{ $w->deskripsi }}
                                </p>

                                <div class="pt-3 text-xs text-aspal flex items-center justify-between border-t border-beton font-mono">
                                    <span class="font-semibold">🎟️ {{ $w->harga_tiket ?: 'Gratis' }}</span>
                                    <span class="text-abu">⏰ {{ $w->jam_operasional ?: '08.00 - 17.00 WIB' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Buka Rincian & Google Maps -->
                        <div class="p-5 pt-0 flex items-center gap-2">
                            <a
                                href="{{ route('wisata.show', $w->slug) }}"
                                class="flex-1 h-[40px] px-3 bg-putih border-2 border-aspal text-aspal font-bold text-xs rounded-kontrol hover:bg-beton no-underline flex items-center justify-center gap-1"
                            >
                                <span>Detail Informasi</span>
                                <span>&rarr;</span>
                            </a>
                            <a
                                href="{{ $w->google_maps_url }}"
                                target="_blank"
                                class="h-[40px] px-3 bg-beton border-2 border-aspal text-aspal font-bold text-xs rounded-kontrol hover:bg-abu/20 no-underline flex items-center justify-center"
                                title="Buka rute di Google Maps"
                            >
                                ↗ Rute
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-16 text-center text-abu">
                        Tidak ada objek wisata yang sesuai dengan kata kunci pencarian Anda.
                    </div>
                @endforelse
            </div>

            @if($wisataList->hasPages())
                <div class="pt-4">
                    {{ $wisataList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-portal-layout>
