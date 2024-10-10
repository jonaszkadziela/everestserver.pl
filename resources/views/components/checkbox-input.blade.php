@props([
    'checked' => false,
    'id',
])

<label for="{{ $id }}" class="cursor-pointer inline-flex items-center">
    <input id="{{ $id }}"
           name="{{ $id }}"
           type="checkbox"
           class="border-gray-300 cursor-pointer focus:ring-blue-700 rounded shadow-sm text-blue-600"
           @checked($checked)
    >
    <span class="ms-2 text-sm text-gray-600">
        {{ $slot }}
    </span>
</label>
