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
    <body class="bg-beton text-aspal font-sans antialiased min-h-screen flex flex-col justify-center items-center p-4">
        <div class="w-full max-w-[420px]">
            <!-- Header Papan Rambu -->
            <div class="mb-4 text-center">
                <a href="/" class="inline-block no-underline">
                    <div class="papan papan--cokelat text-center py-3 px-6 inline-flex items-center gap-3">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 256 256">
                            <path d="M218.83,103.77l-80-75.48a13.9,13.9,0,0,0-17.66,0l-80,75.48A14,14,0,0,0,36,114V208a14,14,0,0,0,14,14H206a14,14,0,0,0,14-14V114A14,14,0,0,0,218.83,103.77ZM206,206H50a2,2,0,0,1-2-2V114a2,2,0,0,1,.74-1.57l80-75.49a2,2,0,0,1,2.52,0l80,75.49A2,2,0,0,1,212,114V204A2,2,0,0,1,206,206Z"/>
                        </svg>
                        <div class="text-left">
                            <div class="font-papan font-bold text-xl leading-none text-putih">SIGEBAT</div>
                            <div class="text-xs text-putih tracking-wide opacity-90">Kampung Gedung Batin</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Lembar Form Putih dengan Border Aspal 2px (DESIGN.md 8.6) -->
            <div class="bg-putih border-2 border-aspal rounded-papan p-6 sm:p-8">
                {{ $slot }}
            </div>

            <div class="mt-4 text-center text-sm text-abu">
                <a href="/" class="text-aspal underline font-semibold hover:text-cokelat">Kembali ke Halaman Publik</a>
            </div>
        </div>
    </body>
</html>
