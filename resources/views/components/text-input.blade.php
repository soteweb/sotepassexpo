@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3 py-2 bg-white text-xs font-medium text-slate-900 border border-slate-300 rounded-md focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition placeholder:text-slate-400 shadow-2xs']) }}>

