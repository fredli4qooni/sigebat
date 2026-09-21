<x-app-layout>
    <x-slot:title>Ubah Objek Wisata: {{ $wisata->nama }}</x-slot:title>
    <x-slot:header>Edit Objek Wisata</x-slot:header>

    <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Papan Cokelat Judul Halaman -->
        <x-papan warna="cokelat" class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold font-papan text-putih">Edit Objek Wisata: {{ $wisata->nama }}</h2>
                    <p class="text-putih/90 text-sm mt-1">
                        Perbarui data cagar budaya, informasi kontak, foto utama, maupun titik koordinat lokasi LBS.
                    </p>
                </div>
                <a
                    href="{{ route('pengelola.wisata.index') }}"
                    class="px-3.5 py-2 bg-putih/15 border border-putih/30 text-putih font-bold text-xs rounded-kontrol hover:bg-putih/25 no-underline"
                >
                    &larr; Kembali ke daftar
                </a>
            </div>
        </x-papan>

        <!-- Form Edit Wisata -->
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
            <div class="bg-putih border-2 border-aspal rounded-papan p-6 space-y-4">
                <h3 class="text-lg font-bold font-papan text-aspal border-b border-beton pb-2">Informasi dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="nama" value="Nama objek wisata" />
                        <x-text-input id="nama" name="nama" type="text" :value="old('nama', $wisata->nama)" required />
                        <x-input-error :messages="$errors->get('nama')" />
                    </div>

                    <div>
                        <x-input-label for="kategori_wisata_id" value="Kategori wisata" />
                        <select
                            id="kategori_wisata_id"
                            name="kategori_wisata_id"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_wisata_id', $wisata->kategori_wisata_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kategori_wisata_id')" />
                    </div>
                </div>

                <div>
                    <x-input-label for="alamat" value="Alamat lengkap / petunjuk lokasi" />
                    <x-text-input id="alamat" name="alamat" type="text" :value="old('alamat', $wisata->alamat)" required />
                    <x-input-error :messages="$errors->get('alamat')" />
                </div>

                <div>
                    <x-input-label for="deskripsi" value="Deskripsi lengkap dan sejarah objek wisata" />
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="5"
                        required
                        class="p-3.5 border-2 border-aspal rounded-kontrol w-full text-sm text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                    >{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="jam_operasional" value="Jam operasional" />
                        <x-text-input id="jam_operasional" name="jam_operasional" type="text" :value="old('jam_operasional', $wisata->jam_operasional)" />
                        <x-input-error :messages="$errors->get('jam_operasional')" />
                    </div>

                    <div>
                        <x-input-label for="harga_tiket" value="Harga tiket masuk" />
                        <x-text-input id="harga_tiket" name="harga_tiket" type="text" :value="old('harga_tiket', $wisata->harga_tiket)" />
                        <x-input-error :messages="$errors->get('harga_tiket')" />
                    </div>

                    <div>
                        <x-input-label for="kontak" value="Kontak narahubung / WhatsApp" />
                        <x-text-input id="kontak" name="kontak" type="text" :value="old('kontak', $wisata->kontak)" />
                        <x-input-error :messages="$errors->get('kontak')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <x-input-label for="status" value="Status publikasi" />
                        <select
                            id="status"
                            name="status"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full text-sm font-semibold text-aspal bg-putih focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                            <option value="aktif" {{ old('status', $wisata->status) === 'aktif' ? 'selected' : '' }}>Aktif (Tayang di Web Publik)</option>
                            <option value="pending" {{ old('status', $wisata->status) === 'pending' ? 'selected' : '' }}>Pending (Konsep/Draft)</option>
                            <option value="nonaktif" {{ old('status', $wisata->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Arsip)</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" />
                    </div>

                    <div>
                        <x-input-label for="foto_utama" value="Ubah foto utama (Biarkan kosong jika tidak diganti)" />
                        <input
                            type="file"
                            id="foto_utama"
                            name="foto_utama"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            @change="handleFile"
                            class="block w-full text-sm text-aspal file:mr-4 file:py-2.5 file:px-4 file:rounded-kontrol file:border-2 file:border-aspal file:text-xs file:font-bold file:bg-beton file:text-aspal hover:file:bg-abu/20 cursor-pointer"
                        >
                        <x-input-error :messages="$errors->get('foto_utama')" />

                        <!-- Foto Saat Ini / Pratinjau Foto Baru -->
                        <div class="mt-3">
                            <template x-if="previewUrl">
                                <div class="p-2 bg-beton/40 border border-aspal rounded-kontrol">
                                    <span class="block text-[11px] font-bold text-abu mb-1">Pratinjau foto baru:</span>
                                    <img :src="previewUrl" alt="Pratinjau Baru" class="w-full h-40 object-cover rounded-kontrol border border-aspal">
                                </div>
                            </template>
                            <template x-if="!previewUrl && {{ $wisata->foto_utama ? 'true' : 'false' }}">
                                <div class="p-2 bg-beton/40 border border-beton rounded-kontrol">
                                    <span class="block text-[11px] font-bold text-abu mb-1">Foto aktif saat ini:</span>
                                    <img src="{{ $wisata->foto_url }}" alt="{{ $wisata->nama }}" class="w-full h-40 object-cover rounded-kontrol border border-aspal">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Titik Koordinat GPS & Leaflet Map Picker (LBS) -->
            <div class="bg-putih border-2 border-aspal rounded-papan p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-beton pb-2">
                    <div>
                        <h3 class="text-lg font-bold font-papan text-aspal">Penentuan Titik Koordinat Lokasi (LBS)</h3>
                        <p class="text-xs text-abu">
                            Geser pin penanda pada peta atau ubah angka koordinat untuk menyesuaikan letak lokasi.
                        </p>
                    </div>
                    <button
                        type="button"
                        id="btn-geolocate"
                        class="h-9 px-3.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-xl cursor-pointer flex items-center gap-1.5 self-start sm:self-auto transition-all shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Gunakan Lokasi GPS Saya</span>
                    </button>
                </div>

                <!-- Leaflet Interactive Container -->
                <div class="relative">
                    <div id="map-picker" class="h-[360px] w-full border-2 border-aspal rounded-kontrol z-10"></div>
                </div>

                <!-- Input Numeric Latitude & Longitude -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <x-input-label for="latitude" value="Latitude (Garis Lintang)" />
                        <input
                            id="latitude"
                            name="latitude"
                            type="number"
                            step="any"
                            value="{{ old('latitude', $wisata->latitude) }}"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full font-mono text-sm text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                        <x-input-error :messages="$errors->get('latitude')" />
                    </div>

                    <div>
                        <x-input-label for="longitude" value="Longitude (Garis Bujur)" />
                        <input
                            id="longitude"
                            name="longitude"
                            type="number"
                            step="any"
                            value="{{ old('longitude', $wisata->longitude) }}"
                            required
                            class="h-[48px] px-3.5 border-2 border-aspal rounded-kontrol w-full font-mono text-sm text-aspal focus:outline-none focus:ring-0 focus:border-aspal"
                        >
                        <x-input-error :messages="$errors->get('longitude')" />
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Simpan -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a
                    href="{{ route('pengelola.wisata.index') }}"
                    class="h-[48px] px-6 bg-putih border-2 border-aspal text-aspal font-bold text-sm rounded-kontrol hover:bg-beton no-underline flex items-center justify-center"
                >
                    Batal
                </a>
                <x-primary-button class="h-[48px] px-8">
                    Simpan Perubahan
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Script Leaflet Coordinate Picker -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const btnGeolocate = document.getElementById('btn-geolocate');

            let currentLat = parseFloat(latInput.value) || -4.540583;
            let currentLng = parseFloat(lngInput.value) || 104.664984;

            if (typeof L === 'undefined') {
                console.error('Leaflet is not loaded.');
                return;
            }

            const map = L.map('map-picker').setView([currentLat, currentLng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            const marker = L.marker([currentLat, currentLng], {
                draggable: true
            }).addTo(map);

            function updateInputs(lat, lng) {
                latInput.value = parseFloat(lat).toFixed(6);
                lngInput.value = parseFloat(lng).toFixed(6);
            }

            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });

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
