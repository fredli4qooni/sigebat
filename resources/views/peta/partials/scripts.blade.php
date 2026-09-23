<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wisataRaw = @json($wisataList);
        const fasilitasRaw = @json($fasilitasList);
        const presets = @json($presetLocations);

        const defaultVillageLat = -4.540583;
        const defaultVillageLng = 104.664984;

        let activeOrigin = {
            lat: defaultVillageLat,
            lng: defaultVillageLng,
            label: 'Balai Adat / Kantor Kampung',
            type: 'balai'
        };

        let currentRadius = 'all';
        let currentTab = 'all';
        let searchKeyword = '';
        let selectedDestination = null;

        if (typeof L === 'undefined') {
            console.error('Leaflet JS is not loaded.');
            return;
        }

        // 1. Inisialisasi Basemap: Eksklusif Citra Satelit Esri Resolusi Tinggi
        const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        // 2. Inisialisasi Map
        const map = L.map('full-map', {
            center: [defaultVillageLat, defaultVillageLng],
            zoom: 16,
            zoomControl: true,
            layers: [satelliteLayer]
        });

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
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function formatDistance(distKm) {
            return distKm < 1 ? Math.round(distKm * 1000) + ' m' : distKm.toFixed(2) + ' km';
        }

        function calculateEta(distKm) {
            const walkMin = Math.max(1, Math.round((distKm / 4.5) * 60));
            const motorMin = Math.max(1, Math.round((distKm / 25) * 60));
            return {
                walk: `Jalan: ~${walkMin} mnt`,
                motor: `Motor: ~${motorMin} mnt`
            };
        }

        // 4. Custom Marker Icon Factory (Bebas Hard Emoji)
        function createBadgeIcon(colorHex, text) {
            return L.divIcon({
                className: 'custom-map-badge',
                html: `<div style="background-color:${colorHex};color:#fff;padding:4px 9px;border-radius:9999px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:11px;box-shadow:0 4px 12px rgba(15,23,42,0.4);border:2px solid #fff;white-space:nowrap;display:inline-flex;align-items:center;cursor:pointer;"><span>${text}</span></div>`,
                iconSize: [110, 32],
                iconAnchor: [55, 34],
                popupAnchor: [0, -34]
            });
        }

        // 5. Update Posisi Acuan (Origin Marker & Radius)
        function updateOrigin(lat, lng, label, type) {
            activeOrigin = { lat, lng, label, type };
            const badge = document.getElementById('badge-active-origin');
            if (badge) badge.textContent = label;

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
                color: '#10B981',
                weight: 3.5,
                dashArray: '6, 8',
                opacity: 0.95
            }).addTo(map);

            const dist = haversine(activeOrigin.lat, activeOrigin.lng, dest.latitude, dest.longitude);
            const distStr = formatDistance(dist);
            const eta = calculateEta(dist);

            const badge = document.getElementById('route-info-badge');
            document.getElementById('route-dest-name').textContent = dest.nama;
            document.getElementById('route-dist-text').textContent = distStr;
            document.getElementById('route-eta-text').textContent = `${eta.walk} • ${eta.motor}`;
            badge.classList.remove('hidden');

            const bounds = L.latLngBounds(latlngs);
            map.fitBounds(bounds, { padding: [80, 80], maxZoom: 17 });
        }

        // 7. Render Seluruh Marker & Daftar Sidebar
        function renderAll() {
            wisataLayer.clearLayers();
            fasilitasLayer.clearLayers();

            const allItems = [];
            wisataRaw.forEach(w => {
                const dist = haversine(activeOrigin.lat, activeOrigin.lng, w.latitude, w.longitude);
                allItems.push({ ...w, type: 'wisata', distance: dist, formattedDistance: formatDistance(dist), eta: calculateEta(dist) });
            });

            fasilitasRaw.forEach(f => {
                const dist = haversine(activeOrigin.lat, activeOrigin.lng, f.latitude, f.longitude);
                allItems.push({ ...f, type: 'fasilitas', distance: dist, formattedDistance: formatDistance(dist), eta: calculateEta(dist) });
            });

            document.getElementById('count-all').textContent = allItems.length;
            document.getElementById('count-wisata').textContent = wisataRaw.length;
            document.getElementById('count-fasilitas').textContent = fasilitasRaw.length;

            const filtered = allItems.filter(item => {
                if (currentTab === 'wisata' && item.type !== 'wisata') return false;
                if (currentTab === 'fasilitas' && item.type !== 'fasilitas') return false;
                if (currentRadius !== 'all' && item.distance > parseFloat(currentRadius)) return false;

                if (searchKeyword.trim() !== '') {
                    const kw = searchKeyword.toLowerCase();
                    const matchName = item.nama.toLowerCase().includes(kw);
                    const matchCategory = (item.kategori || item.jenis || '').toLowerCase().includes(kw);
                    const matchAlamat = (item.alamat || item.lokasi || '').toLowerCase().includes(kw);
                    if (!matchName && !matchCategory && !matchAlamat) return false;
                }
                return true;
            });

            filtered.sort((a, b) => a.distance - b.distance);

            const listContainer = document.getElementById('dest-list');
            listContainer.innerHTML = '';

            if (filtered.length === 0) {
                listContainer.innerHTML = `
                    <div class="py-12 text-center text-slate-400 space-y-2">
                        <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <p class="text-xs font-medium">Tidak ada objek wisata atau sarana dalam kriteria ini.</p>
                        <button type="button" id="btn-reset-filters" class="text-xs text-emerald-700 font-semibold underline cursor-pointer">Reset Semua Filter</button>
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
                    const iconFallbackSvg = isWisata
                        ? `<svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`
                        : `<svg class="w-5 h-5 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>`;

                    card.innerHTML = `
                        <div class="flex items-start gap-3">
                            ${isWisata && item.foto_url ? `
                                <img src="${item.foto_url}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0" alt="${item.nama}">
                            ` : `
                                <div class="w-12 h-12 rounded-lg ${isWisata ? 'bg-amber-50' : 'bg-sky-50'} flex items-center justify-center flex-shrink-0">
                                    ${iconFallbackSvg}
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
                                    <span>•</span>
                                    <span>${item.eta.motor}</span>
                                </div>
                            </div>
                        </div>
                    `;

                    card.addEventListener('click', () => focusDestination(item));
                    listContainer.appendChild(card);
                });
            }

            document.getElementById('status-counter').textContent = `Menampilkan ${filtered.length} dari ${allItems.length} lokasi`;

            filtered.forEach(item => {
                const isWisata = item.type === 'wisata';
                const color = isWisata ? '#78350F' : '#0284C7';
                const label = item.nama.length > 15 ? item.nama.substring(0, 14) + '…' : item.nama;

                const marker = L.marker([item.latitude, item.longitude], {
                    icon: createBadgeIcon(color, label)
                });

                const popupHtml = `
                    <div style="font-family:'Plus Jakarta Sans',sans-serif;min-width:230px;max-width:270px;padding:2px;">
                        ${isWisata && item.foto_url ? `<img src="${item.foto_url}" style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-bottom:8px;" alt="${item.nama}">` : ''}
                        <div style="font-size:10px;font-weight:700;color:${isWisata ? '#047857' : '#0284C7'};text-transform:uppercase;">
                            ${item.kategori || item.jenis}
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#0F172A;line-height:1.3;margin-top:2px;">
                            ${item.nama}
                        </div>
                        <div style="font-size:11px;color:#64748B;margin-top:4px;">
                            ${item.alamat || item.lokasi || ''}
                        </div>
                        <div style="margin-top:8px;padding:5px 8px;background:#F0FDF4;border:1px solid #BBF7D0;color:#065F46;font-size:11px;font-weight:700;border-radius:6px;display:flex;justify-content:space-between;">
                            <span>Jarak: ${item.formattedDistance}</span>
                            <span>${item.eta.walk}</span>
                        </div>
                        <div style="margin-top:10px;display:flex;gap:6px;">
                            ${isWisata ? `<a href="${item.detail_url}" style="flex:1;text-align:center;background:#0F172A;color:#fff;text-decoration:none;padding:6px 10px;font-size:11px;font-weight:600;border-radius:6px;">Detail</a>` : ''}
                            <a href="${item.google_maps_url || 'https://www.google.com/maps/dir/?api=1&destination=' + item.latitude + ',' + item.longitude}" target="_blank" style="flex:1;text-align:center;background:#047857;color:#fff;text-decoration:none;padding:6px 10px;font-size:11px;font-weight:600;border-radius:6px;">Rute ↗</a>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupHtml);
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
            map.flyTo([item.latitude, item.longitude], 17, { duration: 1.2 });
            drawRouteTo(item);

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

        document.getElementById('btn-recenter').addEventListener('click', () => {
            map.flyTo([defaultVillageLat, defaultVillageLng], 16);
            document.getElementById('select-origin').value = 'balai';
            updateOrigin(defaultVillageLat, defaultVillageLng, 'Balai Adat / Kantor Kampung', 'balai');
        });

        document.getElementById('btn-close-route').addEventListener('click', () => {
            document.getElementById('route-info-badge').classList.add('hidden');
            if (routePolyline) {
                map.removeLayer(routePolyline);
                routePolyline = null;
            }
        });

        document.getElementById('search-input').addEventListener('input', function(e) {
            searchKeyword = e.target.value;
            renderAll();
        });

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

        // Inisialisasi awal
        updateOrigin(defaultVillageLat, defaultVillageLng, 'Balai Adat / Kantor Kampung', 'balai');
    });
</script>
