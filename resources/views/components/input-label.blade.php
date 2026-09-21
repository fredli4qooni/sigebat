@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs sm:text-sm text-slate-700 mb-1.5 leading-tight']) }}>
    {{ $value ?? $slot }}
</label>
