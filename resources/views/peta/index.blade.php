<x-portal-layout>
    <x-slot:title>Peta Interaktif & LBS — Desa Wisata Kampung Gedung Batin</x-slot:title>
    <x-slot:description>Peta interaktif berbasis Location Based Services (LBS). Jelajahi sebaran objek wisata rumah adat dan fasilitas penunjang di Kampung Gedung Batin dengan citra satelit resolusi tinggi.</x-slot:description>

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

        <!-- 2. Split-View Layout: Sidebar Navigasi LBS (Kiri) + Peta Satelit Full (Kanan) -->
        <div class="flex flex-col lg:flex-row gap-6 items-stretch">
            @include('peta.partials.sidebar')
            @include('peta.partials.map-container')
        </div>
    </div>

    @include('peta.partials.scripts')
</x-portal-layout>
