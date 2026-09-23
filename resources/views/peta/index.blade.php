<x-portal-layout>
    <x-slot:title>Peta Interaktif & LBS — Desa Wisata Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Peta interaktif berbasis Location Based Services (LBS). Jelajahi sebaran objek wisata rumah adat dan fasilitas penunjang di Kampung Gedung Batin.</x-slot:description>

    <div class="py-6 md:py-8 space-y-6 max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- 1. Header Peta LBS Modern -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 text-white shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Location Based Services (LBS)</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white">
                        Peta Sebaran Wisata & Fasilitas Desa
                    </h1>
                    <p class="text-slate-300 text-sm leading-relaxed font-normal">
                        Jelajahi titik cagar budaya dan sarana pendukung Kampung Gedung Batin dengan sistem navigasi cerdas berbasis GPS, citra satelit resolusi tinggi, dan estimasi waktu tempuh langsung.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                    <button
                        type="button"
                        id="btn-find-me"
                        class="h-11 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-md hover:shadow-emerald-600/30 transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span id="btn-find-text">Deteksi GPS Saya</span>
                    </button>
                    <button
                        type="button"
                        id="btn-recenter"
                        class="h-11 px-4 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-xs transition-all cursor-pointer flex items-center gap-2"
                        title="Pusatkan ke Balai Desa"
                    >
                        <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Pusat Desa</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. Split-View Layout: Sidebar Navigasi LBS (Kiri) + Peta Leaflet Layar Penuh (Kanan) -->
        <div class="flex flex-col lg:flex-row gap-6 items-stretch">
            
            <!-- A. Sidebar Interaktif (410px Desktop) -->
            <div class="w-full lg:w-[410px] flex-shrink-0 flex flex-col bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
                <!-- Header Kontrol Sidebar -->
                <div class="p-5 border-b border-slate-100 space-y-4 bg-slate-50/60">
                    <!-- 1. Pilihan Titik Acuan LBS (GPS vs Simulasi Kedatangan) -->
                    <div>
                        <label for="select-origin" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                            <span>Titik Acuan Wisatawan</span>
                            <span id="badge-active-origin" class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Balai Adat</span>
                        </label>
                        <div class="relative">
                            <select
                                id="select-origin"
                                class="w-full h-10 pl-3 pr-8 rounded-xl border border-slate-300 bg-white text-xs font-semibold text-slate-800 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all cursor-pointer"
                            >
                                <option value="balai" selected>🏡 Balai Adat / Kantor Kampung (Pusat Desa)</option>
                                <option value="gerbang">🏛️ Pintu Gerbang Masuk Desa Gedung Batin</option>
                                <option value="jembatan">🌉 Jembatan Gantung Way Besai</option>
                                <option value="gps">📍 Posisi GPS Saya (Real-time Perangkat)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. Pencarian Cepat -->
                    <div class="relative">
                        <input
                            type="text"
                            id="search-input"
                            placeholder="Cari cagar budaya atau fasilitas..."
                            class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 placeholder-slate-400 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- 3. Filter Radius & Tipe Konten -->
                    <div class="space-y-2.5 pt-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-500 uppercase tracking-wider text-[11px]">Jangkauan Radius:</span>
                            <div class="flex items-center gap-1" id="radius-buttons">
                                <button type="button" data-radius="all" class="radius-btn active px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-600 text-white transition-all">Semua</button>
                                <button type="button" data-radius="0.5" class="radius-btn px-2.5 py-1 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all">500 m</button>
                                <button type="button" data-radius="1" class="radius-btn px-2.5 py-1 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all">1 km</button>
                                <button type="button" data-radius="2" class="radius-btn px-2.5 py-1 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all">2 km</button>
                            </div>
                        </div>

                        <!-- Filter Tab Kategori (Semua / Wisata / Fasilitas) -->
                        <div class="grid grid-cols-3 gap-1 bg-slate-200/70 p-1 rounded-xl text-xs font-semibold text-slate-600">
                            <button type="button" id="tab-all" class="tab-filter active py-1.5 rounded-lg bg-white text-slate-900 shadow-xs text-center transition-all">Semua (<span id="count-all">0</span>)</button>
                            <button type="button" id="tab-wisata" class="tab-filter py-1.5 rounded-lg text-slate-600 hover:text-slate-900 text-center transition-all">Wisata (<span id="count-wisata">0</span>)</button>
                            <button type="button" id="tab-fasilitas" class="tab-filter py-1.5 rounded-lg text-slate-600 hover:text-slate-900 text-center transition-all">Fasilitas (<span id="count-fasilitas">0</span>)</button>
                        </div>
                    </div>
                </div>

                <!-- Daftar Kartu Destinasi Terurut Jarak LBS -->
                <div id="dest-list" class="flex-1 overflow-y-auto max-h-[620px] p-4 space-y-3 divide-y divide-slate-100 scrollbar-thin">
                    <!-- Kartu di-render otomatis via JavaScript -->
                </div>

                <!-- Footer Sidebar Status -->
                <div class="p-3 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span id="status-counter">Memuat data destinasi...</span>
                    <span class="text-emerald-700 font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Formula Haversine Aktif
                    </span>
                </div>
            </div>

            <!-- B. Area Peta Interaktif Leaflet (Kanan) -->
            <div class="flex-1 relative bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col min-h-[550px] lg:min-h-[720px]">
                
                <!-- Floating Controls Atas (Basemap Switch & Layer Toggles) -->
                <div class="absolute top-4 right-4 z-[500] flex flex-col sm:flex-row items-end sm:items-center gap-2.5">
                    <!-- Toggle Basemap: Peta Jalan vs Citra Satelit -->
                    <div class="bg-white/95 backdrop-blur-md rounded-xl p-1 shadow-md border border-slate-200/80 flex items-center text-xs font-semibold">
                        <button
                            type="button"
                            id="btn-map-streets"
                            class="px-3 py-1.5 rounded-lg bg-emerald-700 text-white shadow-xs transition-all flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span>Peta Jalan</span>
                        </button>
                        <button
                            type="button"
                            id="btn-map-satellite"
                            class="px-3 py-1.5 rounded-lg text-slate-700 hover:text-slate-900 transition-all flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Citra Satelit</span>
                        </button>
                    </div>

                    <!-- Toggle Layers (Checkbox) -->
                    <div class="bg-white/95 backdrop-blur-md rounded-xl px-3.5 py-1.5 shadow-md border border-slate-200/80 flex items-center gap-4 text-xs font-semibold text-slate-700">
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" id="toggle-wisata" checked class="w-3.5 h-3.5 rounded text-emerald-600 focus:ring-emerald-500/20 border-slate-300">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-800"></span>
                            <span>Wisata</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" id="toggle-fasilitas" checked class="w-3.5 h-3.5 rounded text-emerald-600 focus:ring-emerald-500/20 border-slate-300">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-600"></span>
                            <span>Fasilitas</span>
                        </label>
                    </div>
                </div>

                <!-- Floating Info Badge Kiri Atas (Status Rute / Destinasi Aktif) -->
                <div id="route-info-badge" class="hidden absolute top-4 left-4 z-[500] bg-slate-900/90 backdrop-blur-md text-white rounded-xl px-4 py-2.5 shadow-lg border border-slate-700 max-w-sm">
                    <div class="flex items-center justify-between gap-3 text-xs mb-1">
                        <span class="text-emerald-400 font-bold uppercase tracking-wider text-[10px]">Rute Navigasi Terpilih</span>
                        <button type="button" id="btn-close-route" class="text-slate-400 hover:text-white font-bold">&times;</button>
                    </div>
                    <div id="route-dest-name" class="font-bold text-sm text-white truncate">Destinasi</div>
                    <div id="route-stats" class="text-xs text-slate-300 flex items-center gap-3 mt-1">
                        <span>Jarak: <strong id="route-dist-text" class="text-emerald-400">-</strong></span>
                        <span>&bull;</span>
                        <span id="route-eta-text">🚶 ~5 mnt</span>
                    </div>
                </div>

                <!-- Kontainer Peta Leaflet -->
                <div id="full-map" class="w-full flex-1 z-10 min-h-[550px] lg:min-h-[720px]"></div>

                <!-- Legenda & Keterangan Bawah -->
                <div class="p-3.5 bg-slate-50 border-t border-slate-200/80 text-xs text-slate-600 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-5">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-amber-800 border border-white shadow-xs"></span>
                            <span class="font-medium text-slate-800">Rumah Panggung & Makam Adat</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-sky-600 border border-white shadow-xs"></span>
                            <span class="font-medium text-slate-800">Sarana Pendukung (Musala, Toilet, Parkir)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 border border-white shadow-xs animate-ping"></span>
                            <span class="font-semibold text-emerald-800">Titik Acuan Pengunjung</span>
                        </div>
                    </div>
                    <div class="text-slate-400 font-mono text-[11px]">
                        Pusat Koordinat: -4.540583, 104.664984
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Leaflet LBS Pro Interactive Engine -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wisataRaw = @json($wisataList);
            const fasilitasRaw = @json($fasilitasList);
            const presets = @json($presetLocations);

            const defaultVillageLat = -4.540583;
            const defaultVillageLng = 104.664984;

            // Titik Acuan Awal Default (Balai Adat)
            let activeOrigin = {
                lat: defaultVillageLat,
                lng: defaultVillageLng,
                label: 'Balai Adat / Kantor Kampung',
                type: 'balai'
            };

            let currentRadius = 'all'; // 'all', 0.5, 1, 2
            let currentTab = 'all';    // 'all', 'wisata', 'fasilitas'
            let searchKeyword = '';
            let selectedDestination = null;

            if (typeof L === 'undefined') {
                console.error('Leaflet JS is not loaded.');
                return;
            }

            // 1. Inisialisasi Tile Basemap
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });

            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });

            // 2. Inisialisasi Map
            const map = L.map('full-map', {
                center: [defaultVillageLat, defaultVillageLng],
                zoom: 16,
                zoomControl: true,
                layers: [osmLayer]
            });

            // Layer Groups
            const wisataLayer = L.layerGroup().addTo(map);
            const fasilitasLayer = L.layerGroup().addTo(map);
            let originMarker = null;
            let radiusCircle = null;
            let routePolyline = null;

            // 3. Formula Haversine
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

            function formatDistance(distKm) {
                if (distKm < 1) {
                    return Math.round(distKm * 1000) + ' m';
                }
                return distKm.toFixed(2) + ' km';
            }

            function calculateEta(distKm) {
                const walkMin = Math.max(1, Math.round((distKm / 4.5) * 60));
                const motorMin = Math.max(1, Math.round((distKm / 25) * 60));
                return {
                    walk: `🚶 ~${walkMin} mnt`,
                    motor: `🛵 ~${motorMin} mnt`
                };
            }

            // 4. Custom Icon Maker
            function createBadgeIcon(colorHex, text, iconSvg) {
                return L.divIcon({
                    className: 'custom-map-badge',
                    html: `
                        <div style="
                            background-color: ${colorHex};
                            color: #FFFFFF;
                            padding: 5px 9px;
                            border-radius: 9999px;
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            font-weight: 700;
                            font-size: 11px;
                            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.35);
                            border: 2px solid #FFFFFF;
                            white-space: nowrap;
                            display: inline-flex;
                            align-items: center;
                            gap: 5px;
                            cursor: pointer;
                        ">
                            ${iconSvg || ''}
                            <span>${text}</span>
                        </div>
                    `,
                    iconSize: [110, 32],
                    iconAnchor: [55, 34],
                    popupAnchor: [0, -34]
                });
            }

            // 5. Update Posisi Acuan (Origin Marker & Radius)
            function updateOrigin(lat, lng, label, type) {
                activeOrigin = { lat, lng, label, type };

                // Update label badge di sidebar
                document.getElementById('badge-active-origin').textContent = label;

                // Marker Acuan Pengguna
                if (originMarker) {
                    originMarker.setLatLng([lat, lng]);
                } else {
                    originMarker = L.circleMarker([lat, lng], {
                        radius: 10,
                        fillColor: '#059669',
                        color: '#FFFFFF',
                        weight: 3,
                        opacity: 1,
                        fillOpacity: 1
                    }).addTo(map);
                }
                originMarker.bindPopup(`<strong>${label}</strong><br><span style="font-size:11px;color:#64748B;">Titik Acuan Pengukuran Jarak LBS</span>`);

                // Radius Circle
                if (radiusCircle) {
                    map.removeLayer(radiusCircle);
                    radiusCircle = null;
                }

                if (currentRadius !== 'all') {
                    const radiusMeters = parseFloat(currentRadius) * 1000;
                    radiusCircle = L.circle([lat, lng], {
                        radius: radiusMeters,
                        color: '#10B981',
                        fillColor: '#10B981',
                        fillOpacity: 0.12,
                        weight: 1.5,
                        dashArray: '4, 6'
                    }).addTo(map);
                }

                // Render ulang data & hitung jarak
                renderAll();
            }

            // 6. Draw Polyline Route ke Destinasi Terpilih
            function drawRouteTo(dest) {
                if (routePolyline) {
                    map.removeLayer(routePolyline);
                    routePolyline = null;
                }

                const latlngs = [
                    [activeOrigin.lat, activeOrigin.lng],
                    [dest.latitude, dest.longitude]
                ];

                routePolyline = L.polyline(latlngs, {
                    color: '#059669',
                    weight: 3.5,
                    dashArray: '6, 8',
                    opacity: 0.9
                }).addTo(map);

                const dist = haversine(activeOrigin.lat, activeOrigin.lng, dest.latitude, dest.longitude);
                const distStr = formatDistance(dist);
                const eta = calculateEta(dist);

                // Tampilkan Floating Badge Rute
                const badge = document.getElementById('route-info-badge');
                document.getElementById('route-dest-name').textContent = dest.nama;
                document.getElementById('route-dist-text').textContent = distStr;
                document.getElementById('route-eta-text').textContent = `${eta.walk} &bull; ${eta.motor}`;
                badge.classList.remove('hidden');

                // Bounding box map agar kedua titik terlihat
                const bounds = L.latLngBounds(latlngs);
                map.fitBounds(bounds, { padding: [80, 80], maxZoom: 17 });
            }

            // 7. Render Seluruh Marker & Daftar Sidebar
            function renderAll() {
                wisataLayer.clearLayers();
                fasilitasLayer.clearLayers();

                // Hitung jarak untuk seluruh item
                const allItems = [];

                wisataRaw.forEach(w => {
                    const dist = haversine(activeOrigin.lat, activeOrigin.lng, w.latitude, w.longitude);
                    allItems.push({
                        ...w,
                        type: 'wisata',
                        distance: dist,
                        formattedDistance: formatDistance(dist),
                        eta: calculateEta(dist)
                    });
                });

                fasilitasRaw.forEach(f => {
                    const dist = haversine(activeOrigin.lat, activeOrigin.lng, f.latitude, f.longitude);
                    allItems.push({
                        ...f,
                        type: 'fasilitas',
                        distance: dist,
                        formattedDistance: formatDistance(dist),
                        eta: calculateEta(dist)
                    });
                });

                // Update Counter Total
                document.getElementById('count-all').textContent = allItems.length;
                document.getElementById('count-wisata').textContent = wisataRaw.length;
                document.getElementById('count-fasilitas').textContent = fasilitasRaw.length;

                // Filter berdasarkan Search, Radius, & Tab
                const filtered = allItems.filter(item => {
                    // Filter Tab
                    if (currentTab === 'wisata' && item.type !== 'wisata') return false;
                    if (currentTab === 'fasilitas' && item.type !== 'fasilitas') return false;

                    // Filter Radius
                    if (currentRadius !== 'all') {
                        const maxKm = parseFloat(currentRadius);
                        if (item.distance > maxKm) return false;
                    }

                    // Filter Search Keyword
                    if (searchKeyword.trim() !== '') {
                        const kw = searchKeyword.toLowerCase();
                        const matchName = item.nama.toLowerCase().includes(kw);
                        const matchCategory = (item.kategori || item.jenis || '').toLowerCase().includes(kw);
                        const matchAlamat = (item.alamat || item.lokasi || '').toLowerCase().includes(kw);
                        if (!matchName && !matchCategory && !matchAlamat) return false;
                    }

                    return true;
                });

                // Urutkan dari jarak terdekat
                filtered.sort((a, b) => a.distance - b.distance);

                // Render Sidebar Cards
                const listContainer = document.getElementById('dest-list');
                listContainer.innerHTML = '';

                if (filtered.length === 0) {
                    listContainer.innerHTML = `
                        <div class="py-12 text-center text-slate-400 space-y-2">
                            <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                            </svg>
                            <p class="text-xs font-medium">Tidak ada objek wisata atau sarana dalam kriteria ini.</p>
                            <button type="button" id="btn-reset-filters" class="text-xs text-emerald-700 font-semibold underline">Reset Semua Filter</button>
                        </div>
                    `;
                    document.getElementById('btn-reset-filters')?.addEventListener('click', () => {
                        searchKeyword = '';
                        document.getElementById('search-input').value = '';
                        currentRadius = 'all';
                        document.querySelectorAll('.radius-btn').forEach(b => {
                            b.classList.toggle('active', b.dataset.radius === 'all');
                            b.classList.toggle('bg-emerald-600', b.dataset.radius === 'all');
                            b.classList.toggle('text-white', b.dataset.radius === 'all');
                        });
                        currentTab = 'all';
                        document.querySelectorAll('.tab-filter').forEach(b => {
                            b.classList.toggle('active', b.id === 'tab-all');
                            b.classList.toggle('bg-white', b.id === 'tab-all');
                            b.classList.toggle('text-slate-900', b.id === 'tab-all');
                        });
                        renderAll();
                    });
                } else {
                    filtered.forEach((item) => {
                        const card = document.createElement('div');
                        card.id = `card-${item.type}-${item.id}`;
                        card.className = `p-3 rounded-xl border border-slate-200 bg-white hover:border-emerald-300 hover:shadow-xs transition-all cursor-pointer group`;

                        const isWisata = item.type === 'wisata';
                        const badgeColor = isWisata ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800';

                        card.innerHTML = `
                            <div class="flex items-start gap-3">
                                ${isWisata && item.foto_url ? `
                                    <img src="${item.foto_url}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0" alt="${item.nama}">
                                ` : `
                                    <div class="w-12 h-12 rounded-lg ${isWisata ? 'bg-amber-50 text-amber-800' : 'bg-sky-50 text-sky-700'} flex items-center justify-center flex-shrink-0 font-bold text-xs">
                                        ${isWisata ? '🏛️' : '🚻'}
                                    </div>
                                `}
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full ${badgeColor}">
                                            ${item.kategori || item.jenis}
                                        </span>
                                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                            ${item.formattedDistance}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-xs text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug line-clamp-1">
                                        ${item.nama}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                        ${item.alamat || item.lokasi || 'Kampung Gedung Batin'}
                                    </p>
                                    <div class="flex items-center gap-3 mt-2 text-[10px] text-slate-400 font-medium">
                                        <span>${item.eta.walk}</span>
                                        <span>&bull;</span>
                                        <span>${item.eta.motor}</span>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Klik Card -> Fokus Peta & Rute
                        card.addEventListener('click', () => {
                            focusDestination(item);
                        });

                        listContainer.appendChild(card);
                    });
                }

                document.getElementById('status-counter').textContent = `Menampilkan ${filtered.length} dari ${allItems.length} lokasi`;

                // Render Markers di Peta (hanya yang masuk filter)
                filtered.forEach(item => {
                    const isWisata = item.type === 'wisata';
                    const color = isWisata ? '#78350F' : '#0284C7';
                    const label = item.nama.length > 15 ? item.nama.substring(0, 14) + '…' : item.nama;

                    const marker = L.marker([item.latitude, item.longitude], {
                        icon: createBadgeIcon(color, label)
                    });

                    // Custom Popup Modern
                    const popupHtml = `
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; min-width: 230px; max-width: 270px; padding: 2px;">
                            ${isWisata && item.foto_url ? `<img src="${item.foto_url}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;" alt="${item.nama}">` : ''}
                            <div style="font-size: 10px; font-weight: 700; color: ${isWisata ? '#047857' : '#0284C7'}; text-transform: uppercase;">
                                ${item.kategori || item.jenis}
                            </div>
                            <div style="font-size: 14px; font-weight: 700; color: #0F172A; line-height: 1.3; margin-top: 2px;">
                                ${item.nama}
                            </div>
                            <div style="font-size: 11px; color: #64748B; margin-top: 4px;">
                                ${item.alamat || item.lokasi || ''}
                            </div>
                            <div style="margin-top: 8px; padding: 5px 8px; background: #F0FDF4; border: 1px solid #BBF7D0; color: #065F46; font-size: 11px; font-weight: 700; border-radius: 6px; display: flex; justify-content: space-between;">
                                <span>Jarak: ${item.formattedDistance}</span>
                                <span>${item.eta.walk}</span>
                            </div>
                            <div style="margin-top: 10px; display: flex; gap: 6px;">
                                ${isWisata ? `<a href="${item.detail_url}" style="flex: 1; text-align: center; background: #0F172A; color: #FFFFFF; text-decoration: none; padding: 6px 10px; font-size: 11px; font-weight: 600; border-radius: 6px;">Detail</a>` : ''}
                                <a href="${item.google_maps_url || 'https://www.google.com/maps/dir/?api=1&destination=' + item.latitude + ',' + item.longitude}" target="_blank" style="flex: 1; text-align: center; background: #047857; color: #FFFFFF; text-decoration: none; padding: 6px 10px; font-size: 11px; font-weight: 600; border-radius: 6px;">Rute ↗</a>
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupHtml);

                    // Klik Marker di Peta -> Sorot Card di Sidebar & Gambar Rute
                    marker.on('click', () => {
                        highlightSidebarCard(item);
                        drawRouteTo(item);
                    });

                    if (isWisata) {
                        wisataLayer.addLayer(marker);
                    } else {
                        fasilitasLayer.addLayer(marker);
                    }
                });
            }

            // 8. Interaksi Dua Arah (Klik Card -> FlyTo Peta)
            function focusDestination(item) {
                selectedDestination = item;
                highlightSidebarCard(item);

                map.flyTo([item.latitude, item.longitude], 17, {
                    duration: 1.2
                });

                drawRouteTo(item);

                // Buka Popup marker yang sesuai
                const targetLayer = item.type === 'wisata' ? wisataLayer : fasilitasLayer;
                targetLayer.eachLayer(layer => {
                    const pos = layer.getLatLng();
                    if (Math.abs(pos.lat - item.latitude) < 0.00001 && Math.abs(pos.lng - item.longitude) < 0.00001) {
                        layer.openPopup();
                    }
                });
            }

            function highlightSidebarCard(item) {
                document.querySelectorAll('#dest-list > div').forEach(c => {
                    c.classList.remove('ring-2', 'ring-emerald-500', 'bg-emerald-50/50');
                });

                const card = document.getElementById(`card-${item.type}-${item.id}`);
                if (card) {
                    card.classList.add('ring-2', 'ring-emerald-500', 'bg-emerald-50/50');
                    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }

            // 9. Event Listeners Kontrol Sidebar
            // A. Pilihan Titik Acuan Origin
            document.getElementById('select-origin').addEventListener('change', function(e) {
                const val = e.target.value;
                if (val === 'gps') {
                    detectUserGps();
                } else if (val === 'gerbang') {
                    updateOrigin(-4.538500, 104.663200, 'Pintu Gerbang Desa Gedung Batin', 'gerbang');
                    map.flyTo([-4.538500, 104.663200], 16);
                } else if (val === 'jembatan') {
                    updateOrigin(-4.542100, 104.666800, 'Jembatan Gantung Way Besai', 'jembatan');
                    map.flyTo([-4.542100, 104.666800], 16);
                } else {
                    updateOrigin(defaultVillageLat, defaultVillageLng, 'Balai Adat / Kantor Kampung', 'balai');
                    map.flyTo([defaultVillageLat, defaultVillageLng], 16);
                }
            });

            // B. Tombol GPS Nyata
            function detectUserGps() {
                const btnFind = document.getElementById('btn-find-me');
                const btnFindText = document.getElementById('btn-find-text');

                if (!navigator.geolocation) {
                    alert('Browser atau perangkat Anda tidak mendukung fitur lokasi GPS.');
                    document.getElementById('select-origin').value = 'balai';
                    return;
                }

                btnFindText.textContent = 'Mencari GPS...';
                btnFind.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        btnFindText.textContent = 'GPS Terdeteksi';
                        btnFind.disabled = false;
                        document.getElementById('select-origin').value = 'gps';
                        updateOrigin(pos.coords.latitude, pos.coords.longitude, 'Posisi GPS Saya', 'gps');
                        map.flyTo([pos.coords.latitude, pos.coords.longitude], 16);
                    },
                    function(err) {
                        alert('Tidak dapat mendeteksi GPS. Pastikan izin lokasi browser Anda aktif.');
                        btnFindText.textContent = 'Deteksi GPS Saya';
                        btnFind.disabled = false;
                        document.getElementById('select-origin').value = 'balai';
                        updateOrigin(defaultVillageLat, defaultVillageLng, 'Balai Adat / Kantor Kampung', 'balai');
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            }

            document.getElementById('btn-find-me').addEventListener('click', detectUserGps);

            // C. Tombol Pusat Desa
            document.getElementById('btn-recenter').addEventListener('click', () => {
                map.flyTo([defaultVillageLat, defaultVillageLng], 16);
                document.getElementById('select-origin').value = 'balai';
                updateOrigin(defaultVillageLat, defaultVillageLng, 'Balai Adat / Kantor Kampung', 'balai');
            });

            // D. Tombol Tutup Rute Floating Badge
            document.getElementById('btn-close-route').addEventListener('click', () => {
                document.getElementById('route-info-badge').classList.add('hidden');
                if (routePolyline) {
                    map.removeLayer(routePolyline);
                    routePolyline = null;
                }
            });

            // E. Search Keyword Input
            document.getElementById('search-input').addEventListener('input', function(e) {
                searchKeyword = e.target.value;
                renderAll();
            });

            // F. Radius Buttons
            document.querySelectorAll('.radius-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.radius-btn').forEach(b => {
                        b.classList.remove('active', 'bg-emerald-600', 'text-white');
                        b.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-600');
                    });
                    this.classList.add('active', 'bg-emerald-600', 'text-white');
                    this.classList.remove('bg-white', 'border', 'border-slate-200', 'text-slate-600');
                    currentRadius = this.dataset.radius;
                    updateOrigin(activeOrigin.lat, activeOrigin.lng, activeOrigin.label, activeOrigin.type);
                });
            });

            // G. Tab Filter (Semua, Wisata, Fasilitas)
            const tabs = [
                { id: 'tab-all', val: 'all' },
                { id: 'tab-wisata', val: 'wisata' },
                { id: 'tab-fasilitas', val: 'fasilitas' }
            ];
            tabs.forEach(t => {
                document.getElementById(t.id).addEventListener('click', function() {
                    tabs.forEach(tb => {
                        const el = document.getElementById(tb.id);
                        el.classList.remove('active', 'bg-white', 'text-slate-900', 'shadow-xs');
                        el.classList.add('text-slate-600');
                    });
                    this.classList.add('active', 'bg-white', 'text-slate-900', 'shadow-xs');
                    this.classList.remove('text-slate-600');
                    currentTab = t.val;
                    renderAll();
                });
            });

            // H. Toggle Basemap (Peta Jalan vs Satelit)
            const btnStreets = document.getElementById('btn-map-streets');
            const btnSatellite = document.getElementById('btn-map-satellite');

            btnStreets.addEventListener('click', () => {
                if (!map.hasLayer(osmLayer)) {
                    map.removeLayer(satelliteLayer);
                    map.addLayer(osmLayer);
                    btnStreets.classList.add('bg-emerald-700', 'text-white', 'shadow-xs');
                    btnStreets.classList.remove('text-slate-700');
                    btnSatellite.classList.remove('bg-emerald-700', 'text-white', 'shadow-xs');
                    btnSatellite.classList.add('text-slate-700');
                }
            });

            btnSatellite.addEventListener('click', () => {
                if (!map.hasLayer(satelliteLayer)) {
                    map.removeLayer(osmLayer);
                    map.addLayer(satelliteLayer);
                    btnSatellite.classList.add('bg-emerald-700', 'text-white', 'shadow-xs');
                    btnSatellite.classList.remove('text-slate-700');
                    btnStreets.classList.remove('bg-emerald-700', 'text-white', 'shadow-xs');
                    btnStreets.classList.add('text-slate-700');
                }
            });

            // I. Toggle Layer Checkbox
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

            // Inisialisasi awal: Aktifkan titik acuan default Balai Adat
            updateOrigin(defaultVillageLat, defaultVillageLng, 'Balai Adat / Kantor Kampung', 'balai');
        });
    </script>
</x-portal-layout>
