@props([
    'disabled' => false,
    'options' => [],
    'selected' => null,
])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm w-full']) !!}>
    @foreach ($options as $key => $value)
        <option value="{{ $key }}" {{ $key === $selected ? 'selected' : '' }}>
            {{ $value }}
        </option>
    @endforeach
</select>
