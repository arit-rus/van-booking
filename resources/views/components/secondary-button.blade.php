<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-200 rounded-xl font-semibold text-sm text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-300 hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 ease-out']) }}>
    {{ $slot }}
</button>
