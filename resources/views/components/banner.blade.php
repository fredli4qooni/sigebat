@props(['jenis' => 'info'])

@php
$config = match($jenis) {
    'sukses', 'berhasil' => [
        'bg' => 'bg-hijau text-putih border-2 border-hijau-gelap',
        'icon' => '<path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"/>',
    ],
    'galat', 'danger', 'error' => [
        'bg' => 'bg-merah text-putih border-2 border-merah-gelap',
        'icon' => '<path d="M236.8,188,148.8,36a24,24,0,0,0-41.6,0L19.2,188A23.68,23.68,0,0,0,40,224H216a23.68,23.68,0,0,0,20.8-36ZM120,104a8,8,0,0,1,16,0v40a8,8,0,0,1-16,0Zm8,88a12,12,0,1,1,12-12A12,12,0,0,1,128,192Z"/>',
    ],
    'peringatan', 'warning' => [
        'bg' => 'bg-kuning text-aspal border-2 border-aspal',
        'icon' => '<path d="M236.8,188,148.8,36a24,24,0,0,0-41.6,0L19.2,188A23.68,23.68,0,0,0,40,224H216a23.68,23.68,0,0,0,20.8-36ZM120,104a8,8,0,0,1,16,0v40a8,8,0,0,1-16,0Zm8,88a12,12,0,1,1,12-12A12,12,0,0,1,128,192Z"/>',
    ],
    default => [
        'bg' => 'bg-biru text-putih border-2 border-biru-gelap',
        'icon' => '<path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm16-40a8,8,0,0,1-8,8,16,16,0,0,1-16-16V120a8,8,0,0,1,0-16,16,16,0,0,1,16,16v48A8,8,0,0,1,144,176ZM112,84a12,12,0,1,1,12,12A12,12,0,0,1,112,84Z"/>',
    ],
};
@endphp

<div x-data="{ open: true }" x-show="open" {{ $attributes->merge(['class' => "p-4 rounded-kontrol {$config['bg']} flex items-start gap-3 relative"]) }}>
    <svg class="w-6 h-6 flex-shrink-0 fill-current mt-0.5" viewBox="0 0 256 256">
        {!! $config['icon'] !!}
    </svg>
    <div class="flex-1 text-[16px] leading-snug">
        {{ $slot }}
    </div>
    <button type="button" @click="open = false" class="text-current opacity-80 hover:opacity-100 p-1 cursor-pointer">
        <svg class="w-5 h-5 fill-current" viewBox="0 0 256 256">
            <path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"/>
        </svg>
    </button>
</div>
