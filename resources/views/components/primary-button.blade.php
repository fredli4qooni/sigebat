<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center h-11 px-5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all cursor-pointer shadow-xs disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
