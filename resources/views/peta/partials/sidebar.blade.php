<!-- Sidebar Interaktif LBS (410px Desktop) -->
<div class="w-full lg:w-[410px] flex-shrink-0 flex flex-col bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    <!-- Header Kontrol Sidebar -->
    <div class="p-5 border-b border-slate-100 space-y-4 bg-slate-50/60">
        <!-- 1. Pilihan Titik Acuan LBS (GPS vs Titik Kedatangan Desa) -->
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
                    <option value="balai" selected>Balai Adat / Kantor Kampung (Pusat Desa)</option>
                    <option value="gerbang">Pintu Gerbang Masuk Desa Gedung Batin</option>
                    <option value="jembatan">Jembatan Gantung Way Besai</option>
                    <option value="gps">Posisi GPS Saya (Real-time Perangkat)</option>
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
        <span class="text-emerald-700 font-semibold flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Formula Haversine Aktif
        </span>
    </div>
</div>
