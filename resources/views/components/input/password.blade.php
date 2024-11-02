@props([
    'disabled' => false,
    'class' => '',
])

<div class="border border-gray-300 flex focus-within:ring-2 focus-within:ring-blue-700 p-0 rounded-md shadow-sm {{ $class }}"
     x-data="{ show: false }"
>
    <input style="box-shadow: unset;"
           x-bind:type="show ? 'text' : 'password'"
           {!! $attributes->merge(['class' => 'border-0 dark:bg-gray-900 dark:text-gray-300 focus:outline-0 rounded-l-md w-full']) !!}
           {{ $disabled ? 'disabled' : '' }}
    >
    <button class="dark:text-gray-300 px-2"
            type="button"
            @click="show = !show"
    >
        <i class="fa-fw fa-regular" x-bind:class="show ? 'fa-eye' : 'fa-eye-slash'"></i>
    </button>
</div>
