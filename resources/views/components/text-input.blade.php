@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'h-[50px] px-3.5 border-2 border-abu bg-putih text-aspal text-[17px] rounded-kontrol focus:border-aspal focus:ring-0 focus:outline-none disabled:bg-beton disabled:text-abu w-full transition-none']) }}>
