@props(['status'])

@php
$statusLower = strtolower($status);
@endphp

@if ($statusLower === 'aktif')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-tag bg-hijau text-putih text-sm font-semibold">
        <svg class="w-4 h-4 fill-current" viewBox="0 0 256 256"><path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"/></svg>
        <span>Aktif</span>
    </span>
@elseif ($statusLower === 'pending')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-tag bg-beton text-aspal border-2 border-dashed border-aspal text-sm font-semibold">
        <svg class="w-4 h-4 fill-current text-abu" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm64-88a8,8,0,0,1-8,8H128a8,8,0,0,1-8-8V72a8,8,0,0,1,16,0v48h48A8,8,0,0,1,192,128Z"/></svg>
        <span>Pending</span>
    </span>
@elseif ($statusLower === 'akan datang')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-tag bg-putih text-aspal border-2 border-aspal text-sm font-semibold">
        <span>Akan datang</span>
    </span>
@elseif ($statusLower === 'berlangsung')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-tag bg-aspal text-kuning text-sm font-semibold">
        <span class="w-2 h-2 rounded-full bg-kuning inline-block animate-pulse"></span>
        <span>Berlangsung</span>
    </span>
@elseif ($statusLower === 'selesai')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-tag bg-beton text-abu text-sm font-semibold">
        <span>Selesai</span>
    </span>
@elseif ($statusLower === 'ditolak')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-tag bg-merah text-putih text-sm font-semibold">
        <span>Ditolak</span>
    </span>
@elseif ($statusLower === 'nonaktif')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-tag bg-beton text-abu border border-abu text-sm font-semibold">
        <span>Nonaktif</span>
    </span>
@else
    <span class="inline-flex items-center px-2.5 py-1 rounded-tag bg-beton text-aspal text-sm font-semibold">
        {{ $status }}
    </span>
@endif
