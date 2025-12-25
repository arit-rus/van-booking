<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg shadow-emerald-500/30 hover:from-emerald-600 hover:to-green-700 hover:shadow-emerald-500/40 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 ease-out']) }}>
    {{ $slot }}
</button>
