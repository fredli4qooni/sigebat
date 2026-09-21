<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center h-[48px] px-6 bg-putih text-aspal border-2 border-aspal font-papan font-bold text-[17px] rounded-kontrol hover:bg-beton focus:outline-none transition-none cursor-pointer']) }}>
    {{ $slot }}
</button>
