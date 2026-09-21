@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'h-11 px-3.5 border border-slate-300 bg-white text-slate-800 text-sm rounded-xl focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none disabled:bg-slate-100 disabled:text-slate-400 w-full transition-all shadow-xs placeholder-slate-400']) }}>
