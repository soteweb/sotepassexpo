<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-slate-900 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:bg-black active:scale-95 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:ring-offset-2 transition-all duration-150 shadow-sm cursor-pointer disabled:opacity-50']) }}>
    {{ $slot }}
</button>
