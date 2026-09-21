@props(['warna' => 'slate'])

@php
$bgClass = match($warna) {
    'biru', 'sky' => 'bg-sky-900 border-sky-800 text-white',
    'hijau', 'emerald' => 'bg-emerald-900 border-emerald-800 text-white',
    'kuning', 'amber' => 'bg-amber-600 border-amber-500 text-white',
    'merah', 'rose' => 'bg-rose-900 border-rose-800 text-white',
    'cokelat' => 'bg-amber-950 border-amber-900 text-white',
    'aspal', 'slate' => 'bg-slate-900 border-slate-800 text-white',
    default => 'bg-slate-900 border-slate-800 text-white',
};
@endphp

<div {{ $attributes->merge(['class' => "rounded-2xl border {$bgClass} shadow-sm"]) }}>
    {{ $slot }}
</div>
