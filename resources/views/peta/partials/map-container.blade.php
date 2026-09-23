<!-- Area Peta Interaktif Leaflet Layar Penuh (Kanan) -->
<div class="flex-1 relative bg-slate-900 rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col min-h-[550px] lg:min-h-[720px]">
    
    <!-- Floating Controls Atas (Informasi Citra Satelit & Toggle Layer) -->
    <div class="absolute top-4 right-4 z-[500] flex flex-col sm:flex-row items-end sm:items-center gap-2.5">
        <!-- Badge Mode Citra Satelit Esri -->
        <div class="bg-slate-900/90 backdrop-blur-md rounded-xl px-3.5 py-2 shadow-md border border-slate-700/80 flex items-center gap-2 text-xs font-semibold text-emerald-300">
            <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Citra Satelit Resolusi Tinggi</span>
        </div>

        <!-- Toggle Layers Wisata & Fasilitas -->
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

    <!-- Floating Info Badge Kiri Atas (Status Rute Terpilih) -->
    <div id="route-info-badge" class="hidden absolute top-4 left-4 z-[500] bg-slate-900/95 backdrop-blur-md text-white rounded-xl px-4 py-2.5 shadow-lg border border-slate-700 max-w-sm">
        <div class="flex items-center justify-between gap-3 text-xs mb-1">
            <span class="text-emerald-400 font-bold uppercase tracking-wider text-[10px]">Rute Navigasi Terpilih</span>
            <button type="button" id="btn-close-route" class="text-slate-400 hover:text-white font-bold text-sm leading-none">&times;</button>
        </div>
        <div id="route-dest-name" class="font-bold text-sm text-white truncate">Destinasi</div>
        <div id="route-stats" class="text-xs text-slate-300 flex items-center gap-3 mt-1">
            <span>Jarak: <strong id="route-dist-text" class="text-emerald-400">-</strong></span>
            <span>&bull;</span>
            <span id="route-eta-text">Estimasi Tempuh</span>
        </div>
    </div>

    <!-- Kontainer Peta Leaflet Layar Penuh -->
    <div id="full-map" class="w-full flex-1 z-10 min-h-[550px] lg:min-h-[720px]"></div>

    <!-- Legenda & Keterangan Bawah Peta -->
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
