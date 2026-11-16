<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 rounded-xl font-bold text-xs text-slate-700 uppercase tracking-wider shadow-2xs hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:ring-offset-2 disabled:opacity-25 transition-all duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
