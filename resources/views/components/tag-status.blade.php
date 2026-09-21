@props(['status'])

@php
$statusLower = strtolower($status);
@endphp

@if ($statusLower === 'aktif')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        <span>Aktif</span>
    </span>
@elseif ($statusLower === 'pending')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold">
        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
        <span>Pending</span>
    </span>
@elseif ($statusLower === 'akan datang')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold">
        <span>Akan datang</span>
    </span>
@elseif ($statusLower === 'berlangsung')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500 text-white text-xs font-semibold shadow-xs">
        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
        <span>Berlangsung</span>
    </span>
@elseif ($statusLower === 'selesai')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 border border-slate-200 text-xs font-medium">
        <span>Selesai</span>
    </span>
@elseif ($statusLower === 'ditolak')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold">
        <span>Ditolak</span>
    </span>
@elseif ($statusLower === 'nonaktif')
    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 text-xs font-medium">
        <span>Nonaktif</span>
    </span>
@else
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-medium">
        {{ $status }}
    </span>
@endif
