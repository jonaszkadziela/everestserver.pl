@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-blue-50 block border-blue-400 border-l-4 dark:bg-gray-900 duration-150 ease-in-out focus:bg-blue-100 focus:border-blue-700 focus:outline-none focus:text-blue-800 font-medium pe-4 ps-3 py-2 text-base text-blue-700 text-start transition w-full'
            : 'block border-l-4 border-transparent dark:focus:bg-gray-900 dark:focus:text-gray-300 dark:text-gray-300 duration-150 ease-in-out focus:bg-gray-50 focus:border-gray-300 focus:outline-none focus:text-gray-800 font-medium hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 pe-4 ps-3 py-2 text-base text-gray-600 text-start transition w-full';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
