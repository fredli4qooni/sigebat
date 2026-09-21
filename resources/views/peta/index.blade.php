<x-portal-layout>
    <x-slot:title>Peta Interaktif & LBS — Desa Wisata Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Peta interaktif berbasis Location Based Services (LBS). Jelajahi sebaran objek wisata rumah adat dan fasilitas penunjang di Kampung Gedung Batin.</x-slot:description>

    <div class="py-6 md:py-10 space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Peta LBS Modern -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 text-white shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">
                        Location Based Services (LBS)
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                        Peta Sebaran Wisata & Fasilitas Desa
                    </h1>
                    <p class="text-slate-300 text-sm leading-relaxed font-normal">
                        Jelajahi titik cagar budaya dan sarana pendukung Kampung Gedung Batin, atau aktifkan GPS untuk mengukur jarak serta menemukan destinasi terdekat.
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <button
                        type="button"
                        id="btn-find-me"
                        class="h-12 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md hover:shadow-emerald-600/30 transition-all cursor-pointer flex items-center gap-2.5 whitespace-nowrap"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span id="btn-find-text">Temukan Lokasi Saya</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bar Kontrol Filter Layer Peta & Banner Terdekat -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-6 text-sm font-medium text-slate-700">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tampilkan Layer:</span>
                
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" id="toggle-wisata" checked class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500/20 border-slate-300">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-800 inline-block"></span>
                        <span class="font-semibold text-slate-800">Cagar Budaya ({{ count($wisataList) }})</span>
                    </span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" id="toggle-fasilitas" checked class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500/20 border-slate-300">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-600 inline-block"></span>
                        <span class="font-semibold text-slate-800">Fasilitas Desa ({{ count($fasilitasList) }})</span>
                    </span>
                </label>
            </div>

            <!-- Rekomendasi Terdekat (terisi jika GPS aktif) -->
            <div id="closest-dest-banner" class="hidden text-xs bg-emerald-50 border border-emerald-200 text-emerald-900 font-semibold px-4 py-2 rounded-xl flex items-center gap-2 shadow-xs">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span id="closest-dest-text"></span>
            </div>
        </div>

        <!-- Wadah Peta Leaflet Layar Penuh Modern -->
        <div class="card-modern p-0 overflow-hidden shadow-md">
            <div id="full-map" class="w-full h-[620px] z-10"></div>
        </div>

        <!-- Legenda Peta Modern -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm text-xs text-slate-600 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-800 inline-block"></span>
                    <span class="font-medium text-slate-800">Cagar Budaya / Rumah Panggung Adat</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-sky-600 inline-block"></span>
                    <span class="font-medium text-slate-800">Fasilitas Desa (Musala, Toilet, Parkir)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-600 inline-block animate-pulse"></span>
                    <span class="font-medium text-slate-800">Posisi Pengguna (GPS Terdeteksi)</span>
                </div>
            </div>
            <div class="text-slate-400 font-mono">
                Pusat Wilayah: -4.540583, 104.664984
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
            const map = L.map('full-map', {
                center: [defaultLat, defaultLng],
                zoom: 16,
                zoomControl: true,
                attributionControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let userCoords = null;
            let userLocationMarker = null;

            // Layer Groups
            const wisataLayer = L.layerGroup().addTo(map);
            const fasilitasLayer = L.layerGroup().addTo(map);

            // Fungsi Haversine Formula
            function haversine(lat1, lon1, lat2, lon2) {
                const R = 6371; // km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a =
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c;
            }

            // Custom Icon Factory
            function createPinIcon(colorHex, label) {
                return L.divIcon({
                    className: 'custom-pin-icon',
                    html: `<div style="
                        background-color: ${colorHex};
                        color: #FFFFFF;
                        padding: 4px 8px;
                        border-radius: 8px;
                        font-weight: 700;
                        font-size: 11px;
                        box-shadow: 0 4px 10px rgba(0,0,0,0.25);
                        border: 2px solid #FFFFFF;
                        white-space: nowrap;
                        display: inline-flex;
                        align-items: center;
                        gap: 4px;
                    ">
                        <span>${label}</span>
                    </div>`,
                    iconSize: [100, 30],
                    iconAnchor: [50, 32],
                    popupAnchor: [0, -32]
                });
            }

            // Render Wisata Markers
            function renderWisataMarkers() {
                wisataLayer.clearLayers();

                wisataData.forEach(w => {
                    let distHtml = '';
                    if (userCoords) {
                        const dist = haversine(userCoords.lat, userCoords.lng, w.latitude, w.longitude);
                        const distText = dist < 1 ? Math.round(dist * 1000) + ' meter' : dist.toFixed(2) + ' km';
                        distHtml = `<div style="margin-top: 6px; padding: 4px 8px; background: #ECFDF5; color: #047857; font-weight: 600; font-size: 11px; border-radius: 6px; display: inline-block;">Jarak: ${distText} dari Anda</div>`;
                    }

                    const popupContent = `
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; min-width: 220px; max-width: 280px; padding: 4px;">
                            ${w.foto_url ? `<img src="${w.foto_url}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;" alt="${w.nama}">` : ''}
                            <div style="font-size: 10px; font-weight: 700; color: #047857; text-transform: uppercase; margin-bottom: 2px;">${w.kategori}</div>
                            <div style="font-size: 14px; font-weight: 700; color: #0F172A; line-height: 1.3;">${w.nama}</div>
                            <div style="font-size: 11px; color: #64748B; margin-top: 4px;">${w.alamat}</div>
                            <div style="font-size: 11px; color: #475569; margin-top: 4px;">Tiket: ${w.harga_tiket} &bull; Buka: ${w.jam_operasional}</div>
                            ${distHtml}
                            <div style="margin-top: 10px; display: flex; gap: 6px;">
                                <a href="${w.detail_url}" style="flex: 1; text-align: center; background: #0F172A; color: #FFFFFF; text-decoration: none; padding: 6px 10px; font-size: 11px; font-weight: 600; border-radius: 6px;">Detail</a>
                                <a href="${w.google_maps_url}" target="_blank" style="text-align: center; background: #047857; color: #FFFFFF; text-decoration: none; padding: 6px 10px; font-size: 11px; font-weight: 600; border-radius: 6px;">Rute ↗</a>
                            </div>
                        </div>
                    `;

                    const marker = L.marker([w.latitude, w.longitude], {
                        icon: createPinIcon('#78350F', w.nama.substring(0, 16) + (w.nama.length > 16 ? '...' : ''))
                    }).bindPopup(popupContent);

                    wisataLayer.addLayer(marker);
                });
            }

            // Render Fasilitas Markers
            function renderFasilitasMarkers() {
                fasilitasLayer.clearLayers();

                fasilitasData.forEach(f => {
                    const popupContent = `
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; min-width: 180px; padding: 4px;">
                            <div style="font-size: 10px; font-weight: 700; color: #0284C7; text-transform: uppercase;">${f.jenis}</div>
                            <div style="font-size: 13px; font-weight: 700; color: #0F172A; margin-top: 2px;">${f.nama}</div>
                            <div style="font-size: 11px; color: #64748B; margin-top: 4px;">${f.lokasi || ''}</div>
                            <div style="font-size: 10px; color: #475569; margin-top: 2px;">${f.is_umum ? 'Fasilitas Umum Desa' : 'Fasilitas di ' + (f.objek_wisata || 'Objek Wisata')}</div>
                        </div>
                    `;

                    const marker = L.marker([f.latitude, f.longitude], {
                        icon: createPinIcon('#0284C7', f.nama.substring(0, 14))
                    }).bindPopup(popupContent);

                    fasilitasLayer.addLayer(marker);
                });
            }

            renderWisataMarkers();
            renderFasilitasMarkers();

            // Toggle Layer
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
                    alert('Perangkat Anda tidak mendukung fitur lokasi GPS.');
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
                                radius: 9,
                                fillColor: '#059669',
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
                            textClosest.innerHTML = `Destinasi terdekat: <strong>${closestWisata.nama}</strong> (${distStr}) &bull; <a href="${closestWisata.detail_url}" class="underline font-bold text-emerald-800">Buka Detail</a>`;
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
