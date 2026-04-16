<a {{ $attributes->merge(['class' => 'flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600']) }}>
    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
        <!-- Icon placeholder - ganti sesuai icon yang dibutuhkan -->
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
    </svg>
    {{ $slot }}
</a>
