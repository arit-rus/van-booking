<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-400 to-orange-500 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg shadow-amber-500/30 hover:from-amber-500 hover:to-orange-600 hover:shadow-amber-500/40 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 ease-out']) }}>
    {{ $slot }}
</button>
