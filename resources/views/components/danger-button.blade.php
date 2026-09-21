@props(['solid' => false])

@php
$classes = $solid 
    ? 'inline-flex items-center justify-center h-[48px] px-6 bg-merah text-putih font-papan font-bold text-[17px] rounded-kontrol hover:bg-merah-gelap focus:outline-none transition-none cursor-pointer border-none'
    : 'inline-flex items-center justify-center h-[48px] px-6 bg-putih text-merah border-2 border-merah font-papan font-bold text-[17px] rounded-kontrol hover:bg-merah hover:text-putih focus:outline-none transition-none cursor-pointer';
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
    {{ $slot }}
</button>
