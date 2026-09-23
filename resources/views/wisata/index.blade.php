<x-portal-layout>
    <x-slot:title>Daftar Objek Wisata & Cagar Budaya — Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Jelajahi cagar budaya rumah panggung adat, keindahan alam, dan situs bersejarah di Kampung Gedung Batin, Way Kanan.</x-slot:description>

    <div class="py-8 md:py-12" x-data="{
        userLat: null,
        userLng: null,
        geoActive: false,
        geoLoading: false,
        geoError: null,
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
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Header Halaman Modern -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 md:p-10 text-white shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-2xl">
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">
                            Katalog Destinasi Budaya
                        </span>
                        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                            Objek Wisata & Cagar Budaya
                        </h1>
                        <p class="text-slate-300 text-sm leading-relaxed font-normal">
                            Temukan rumah adat panggung kayu ulin, situs cagar budaya, dan titik bersejarah Kampung Gedung Batin lengkap dengan koordinat LBS presisi.
                        </p>
                    </div>
                    <div>
                        <a
                            href="/peta"
                            class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-semibold text-sm shadow-md transition-all no-underline whitespace-nowrap"
                        >
                            <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span>Buka di Peta LBS</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bar Filter, Pencarian & LBS Geolocation Trigger -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <!-- Form Filter & Pencarian -->
                    <form method="GET" action="{{ route('wisata.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative w-full sm:w-64">
                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Cari nama atau petunjuk lokasi..."
                                class="h-11 pl-9 pr-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                            >
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <div class="w-48">
                            <select
                                name="kategori"
                                class="h-11 px-3 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                            >
                                <option value="">Semua Kategori</option>
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
                                class="h-11 px-3 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                            >
                                <option value="terbaru" {{ request('sort') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="nama_asc" {{ request('sort') === 'nama_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                                <option value="nama_desc" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit" class="h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl transition-colors cursor-pointer shadow-xs">
                                Cari
                            </button>

                            @if(request()->hasAny(['q', 'kategori', 'sort']))
                                <a href="{{ route('wisata.index') }}" class="h-11 px-3.5 flex items-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl no-underline transition-colors">
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
                            class="h-11 px-4 rounded-xl font-semibold text-xs cursor-pointer flex items-center gap-2 transition-all shadow-xs"
                            :class="geoActive ? 'bg-emerald-50 text-emerald-800 border border-emerald-300' : 'bg-emerald-600 hover:bg-emerald-700 text-white'"
                        >
                            <svg x-show="!geoLoading" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="geoLoading" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="geoActive ? 'GPS Aktif & Jarak Dihitung' : (geoLoading ? 'Mendeteksi Koordinat...' : 'Hitung Jarak dari Lokasi Saya')"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid Kartu Objek Wisata -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8" id="wisata-grid">
                @forelse($wisataList as $w)
                    <div
                        class="card-modern overflow-hidden group flex flex-col justify-between"
                        data-wisata-lat="{{ $w->latitude }}"
                        data-wisata-lng="{{ $w->longitude }}"
                        data-wisata-id="{{ $w->id }}"
                    >
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
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 font-medium text-sm">
                                        Foto belum tersedia
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start">
                                    <span class="px-2.5 py-1 bg-slate-900/80 backdrop-blur-md text-white text-xs font-medium rounded-md shadow-xs">
                                        {{ $w->kategori?->nama ?? 'Wisata' }}
                                    </span>

                                    <!-- Badge Jarak Dinamis LBS (muncul saat GPS aktif) -->
                                    <span class="distance-display hidden px-2.5 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-md shadow-sm font-mono">
                                    </span>
                                </div>
                            </div>

                            <!-- Konten Card -->
                            <div class="p-6 space-y-3">
                                <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug group-hover:text-emerald-700 transition-colors">
                                    <a href="{{ route('wisata.show', $w->slug) }}" class="no-underline text-inherit">
                                        {{ $w->nama }}
                                    </a>
                                </h3>

                                <div class="text-xs text-slate-500 flex items-center gap-1.5 font-mono">
                                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="truncate">{{ $w->alamat }}</span>
                                </div>

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

                        <!-- Aksi Card -->
                        <div class="p-6 pt-0 flex items-center gap-2">
                            <a
                                href="{{ route('wisata.show', $w->slug) }}"
                                class="flex-1 h-10 px-4 rounded-lg bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-700 font-semibold text-xs no-underline flex items-center justify-center gap-1.5 transition-all"
                            >
                                <span>Informasi Lengkap</span>
                                <span>&rarr;</span>
                            </a>
                            <a
                                href="{{ $w->google_maps_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="h-10 px-3.5 rounded-lg border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-900 bg-white font-medium text-xs no-underline flex items-center justify-center gap-1 transition-all"
                                title="Buka Rute di Google Maps"
                            >
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                <span class="hidden sm:inline">Peta</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200 p-8 space-y-3">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div class="text-lg font-bold text-slate-800">Tidak ada objek wisata yang sesuai kriteria pencarian</div>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            Coba ubah kata kunci atau hapus filter kategori untuk menemukan objek wisata lainnya.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('wisata.index') }}" class="inline-flex px-4 py-2 rounded-lg bg-emerald-600 text-white font-semibold text-xs no-underline hover:bg-emerald-700 transition-colors">
                                Tampilkan Semua Wisata
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Paginasi -->
            @if($wisataList->hasPages())
                <div class="pt-4 flex justify-center">
                    {{ $wisataList->links() }}
                </div>
            @endif
        </div>
    </div>
</x-portal-layout>
