@props(['active'])

@php
$classes = ($active ?? false)
            ? 'border-b-2 border-blue-400 dark:text-white duration-150 ease-in-out focus:border-blue-700 focus:outline-none font-medium inline-flex items-center leading-5 pt-1 px-1 text-gray-900 text-sm transition'
            : 'border-b-2 border-transparent dark:hover:text-gray-400 dark:text-gray-300 duration-150 ease-in-out focus:border-gray-300 focus:outline-none focus:text-gray-700 font-medium hover:border-gray-300 hover:text-gray-700 inline-flex items-center leading-5 pt-1 px-1 text-gray-500 text-sm transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
