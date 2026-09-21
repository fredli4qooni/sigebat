@props(['warna' => 'cokelat'])

@php
$warnaClass = match($warna) {
    'biru' => 'papan--biru',
    'hijau' => 'papan--hijau',
    'kuning' => 'papan--kuning',
    'aspal' => 'papan--aspal',
    'merah' => 'papan--merah',
    default => 'papan--cokelat',
};
@endphp

<div {{ $attributes->merge(['class' => "papan {$warnaClass}"]) }}>
    {{ $slot }}
</div>
