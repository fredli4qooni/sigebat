<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center h-[48px] px-6 bg-aspal text-putih font-papan font-bold text-[17px] rounded-kontrol hover:bg-black focus:outline-none transition-none cursor-pointer disabled:bg-beton disabled:text-abu disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
