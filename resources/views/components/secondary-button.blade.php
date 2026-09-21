<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center h-11 px-5 bg-white text-slate-700 border border-slate-300 font-semibold text-sm rounded-xl hover:bg-slate-50 active:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300 transition-all cursor-pointer shadow-xs disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
