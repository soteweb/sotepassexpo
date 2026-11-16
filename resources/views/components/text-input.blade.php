@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-slate-50/50 hover:bg-slate-50 focus:bg-white text-sm text-slate-900 border border-slate-200 rounded-xl focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all placeholder:text-slate-400 shadow-2xs']) }}>

