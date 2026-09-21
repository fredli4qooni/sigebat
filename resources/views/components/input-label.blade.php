@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-[15px] text-aspal mb-1.5 leading-tight']) }}>
    {{ $value ?? $slot }}
</label>
