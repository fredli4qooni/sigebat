<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'SIGEBAT') }} — Desa Wisata Kampung Gedung Batin</title>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 selection:bg-emerald-100 selection:text-emerald-900">
        <div class="w-full max-w-[440px]">
            <!-- Header Brand Modern -->
            <div class="mb-6 text-center">
                <a href="/" class="inline-flex items-center gap-3 no-underline group">
                    <div class="w-11 h-11 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 256 256">
                            <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="block font-bold text-xl text-slate-900 tracking-tight group-hover:text-emerald-700 transition-colors">SIGEBAT</span>
                        <span class="text-xs font-medium text-slate-500">Kampung Gedung Batin</span>
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
