<x-portal-layout>
    <x-slot:title>Peta Interaktif & LBS — Desa Wisata Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Peta interaktif berbasis Location Based Services (LBS). Jelajahi sebaran objek wisata rumah adat dan fasilitas penunjang di Kampung Gedung Batin.</x-slot:description>

    <div class="py-6 md:py-10 space-y-6 max-w-[1240px] mx-auto px-4 sm:px-6">
        <!-- Papan Hijau Judul Peta LBS -->
        <x-papan warna="hijau" class="p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="px-2.5 py-0.5 bg-putih text-hijau text-xs font-bold rounded-tag mb-1 inline-block">
                        Location Based Services (LBS)
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-bold font-papan text-putih">
                        Peta Sebaran Wisata & Fasilitas Desa
                    </h1>
                    <p class="text-putih/90 text-sm mt-1 max-w-xl">
                        Klik pin pada peta untuk melihat informasi objek wisata atau gunakan GPS untuk mencari destinasi terdekat dari titik Anda berada.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        id="btn-find-me"
                        class="h-[48px] px-5 bg-putih text-aspal border-2 border-aspal font-bold text-sm rounded-kontrol hover:bg-beton cursor-pointer flex items-center gap-2 whitespace-nowrap shadow-none"
                    >
                        <span>📍</span>
                        <span id="btn-find-text">Temukan Lokasi Saya</span>
                    </button>
                </div>
            </div>
        </x-papan>

        <!-- Bar Kontrol Filter Layer Peta -->
        <div class="bg-putih border-2 border-aspal rounded-papan p-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4 text-sm font-semibold text-aspal">
                <span class="text-xs font-bold text-abu uppercase tracking-wider">Tampilkan:</span>
                
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="toggle-wisata" checked class="w-4 h-4 rounded border-2 border-aspal text-cokelat focus:ring-0">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-cokelat inline-block"></span>
                        <span>Objek Wisata ({{ count($wisataList) }})</span>
                    </span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="toggle-fasilitas" checked class="w-4 h-4 rounded border-2 border-aspal text-biru focus:ring-0">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-biru inline-block"></span>
                        <span>Fasilitas Desa ({{ count($fasilitasList) }})</span>
                    </span>
                </label>
            </div>

            <!-- Rekomendasi Terdekat (akan terisi jika GPS aktif) -->
            <div id="closest-dest-banner" class="hidden text-xs bg-hijau/15 border border-hijau text-hijau-gelap font-bold px-3 py-1.5 rounded-kontrol flex items-center gap-2">
                <span>🎯</span>
                <span id="closest-dest-text"></span>
            </div>
        </div>

        <!-- Wadah Peta Leaflet Layar Penuh -->
        <div class="bg-putih border-2 border-aspal rounded-papan overflow-hidden">
            <div id="full-map" class="w-full h-[600px] z-10"></div>
        </div>

        <!-- Legenda Peta (DESIGN.md 7.11) -->
        <div class="p-4 bg-putih border-2 border-aspal rounded-papan text-xs text-aspal flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded bg-cokelat border border-aspal inline-block"></span>
                    <span class="font-semibold">Cagar Budaya / Rumah Panggung Adat</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded bg-biru border border-aspal inline-block"></span>
                    <span class="font-semibold">Fasilitas Desa (Musala, Toilet, Parkir)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-hijau border-2 border-putih inline-block animate-pulse"></span>
                    <span class="font-semibold">Posisi Pengguna (GPS)</span>
                </div>
            </div>
            <div class="text-abu font-mono">
                Pusat Koordinat Kampung Gedung Batin: -4.540583, 104.664984
            </div>
        </div>
    </div>

    <!-- Script Leaflet LBS Full Interactive Map -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wisataData = @json($wisataList);
            const fasilitasData = @json($fasilitasList);

            const defaultLat = -4.540583;
            const defaultLng = 104.664984;

            if (typeof L === 'undefined') return;

            // Inisialisasi Map
            const map = L.map('full-map').setView([defaultLat, defaultLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19
            }).addTo(map);

            // Layer Groups
            const wisataLayer = L.layerGroup().addTo(map);
            const fasilitasLayer = L.layerGroup().addTo(map);
            let userLocationMarker = null;
            let userCoords = null;

            // Haversine Formula (km)
            function haversine(lat1, lon1, lat2, lon2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a =
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c;
            }

            // Render Wisata Markers
            function renderWisataMarkers() {
                wisataLayer.clearLayers();

                wisataData.forEach(w => {
                    let distanceHtml = '';
                    if (userCoords) {
                        const dist = haversine(userCoords.lat, userCoords.lng, w.latitude, w.longitude);
                        const distFormatted = dist < 1 ? Math.round(dist * 1000) + ' m' : dist.toFixed(2) + ' km';
                        distanceHtml = `<div class="mt-1 text-xs font-bold text-hijau">📍 Jarak: ${distFormatted} dari Anda</div>`;
                    }

                    const popupContent = `
                        <div style="min-width: 220px; font-family: inherit; color: #22282E;">
                            ${w.foto_url ? `<img src="${w.foto_url}" style="width: 100%; height: 110px; object-fit: cover; border-radius: 4px; border: 1px solid #22282E; margin-bottom: 8px;">` : ''}
                            <span style="background: #6B3A22; color: #fff; padding: 2px 6px; font-size: 10px; font-weight: bold; border-radius: 2px;">
                                ${w.kategori}
                            </span>
                            <h4 style="font-size: 15px; font-weight: bold; margin: 6px 0 3px 0; color: #22282E;">${w.nama}</h4>
                            <p style="font-size: 11px; color: #59626B; margin: 0 0 6px 0;">${w.alamat}</p>
                            ${distanceHtml}
                            <div style="margin-top: 8px; display: flex; gap: 4px;">
                                <a href="${w.detail_url}" style="flex: 1; text-align: center; background: #22282E; color: #fff; padding: 6px; font-size: 11px; font-weight: bold; text-decoration: none; border-radius: 4px;">
                                    Detail
                                </a>
                                <a href="${w.google_maps_url}" target="_blank" style="text-align: center; background: #E4E7E9; color: #22282E; padding: 6px 8px; font-size: 11px; font-weight: bold; text-decoration: none; border-radius: 4px; border: 1px solid #22282E;">
                                    Rute ↗
                                </a>
                            </div>
                        </div>
                    `;

                    const marker = L.marker([w.latitude, w.longitude]).bindPopup(popupContent);
                    wisataLayer.addLayer(marker);
                });
            }

            // Render Fasilitas Markers
            function renderFasilitasMarkers() {
                fasilitasLayer.clearLayers();

                fasilitasData.forEach(f => {
                    const popupContent = `
                        <div style="min-width: 180px; font-family: inherit; color: #22282E;">
                            <span style="background: #0B5EA8; color: #fff; padding: 2px 6px; font-size: 10px; font-weight: bold; border-radius: 2px;">
                                ${f.jenis}
                            </span>
                            <h4 style="font-size: 14px; font-weight: bold; margin: 6px 0 2px 0;">${f.nama}</h4>
                            <p style="font-size: 11px; color: #59626B; margin: 0;">${f.lokasi}</p>
                            ${f.objek_wisata ? `<div style="font-size: 10px; color: #6B3A22; font-weight: bold; margin-top: 4px;">Objek: ${f.objek_wisata}</div>` : '<div style="font-size: 10px; color: #0F7A3C; font-weight: bold; margin-top: 4px;">Fasilitas Umum Desa</div>'}
                        </div>
                    `;

                    const marker = L.circleMarker([f.latitude, f.longitude], {
                        radius: 8,
                        fillColor: '#0B5EA8',
                        color: '#22282E',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    }).bindPopup(popupContent);

                    fasilitasLayer.addLayer(marker);
                });
            }

            // Inisialisasi marker awal
            renderWisataMarkers();
            renderFasilitasMarkers();

            // Toggle Layer Checkbox
            document.getElementById('toggle-wisata').addEventListener('change', function(e) {
                if (e.target.checked) {
                    map.addLayer(wisataLayer);
                } else {
                    map.removeLayer(wisataLayer);
                }
            });

            document.getElementById('toggle-fasilitas').addEventListener('change', function(e) {
                if (e.target.checked) {
                    map.addLayer(fasilitasLayer);
                } else {
                    map.removeLayer(fasilitasLayer);
                }
            });

            // Tombol "Temukan Lokasi Saya" (LBS GPS Geolocation)
            const btnFind = document.getElementById('btn-find-me');
            const btnFindText = document.getElementById('btn-find-text');
            const bannerClosest = document.getElementById('closest-dest-banner');
            const textClosest = document.getElementById('closest-dest-text');

            btnFind.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    alert('Perangkat Anda tidak mendukung fitur lokasi.');
                    return;
                }

                btnFindText.textContent = 'Mendeteksi...';
                btnFind.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        userCoords = { lat, lng };

                        btnFindText.textContent = 'Lokasi Terdeteksi';
                        btnFind.disabled = false;

                        // Tambahkan/perbarui marker user
                        if (userLocationMarker) {
                            userLocationMarker.setLatLng([lat, lng]);
                        } else {
                            userLocationMarker = L.circleMarker([lat, lng], {
                                radius: 10,
                                fillColor: '#0F7A3C',
                                color: '#FFFFFF',
                                weight: 3,
                                opacity: 1,
                                fillOpacity: 0.95
                            }).addTo(map).bindPopup('<strong>Posisi Anda Saat Ini</strong>');
                        }

                        // Re-render popup wisata dengan jarak terhitung
                        renderWisataMarkers();

                        // Cari objek wisata terdekat
                        let closestWisata = null;
                        let minDistance = Infinity;

                        wisataData.forEach(w => {
                            const d = haversine(lat, lng, w.latitude, w.longitude);
                            if (d < minDistance) {
                                minDistance = d;
                                closestWisata = w;
                            }
                        });

                        if (closestWisata) {
                            const distStr = minDistance < 1 ? Math.round(minDistance * 1000) + ' m' : minDistance.toFixed(2) + ' km';
                            textClosest.innerHTML = `Destinasi terdekat: <strong>${closestWisata.nama}</strong> (${distStr}) &bull; <a href="${closestWisata.detail_url}" class="underline font-bold">Buka Detail</a>`;
                            bannerClosest.classList.remove('hidden');
                        }

                        // Pan ke lokasi pengguna
                        map.setView([lat, lng], 16);
                        userLocationMarker.openPopup();
                    },
                    function(err) {
                        alert('Gagal mendeteksi lokasi GPS. Pastikan izin lokasi browser Anda aktif.');
                        btnFindText.textContent = 'Temukan Lokasi Saya';
                        btnFind.disabled = false;
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            });
        });
    </script>
</x-portal-layout>
