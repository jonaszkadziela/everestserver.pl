@props([
    'disabled' => false,
    'options' => [],
])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block border-gray-300 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm w-full']) !!}>
    @foreach ($options as $key => $value)
        <option value="{{ $key }}">
            {{ $value }}
        </option>
    @endforeach
</select>
