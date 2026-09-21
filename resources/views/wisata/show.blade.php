<x-portal-layout>
    <x-slot:title>{{ $wisata->nama }} — Desa Wisata Kampung Gedung Batin</x-slot:title>
    <x-slot:description>{{ Str::limit(strip_tags($wisata->deskripsi), 150) }}</x-slot:description>

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
        <div class="max-w-[1240px] mx-auto px-4 sm:px-6 space-y-8">
            <!-- Breadcrumbs Navigasi -->
            <div class="text-xs font-semibold text-abu flex items-center gap-2">
                <a href="/" class="hover:underline text-aspal">Beranda</a>
                <span>&rsaquo;</span>
                <a href="/wisata" class="hover:underline text-aspal">Objek Wisata</a>
                <span>&rsaquo;</span>
                <span class="text-cokelat font-bold">{{ $wisata->nama }}</span>
            </div>

            <!-- Papan Cokelat Judul Halaman -->
            <x-papan warna="cokelat" class="p-6 sm:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 bg-putih text-cokelat text-xs font-bold rounded-tag">
                                {{ $wisata->kategori?->nama ?? 'Cagar Budaya' }}
                            </span>
                            <span class="text-xs text-putih/80 font-mono">
                                {{ number_format($wisata->latitude, 6) }}, {{ number_format($wisata->longitude, 6) }}
                            </span>
                        </div>
                        <h1 class="text-3xl sm:text-5xl font-bold font-papan text-putih leading-tight">
                            {{ $wisata->nama }}
                        </h1>
                        <p class="text-putih/90 text-sm mt-2 flex items-center gap-1.5">
                            <span>📍</span>
                            <span>{{ $wisata->alamat }}</span>
                        </p>
                    </div>

                    <!-- Tombol Cek Rute -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a
                            href="{{ $wisata->google_maps_url }}"
                            target="_blank"
                            class="h-[48px] px-6 bg-putih text-aspal border-2 border-aspal font-bold text-sm rounded-kontrol hover:bg-beton no-underline flex items-center justify-center gap-2 whitespace-nowrap"
                        >
                            <span>🧭</span>
                            <span>Buka Rute di Google Maps</span>
                            <span>↗</span>
                        </a>
                    </div>
                </div>
            </x-papan>

            <!-- Bar Hitung Jarak Real-time (LBS) -->
            <div class="p-4 bg-putih border-2 border-aspal rounded-papan flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-sm text-aspal">
                    <span class="text-lg">📍</span>
                    <span class="font-semibold" x-text="distText ? distText : 'Ketahui jarak langsung dari posisi Anda ke tempat wisata ini'"></span>
                </div>
                <button
                    type="button"
                    @click="calcDistance"
                    class="h-[38px] px-4 bg-hijau text-putih font-bold text-xs rounded-kontrol hover:opacity-95 cursor-pointer whitespace-nowrap"
                >
                    <span x-show="!geoLoading">Hitung Jarak (LBS GPS)</span>
                    <span x-show="geoLoading">Mendeteksi Posisi...</span>
                </button>
            </div>

            <!-- Konten Dua Kolom: Informasi Utama & Sidebar Peta/Fasilitas -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Kolom Kiri (8 Col): Foto & Deskripsi Lengkap -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Foto Utama -->
                    <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
                        @if($wisata->foto_utama)
                            <img src="{{ $wisata->foto_url }}" alt="{{ $wisata->nama }}" class="w-full h-[400px] object-cover">
                        @else
                            <div class="w-full h-72 bg-beton flex items-center justify-center text-abu font-semibold">
                                Foto utama belum tersedia
                            </div>
                        @endif
                    </div>

                    <!-- Uraian Sejarah & Deskripsi -->
                    <div class="bg-putih border-2 border-aspal rounded-papan p-6 sm:p-8 space-y-4">
                        <h2 class="text-2xl font-bold font-papan text-aspal border-b border-beton pb-2">
                            Sejarah & Daya Tarik Wisata
                        </h2>

                        <div class="prose max-w-none text-aspal text-base leading-relaxed whitespace-pre-line">
                            {{ $wisata->deskripsi }}
                        </div>
                    </div>

                    <!-- Fasilitas Penunjang yang Tersedia di Objek Wisata Ini -->
                    <div class="bg-putih border-2 border-aspal rounded-papan p-6 sm:p-8 space-y-4">
                        <h2 class="text-2xl font-bold font-papan text-aspal border-b border-beton pb-2">
                            Fasilitas di Lokasi Ini
                        </h2>

                        @if($wisata->fasilitas->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($wisata->fasilitas as $f)
                                    <div class="p-4 border-2 border-beton rounded-kontrol flex items-start gap-3 bg-beton/20 hover:border-aspal">
                                        <div class="w-10 h-10 rounded-kontrol bg-biru text-putih flex items-center justify-center text-base font-bold flex-shrink-0">
                                            🚻
                                        </div>
                                        <div>
                                            <div class="font-bold text-aspal text-[15px]">{{ $f->nama }}</div>
                                            <div class="text-xs text-biru font-semibold">{{ $f->jenis?->nama }}</div>
                                            <div class="text-xs text-abu mt-1">{{ $f->keterangan_lokasi }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-abu py-2">
                                Belum ada catatan fasilitas khusus yang terhubung di objek wisata ini. Wisatawan dapat memanfaatkan fasilitas umum desa di sekitarnya.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Kolom Kanan (4 Col): Peta Leaflet & Informasi Praktis -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Kartu Informasi Praktis -->
                    <div class="bg-putih border-2 border-aspal rounded-papan p-6 space-y-4">
                        <h3 class="text-lg font-bold font-papan text-aspal border-b border-beton pb-2">
                            Informasi Kunjungan
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <span class="text-base">🎟️</span>
                                <div>
                                    <div class="font-bold text-aspal">Harga Tiket Masuk</div>
                                    <div class="text-xs text-abu">{{ $wisata->harga_tiket ?: 'Gratis / Sukarela' }}</div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="text-base">⏰</span>
                                <div>
                                    <div class="font-bold text-aspal">Jam Operasional</div>
                                    <div class="text-xs text-abu">{{ $wisata->jam_operasional ?: '08.00 - 17.00 WIB' }}</div>
                                </div>
                            </div>

                            @if($wisata->kontak)
                                <div class="flex items-start gap-3">
                                    <span class="text-base">📞</span>
                                    <div>
                                        <div class="font-bold text-aspal">Narahubung / Pengelola</div>
                                        <div class="text-xs text-abu">
                                            <a
                                                href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $wisata->kontak)) }}"
                                                target="_blank"
                                                class="text-aspal underline font-bold hover:text-cokelat"
                                            >
                                                {{ $wisata->kontak }} (WhatsApp) &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-start gap-3">
                                <span class="text-base">🏛️</span>
                                <div>
                                    <div class="font-bold text-aspal">Status Kawasan</div>
                                    <div class="text-xs text-hijau font-bold">Cagar Budaya Resmi</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Peta Mini Lokasi Leaflet -->
                    <div class="bg-putih border-2 border-aspal rounded-papan p-6 space-y-3">
                        <h3 class="text-lg font-bold font-papan text-aspal">Titik Lokasi Peta</h3>
                        
                        <div id="mini-map" class="h-60 w-full border-2 border-aspal rounded-kontrol z-10"></div>

                        <div class="pt-2">
                            <a
                                href="{{ $wisata->google_maps_url }}"
                                target="_blank"
                                class="w-full h-[40px] px-4 bg-aspal text-putih font-bold text-xs rounded-kontrol hover:bg-black no-underline flex items-center justify-center gap-1.5"
                            >
                                <span>Buka Navigasi Rute</span>
                                <span>↗</span>
                            </a>
                        </div>
                    </div>

                    <!-- Rekomendasi Objek Wisata Lainnya -->
                    @if($wisataLain->count() > 0)
                        <div class="bg-putih border-2 border-aspal rounded-papan p-6 space-y-3">
                            <h3 class="text-lg font-bold font-papan text-aspal border-b border-beton pb-2">
                                Objek Wisata Lainnya
                            </h3>

                            <div class="space-y-3">
                                @foreach($wisataLain as $wl)
                                    <a href="{{ route('wisata.show', $wl->slug) }}" class="block p-2.5 rounded-kontrol border border-beton hover:border-aspal no-underline group">
                                        <div class="font-bold text-sm text-aspal group-hover:text-cokelat leading-snug">
                                            {{ $wl->nama }}
                                        </div>
                                        <div class="text-[11px] text-abu mt-0.5">{{ $wl->kategori?->nama }}</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Script Mini Map Leaflet -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $wisata->latitude }};
            const lng = {{ $wisata->longitude }};

            if (typeof L === 'undefined') return;

            const map = L.map('mini-map', {
                center: [lat, lng],
                zoom: 16,
                scrollWheelZoom: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            L.marker([lat, lng])
                .addTo(map)
                .bindPopup("<strong>{{ addslashes($wisata->nama) }}</strong><br>{{ addslashes($wisata->alamat) }}")
                .openPopup();
        });
    </script>
</x-portal-layout>
