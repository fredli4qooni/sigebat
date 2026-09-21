<x-app-layout>
    <x-slot:title>Dashboard Pengelola</x-slot:title>
    <x-slot:header>Dashboard Pengelola Wisata</x-slot:header>

    <div class="space-y-6">
        <!-- Papan Hijau Ringkasan Pengelola (DESIGN.md 7.11 & 8.7) -->
        <x-papan warna="hijau" class="p-6">
            <h2 class="text-2xl font-bold font-papan text-putih">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-putih/90 text-[16px] mt-1">
                Kelola data objek wisata adat, fasilitas pendukung, dan kalender kegiatan budaya Kampung Gedung Batin.
            </p>
        </x-papan>

        <!-- Grid Pintasan Cepat Pengelola -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-putih border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-kontrol bg-cokelat text-putih flex items-center justify-center text-lg mb-3">
                        🏡
                    </div>
                    <h3 class="text-xl font-bold font-papan text-aspal">Objek Wisata</h3>
                    <p class="text-sm text-abu mt-1">Kelola data rumah adat, wisata alam, koordinat GPS peta, dan galeri foto.</p>
                </div>
                <div class="mt-4 pt-4 border-t border-beton">
                    <a href="/pengelola/wisata" class="text-aspal font-bold underline hover:text-cokelat text-sm">
                        Buka data wisata &rarr;
                    </a>
                </div>
            </div>

            <div class="bg-putih border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-kontrol bg-biru text-putih flex items-center justify-center text-lg mb-3">
                        🚻
                    </div>
                    <h3 class="text-xl font-bold font-papan text-aspal">Fasilitas Desa</h3>
                    <p class="text-sm text-abu mt-1">Kelola fasilitas umum maupun fasilitas yang terhubung dengan objek wisata.</p>
                </div>
                <div class="mt-4 pt-4 border-t border-beton">
                    <a href="/pengelola/fasilitas" class="text-aspal font-bold underline hover:text-biru text-sm">
                        Buka data fasilitas &rarr;
                    </a>
                </div>
            </div>

            <div class="bg-putih border-2 border-aspal rounded-papan p-6 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-kontrol bg-kuning text-aspal flex items-center justify-center text-lg mb-3">
                        📅
                    </div>
                    <h3 class="text-xl font-bold font-papan text-aspal">Event Budaya</h3>
                    <p class="text-sm text-abu mt-1">Kelola agenda pagelaran seni, begawi adat, dan jadwal festival tahunan.</p>
                </div>
                <div class="mt-4 pt-4 border-t border-beton">
                    <a href="/pengelola/event" class="text-aspal font-bold underline hover:text-kuning-gelap text-sm">
                        Buka kalender event &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
