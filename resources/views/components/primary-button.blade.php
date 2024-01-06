<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-blue-700 border flex hover:bg-blue-800 items-center justify-center px-4 py-2 rounded-full text-white']) }}>
    {{ $slot }}
</button>
