<x-portal-layout>
    <x-slot:title>{{ $wisata->nama }} — Desa Wisata Kampung Gedung Batin</x-slot:title>
    <x-slot:description>{{ Str::limit(strip_tags($wisata->deskripsi), 150) }}</x-slot:description>
    @if($wisata->foto_utama)
        <x-slot:image>{{ $wisata->foto_url }}</x-slot:image>
    @endif

    <div class="py-8 md:py-12" x-data="{
        userLat: null,
        userLng: null,
        distText: null,
        geoLoading: false,
        calcDistance() {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung geolokasi GPS.');
                return;
            }
            this.geoLoading = true;
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.userLat = pos.coords.latitude;
                    this.userLng = pos.coords.longitude;
                    this.geoLoading = false;

                    const R = 6371;
                    const dLat = ({{ $wisata->latitude }} - this.userLat) * Math.PI / 180;
                    const dLon = ({{ $wisata->longitude }} - this.userLng) * Math.PI / 180;
                    const a =
                        Math.sin(dLat/2) * Math.sin(dLat/2) +
                        Math.cos(this.userLat * Math.PI / 180) * Math.cos({{ $wisata->latitude }} * Math.PI / 180) *
                        Math.sin(dLon/2) * Math.sin(dLon/2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    const d = R * c;

                    if (d < 1) {
                        this.distText = Math.round(d * 1000) + ' meter dari posisi Anda saat ini';
                    } else {
                        this.distText = d.toFixed(2) + ' km dari posisi Anda saat ini';
                    }
                },
                (err) => {
                    this.geoLoading = false;
                    alert('Gagal mendeteksi lokasi GPS. Pastikan izin lokasi diaktifkan.');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Breadcrumbs Navigasi Modern -->
            <nav class="text-xs font-medium text-slate-500 flex items-center gap-2">
                <a href="/" class="hover:text-slate-900 no-underline text-slate-500">Beranda</a>
                <span>/</span>
                <a href="/wisata" class="hover:text-slate-900 no-underline text-slate-500">Objek Wisata</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold truncate max-w-xs sm:max-w-md">{{ $wisata->nama }}</span>
            </nav>

            <!-- Header Halaman Editorial Modern -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-850 to-emerald-950 rounded-2xl p-6 md:p-10 text-white shadow-lg">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-3 max-w-3xl">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold rounded-full backdrop-blur-sm">
                                {{ $wisata->kategori?->nama ?? 'Cagar Budaya' }}
                            </span>
                            <span class="text-xs text-slate-400 font-mono">
                                GPS: {{ number_format($wisata->latitude, 6) }}, {{ number_format($wisata->longitude, 6) }}
                            </span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-white leading-tight">
                            {{ $wisata->nama }}
                        </h1>
                        <p class="text-slate-300 text-sm leading-relaxed flex items-center gap-2 font-normal">
                            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $wisata->alamat }}</span>
                        </p>
                    </div>

                    <!-- Tombol Cek Rute Google Maps -->
                    <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                        <a
                            href="{{ $wisata->google_maps_url }}"
                            target="_blank"
                            class="h-12 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md hover:shadow-emerald-600/30 transition-all no-underline flex items-center justify-center gap-2 whitespace-nowrap"
                        >
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <span>Buka Rute di Google Maps</span>
                            <span>↗</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bar Hitung Jarak Real-time (LBS) -->
            <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 text-sm text-slate-700">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="font-medium" x-text="distText ? distText : 'Ketahui jarak langsung dari posisi Anda saat ini ke destinasi ini.'"></span>
                </div>
                <button
                    type="button"
                    @click="calcDistance"
                    class="h-10 px-5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition-colors cursor-pointer whitespace-nowrap shadow-xs"
                >
                    <span x-show="!geoLoading">Hitung Jarak (GPS)</span>
                    <span x-show="geoLoading">Mendeteksi Posisi...</span>
                </button>
            </div>

            <!-- Konten Dua Kolom: Informasi Utama & Sidebar Peta/Fasilitas -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Kolom Kiri (8 Col): Foto & Narasi Lengkap -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Foto Utama -->
                    <div class="card-modern overflow-hidden p-0">
                        @if($wisata->foto_utama)
                            <img src="{{ $wisata->foto_url }}" alt="{{ $wisata->nama }}" class="w-full h-[420px] object-cover">
                        @else
                            <div class="w-full h-72 bg-slate-100 flex items-center justify-center text-slate-400 font-medium">
                                Foto utama belum tersedia
                            </div>
                        @endif
                    </div>

                    <!-- Uraian Sejarah & Deskripsi -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-4">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight border-b border-slate-100 pb-3">
                            Sejarah & Daya Tarik Wisata
                        </h2>

                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line font-normal">
                            {{ $wisata->deskripsi }}
                        </div>
                    </div>

                    <!-- Fasilitas Penunjang yang Tersedia -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-4">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight border-b border-slate-100 pb-3">
                            Sarana Fasilitas di Lokasi
                        </h2>

                        @if($wisata->fasilitas->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($wisata->fasilitas as $f)
                                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-50 flex items-start gap-3 transition-colors">
                                        <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            ✓
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">{{ $f->nama }}</div>
                                            <div class="text-xs text-emerald-700 font-semibold">{{ $f->jenis?->nama }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">{{ $f->keterangan_lokasi }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-400 py-2">
                                Belum ada catatan fasilitas khusus yang terhubung di objek wisata ini. Pengunjung dapat memanfaatkan sarana umum desa di sekitarnya.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Kolom Kanan (4 Col): Peta Leaflet & Informasi Praktis -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Kartu Informasi Praktis -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-5">
                        <h3 class="text-lg font-bold text-slate-900 tracking-tight border-b border-slate-100 pb-3">
                            Informasi Kunjungan
                        </h3>

                        <div class="space-y-4 text-sm">
                            <div class="flex items-start gap-3.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center flex-shrink-0">
                                    🎟️
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">Harga Tiket Masuk</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $wisata->harga_tiket ?: 'Gratis / Sukarela' }}</div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                                    ⏰
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">Jam Operasional</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $wisata->jam_operasional ?: '08.00 - 17.00 WIB' }}</div>
                                </div>
                            </div>

                            @if($wisata->kontak)
                                <div class="flex items-start gap-3.5">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center flex-shrink-0">
                                        📞
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">Narahubung / Pengelola</div>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            <a
                                                href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $wisata->kontak)) }}"
                                                target="_blank"
                                                class="text-emerald-700 font-semibold underline hover:text-emerald-800"
                                            >
                                                {{ $wisata->kontak }} (WhatsApp)
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Mini Peta Leaflet Lokasi Objek Wisata -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                                Peta Titik Lokasi
                            </h3>
                            <span class="text-xs text-slate-400 font-mono">LBS Active</span>
                        </div>

                        <!-- Wadah Peta Mini -->
                        <div id="mini-map" class="h-60 rounded-xl border border-slate-200 z-10 overflow-hidden"></div>

                        <div class="pt-1">
                            <a
                                href="{{ $wisata->google_maps_url }}"
                                target="_blank"
                                class="w-full h-11 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs no-underline flex items-center justify-center gap-2 shadow-xs transition-colors"
                            >
                                <span>Navigasi Google Maps</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi Objek Wisata Lainnya -->
            @if($wisataLain->count() > 0)
                <div class="space-y-6 pt-6 border-t border-slate-200">
                    <div>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Eksplorasi Lanjutan</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-1">
                            Destinasi Lain di Gedung Batin
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($wisataLain as $wl)
                            <div class="card-modern overflow-hidden group flex flex-col justify-between">
                                <div>
                                    <div class="h-44 bg-slate-100 overflow-hidden">
                                        @if($wl->foto_utama)
                                            <img src="{{ $wl->foto_url }}" alt="{{ $wl->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 text-xs font-medium">
                                                Foto belum ada
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-5 space-y-2">
                                        <h3 class="text-lg font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition-colors">
                                            <a href="{{ route('wisata.show', $wl->slug) }}" class="no-underline text-inherit">
                                                {{ $wl->nama }}
                                            </a>
                                        </h3>
                                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                            {{ $wl->deskripsi }}
                                        </p>
                                    </div>
                                </div>

                                <div class="p-5 pt-0">
                                    <a href="{{ route('wisata.show', $wl->slug) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 no-underline flex items-center justify-between">
                                        <span>Lihat detail</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Leaflet Script untuk Mini Peta -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L !== 'undefined') {
                const lat = {{ $wisata->latitude }};
                const lng = {{ $wisata->longitude }};
                
                const miniMap = L.map('mini-map', {
                    center: [lat, lng],
                    zoom: 16,
                    zoomControl: true,
                    attributionControl: false
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(miniMap);

                L.marker([lat, lng])
                    .addTo(miniMap)
                    .bindPopup("<b>{{ addslashes($wisata->nama) }}</b><br><span style='font-size:11px'>{{ addslashes($wisata->alamat) }}</span>")
                    .openPopup();
            }
        });
    </script>
</x-portal-layout>
