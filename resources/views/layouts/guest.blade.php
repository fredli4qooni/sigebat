<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'SIGEBAT') }} — Desa Wisata Kampung Gedung Batin</title>

        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 selection:bg-emerald-100 selection:text-emerald-900">
        <div class="w-full max-w-[440px]">
            <!-- Header Brand Modern -->
            <div class="mb-6 text-center">
                <a href="/" class="inline-flex items-center gap-3.5 no-underline group">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SIGEBAT" class="w-12 h-12 rounded-xl object-contain bg-white border border-slate-200 shadow-xs group-hover:scale-105 transition-transform flex-shrink-0">
                    <div class="text-left">
                        <span class="block font-bold text-xl text-slate-900 tracking-tight group-hover:text-emerald-700 transition-colors">SIGEBAT</span>
                        <span class="text-xs font-medium text-slate-500">Kampung Gedung Batin, Way Kanan</span>
                    </div>
                </a>
            </div>

            <!-- Card Form Modern -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-7 sm:p-9 shadow-sm">
                {{ $slot }}
            </div>

            <!-- Kembali ke Beranda -->
            <div class="mt-6 text-center text-xs sm:text-sm text-slate-500">
                <a href="/" class="inline-flex items-center gap-1.5 font-medium text-slate-600 hover:text-emerald-700 transition-colors no-underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Halaman Publik</span>
                </a>
            </div>
        </div>
    </body>
</html>
