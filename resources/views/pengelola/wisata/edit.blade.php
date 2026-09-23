<x-app-layout>
    <x-slot:title>Ubah Objek Wisata: {{ $wisata->nama }}</x-slot:title>
    <x-slot:header>Edit Objek Wisata</x-slot:header>

    <div class="space-y-6 max-w-5xl mx-auto">
        <!-- 1. Banner Eksekutif Modern (Slate 900) -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Penyuntingan Destinasi Wisata</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Edit Objek Wisata: {{ $wisata->nama }}
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed font-normal">
                    Perbarui data cagar budaya, informasi kontak, foto utama, maupun titik koordinat lokasi LBS.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a
                    href="{{ route('pengelola.wisata.index') }}"
                    class="h-10 px-4 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-semibold text-xs sm:text-sm transition-all inline-flex items-center gap-2 no-underline"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>

        <!-- 2. Formulir Edit Objek Wisata -->
        <form
            method="POST"
            action="{{ route('pengelola.wisata.update', $wisata) }}"
            enctype="multipart/form-data"
            class="space-y-6"
            x-data="{
                previewUrl: null,
                handleFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                    }
                }
            }"
        >
            @csrf
            @method('PUT')

            <!-- Bagian 1: Informasi Pokok -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-sm space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold tracking-tight text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <span>Informasi Dasar & Deskripsi</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Identitas utama, kategori, kontak, dan operasional objek wisata</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Objek Wisata <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="{{ old('nama', $wisata->nama) }}"
                            required
                            placeholder="Contoh: Rumah Panggung Tua Buay Pemuka Pangeran"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                        >
                        <x-input-error :messages="$errors->get('nama')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="kategori_wisata_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kategori Wisata <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="kategori_wisata_id"
                            name="kategori_wisata_id"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                        >
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_wisata_id', $wisata->kategori_wisata_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kategori_wisata_id')" class="mt-1.5" />
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Alamat Lengkap / Petunjuk Lokasi <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="alamat"
                        name="alamat"
                        value="{{ old('alamat', $wisata->alamat) }}"
                        required
                        class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                    >
                    <x-input-error :messages="$errors->get('alamat')" class="mt-1.5" />
                </div>

                <div>
                    <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Deskripsi Lengkap & Sejarah Objek Wisata <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="5"
                        required
                        class="p-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400 leading-relaxed"
                    >{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-1.5" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="jam_operasional" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jam Operasional (Opsional)
                        </label>
                        <input
                            type="text"
                            id="jam_operasional"
                            name="jam_operasional"
                            value="{{ old('jam_operasional', $wisata->jam_operasional) }}"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                        >
                        <x-input-error :messages="$errors->get('jam_operasional')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="harga_tiket" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Harga Tiket Masuk (Opsional)
                        </label>
                        <input
                            type="text"
                            id="harga_tiket"
                            name="harga_tiket"
                            value="{{ old('harga_tiket', $wisata->harga_tiket) }}"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400"
                        >
                        <x-input-error :messages="$errors->get('harga_tiket')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="kontak" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kontak Narahubung / WA (Opsional)
                        </label>
                        <input
                            type="text"
                            id="kontak"
                            name="kontak"
                            value="{{ old('kontak', $wisata->kontak) }}"
                            placeholder="Contoh: 081234567890"
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all placeholder-slate-400 font-mono"
                        >
                        <x-input-error :messages="$errors->get('kontak')" class="mt-1.5" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Status Publikasi <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                        >
                            <option value="aktif" {{ old('status', $wisata->status) === 'aktif' ? 'selected' : '' }}>Aktif (Tayang di Web Publik)</option>
                            <option value="pending" {{ old('status', $wisata->status) === 'pending' ? 'selected' : '' }}>Pending (Konsep/Draft)</option>
                            <option value="nonaktif" {{ old('status', $wisata->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Arsip)</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="foto_utama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Ganti Foto Utama (Opsional, Maksimal 3 MB)
                        </label>
                        <input
                            type="file"
                            id="foto_utama"
                            name="foto_utama"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            @change="handleFile"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer"
                        >
                        <x-input-error :messages="$errors->get('foto_utama')" class="mt-1.5" />

                        <!-- Foto Saat Ini vs Foto Baru -->
                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if($wisata->foto_utama)
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                                    <span class="block text-[11px] font-semibold text-slate-500">Foto Saat Ini:</span>
                                    <img src="{{ $wisata->foto_url }}" alt="{{ $wisata->nama }}" class="w-full h-36 object-cover rounded-xl border border-slate-200 shadow-2xs">
                                </div>
                            @endif

                            <template x-if="previewUrl">
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
                                    <span class="block text-[11px] font-semibold text-emerald-700">Foto Pengganti Terpilih:</span>
                                    <img :src="previewUrl" alt="Pratinjau Foto" class="w-full h-36 object-cover rounded-xl border border-emerald-300 shadow-2xs">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Titik Koordinat GPS & Leaflet Map Picker (LBS) -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-bold tracking-tight text-slate-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-sky-600"></span>
                            <span>Penentuan Titik Koordinat Lokasi (LBS)</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Klik atau geser pin penanda pada peta satelit untuk memperbarui koordinat GPS lokasi objek wisata.
                        </p>
                    </div>
                    <button
                        type="button"
                        id="btn-geolocate"
                        class="h-9 px-3.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-xl cursor-pointer flex items-center gap-1.5 self-start sm:self-auto transition-all shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Gunakan Lokasi GPS Saya</span>
                    </button>
                </div>

                <!-- Kontainer Peta Leaflet Citra Satelit Esri -->
                <div class="relative">
                    <div id="map-picker" class="h-[360px] w-full border border-slate-300 rounded-xl z-10 shadow-xs overflow-hidden"></div>
                </div>

                <!-- Input Numerik Latitude & Longitude -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label for="latitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Latitude (Garis Lintang) <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="latitude"
                            name="latitude"
                            type="number"
                            step="any"
                            value="{{ old('latitude', $wisata->latitude) }}"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full font-mono text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                        <x-input-error :messages="$errors->get('latitude')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Longitude (Garis Bujur) <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="longitude"
                            name="longitude"
                            type="number"
                            step="any"
                            value="{{ old('longitude', $wisata->longitude) }}"
                            required
                            class="h-11 px-3.5 border border-slate-300 rounded-xl w-full font-mono text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                        <x-input-error :messages="$errors->get('longitude')" class="mt-1.5" />
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Simpan Perubahan -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a
                    href="{{ route('pengelola.wisata.index') }}"
                    class="h-11 px-6 bg-white border border-slate-200 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl hover:bg-slate-50 transition-all no-underline flex items-center justify-center cursor-pointer shadow-xs"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="h-11 px-7 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-emerald-600/20 transition-all cursor-pointer flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Script Leaflet Coordinate Picker (Citra Satelit Esri) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const btnGeolocate = document.getElementById('btn-geolocate');

            let currentLat = parseFloat(latInput.value) || -4.540583;
            let currentLng = parseFloat(lngInput.value) || 104.664984;

            if (typeof L === 'undefined') {
                console.error('Leaflet JS is not loaded.');
                return;
            }

            // Inisialisasi peta dengan citra satelit Esri resolusi tinggi
            const map = L.map('map-picker').setView([currentLat, currentLng], 16);

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri',
                maxZoom: 19
            }).addTo(map);

            // Marker dengan drag enabled
            const marker = L.marker([currentLat, currentLng], {
                draggable: true
            }).addTo(map);

            function updateInputs(lat, lng) {
                latInput.value = parseFloat(lat).toFixed(6);
                lngInput.value = parseFloat(lng).toFixed(6);
            }

            // Saat marker digeser
            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });

            // Saat peta diklik
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

            // Saat input diubah secara manual oleh pengguna
            function onInputChange() {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.panTo([lat, lng]);
                }
            }

            latInput.addEventListener('input', onInputChange);
            lngInput.addEventListener('input', onInputChange);

            // Tombol deteksi lokasi GPS browser
            if (btnGeolocate && navigator.geolocation) {
                btnGeolocate.addEventListener('click', function() {
                    btnGeolocate.disabled = true;
                    btnGeolocate.textContent = 'Mendeteksi koordinat...';

                    navigator.geolocation.getCurrentPosition(
                        function(pos) {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            marker.setLatLng([lat, lng]);
                            map.setView([lat, lng], 17);
                            updateInputs(lat, lng);
                            btnGeolocate.disabled = false;
                            btnGeolocate.innerHTML = '<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg><span>Lokasi GPS Terdeteksi</span>';
                        },
                        function(err) {
                            alert('Gagal mendeteksi lokasi GPS. Pastikan izin lokasi browser telah diaktifkan.');
                            btnGeolocate.disabled = false;
                            btnGeolocate.innerHTML = '<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg><span>Gunakan Lokasi GPS Saya</span>';
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                });
            }
        });
    </script>
</x-app-layout>
