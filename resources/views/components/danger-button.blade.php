@props(['solid' => false])

@php
$classes = $solid 
    ? 'inline-flex items-center justify-center h-11 px-5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-semibold text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/30 transition-all cursor-pointer shadow-xs border-none'
    : 'inline-flex items-center justify-center h-11 px-5 bg-white text-rose-600 border border-rose-200 font-semibold text-sm rounded-xl hover:bg-rose-50 active:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all cursor-pointer shadow-xs';
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
    {{ $slot }}
</button>
