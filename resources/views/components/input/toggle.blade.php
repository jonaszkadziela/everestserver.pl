@props([
    'label' => false,
    'size' => 'md',
    'checked' => false,
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'w-9 h-5 after:h-4 after:w-4',
        'md' => 'w-11 h-6 after:h-5 after:w-5',
        'lg' => 'w-14 h-7 after:h-6 after:w-6 after:top-0.5 after:start-[5px]',
    };
@endphp

<label class="cursor-pointer inline-flex items-center relative">
    <input {{ $attributes->merge(['class' => 'sr-only peer']) }}
           type="checkbox"
           @checked($checked)
    >
    <div class="{{ $sizeClasses }} after:absolute after:bg-white after:border after:border-gray-300 after:content-[''] after:rounded-full after:start-[2px] after:top-[2px] after:transition-all bg-gray-200 dark:bg-gray-700 dark:border-gray-600 peer peer-checked:after:border-white peer-checked:after:translate-x-full peer-checked:bg-blue-600 peer-focus:outline relative rounded-full rtl:peer-checked:after:-translate-x-full"
    ></div>
    @if ($icons->isNotEmpty())
        {{ $icons }}
    @endif
    @if ($slot->isNotEmpty())
        <span class="ml-2 text-sm text-gray-900 dark:text-gray-300">
            {{ $slot }}
        </span>
    @endisset
</label>
