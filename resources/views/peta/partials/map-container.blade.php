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
                <span class="w-3.5 h-3.5 rounded-full bg-amber-700 border-2 border-white shadow-xs"></span>
                <span class="font-medium text-slate-800">Pin Objek Wisata Adat</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3.5 h-3.5 rounded-full bg-sky-600 border-2 border-white shadow-xs"></span>
                <span class="font-medium text-slate-800">Pin Sarana & Fasilitas</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white shadow-xs animate-ping"></span>
                <span class="font-semibold text-emerald-800">Titik Acuan Pengunjung</span>
            </div>
        </div>
        <div class="text-slate-400 font-mono text-[11px]">
            Pusat Koordinat: -4.540583, 104.664984
        </div>
    </div>
</div>

<style>
    /* Custom Pin Leaflet Sigebat */
    .sigebat-custom-pin {
        background: transparent !important;
        border: none !important;
    }
    .sigebat-pin-wrapper {
        position: relative;
        width: 44px;
        height: 52px;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.45));
        transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.22s ease;
    }
    .sigebat-pin-wrapper:hover {
        transform: translateY(-5px) scale(1.12);
        filter: drop-shadow(0 8px 14px rgba(0, 0, 0, 0.55));
        z-index: 1000 !important;
    }
    .sigebat-pin-wrapper.is-active {
        transform: translateY(-6px) scale(1.16);
        filter: drop-shadow(0 0 12px rgba(16, 185, 129, 0.8)) drop-shadow(0 6px 12px rgba(0, 0, 0, 0.5));
        z-index: 1001 !important;
    }
    .sigebat-pin-head {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border-width: 3px;
        border-style: solid;
        background: #ffffff;
        box-shadow: 0 0 0 1.5px rgba(255, 255, 255, 0.95);
        overflow: hidden;
        position: relative;
        z-index: 2;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .sigebat-pin-wrapper.is-active .sigebat-pin-head {
        border-color: #10B981 !important;
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #10B981 !important;
    }
    .sigebat-pin-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .sigebat-pin-beak {
        position: absolute;
        top: 34px;
        left: 50%;
        transform: translateX(-50%) rotate(45deg);
        width: 14px;
        height: 14px;
        border-bottom-right-radius: 3px;
        box-shadow: 1px 1px 0 1px rgba(255, 255, 255, 0.9);
        z-index: 1;
        transition: background-color 0.2s ease;
    }
    .sigebat-pin-wrapper.is-active .sigebat-pin-beak {
        background-color: #10B981 !important;
    }
    .sigebat-pin-badge {
        position: absolute;
        top: -1px;
        right: 0px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        z-index: 3;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
    }
    /* Tooltip */
    .sigebat-pin-tooltip {
        background: rgba(15, 23, 42, 0.92) !important;
        backdrop-filter: blur(6px) !important;
        -webkit-backdrop-filter: blur(6px) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        border-radius: 8px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        padding: 5px 9px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
        white-space: nowrap !important;
    }
    .sigebat-pin-tooltip::before {
        border-top-color: rgba(15, 23, 42, 0.92) !important;
    }
</style>
