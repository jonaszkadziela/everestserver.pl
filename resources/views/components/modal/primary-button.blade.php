<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'active:bg-blue-700 bg-blue-600 border border-transparent duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-semibold hover:bg-blue-500 inline-flex items-center px-4 py-2 rounded-md text-white text-xs tracking-widest transition uppercase',
]) }}>
    {{ $slot }}
</button>
