@props(['warna' => 'hijau', 'href' => null])

@php
$warnaClass = match($warna) {
    'kuning' => 'tombol-arah--kuning',
    'cokelat' => 'tombol-arah--cokelat',
    default => 'tombol-arah--hijau',
};
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "tombol-arah {$warnaClass}"]) }}>
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="submit" {{ $attributes->merge(['class' => "tombol-arah {$warnaClass}"]) }}>
        <span>{{ $slot }}</span>
    </button>
@endif
