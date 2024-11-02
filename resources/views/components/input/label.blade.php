@props(['value'])

<label {{ $attributes->merge(['class' => 'block dark:text-gray-300 font-medium text-gray-700 text-sm']) }}>
    {{ $value ?? $slot }}
</label>
