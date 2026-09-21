<x-app-layout>
    <x-slot:title>Dashboard</x-slot:title>
    <x-slot:header>Dashboard</x-slot:header>

    <div class="bg-putih border-2 border-aspal rounded-papan p-6 sm:p-8">
        <h2 class="text-xl font-bold font-papan text-aspal mb-2">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="text-[16px] text-abu mb-6">
            Anda masuk sebagai <strong>{{ auth()->user()->role === 'admin' ? 'Administrator Sistem' : 'Pengelola Desa Wisata' }}</strong>.
        </p>

        @if(auth()->user()->isAdmin())
            <a href="/admin" class="inline-flex items-center justify-center h-[48px] px-6 bg-aspal text-putih font-papan font-bold text-[16px] rounded-kontrol hover:bg-black no-underline">
                Buka Panel Administrator &rarr;
            </a>
        @else
            <a href="/pengelola" class="inline-flex items-center justify-center h-[48px] px-6 bg-aspal text-putih font-papan font-bold text-[16px] rounded-kontrol hover:bg-black no-underline">
                Buka Ruang Pengelola Wisata &rarr;
            </a>
        @endif
    </div>
</x-app-layout>
