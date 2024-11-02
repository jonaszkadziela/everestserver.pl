@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm']) !!}>{{ $slot }}</textarea>
