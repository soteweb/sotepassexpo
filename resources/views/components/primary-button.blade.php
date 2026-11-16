<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-slate-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-slate-800 active:scale-[0.99] focus:outline-none focus:ring-1 focus:ring-slate-900 transition-all duration-150 shadow-xs cursor-pointer disabled:opacity-50']) }}>
    {{ $slot }}
</button>
