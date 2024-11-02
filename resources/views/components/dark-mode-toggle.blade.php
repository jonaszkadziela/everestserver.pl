@php
    use App\Http\Controllers\UserSettingController;
@endphp

@props(['value'])

<x-input.toggle x-data="{ checked: ('{{ $value }}' === '' ? window.matchMedia('(prefers-color-scheme: light)').matches : {{ $value === 'light' ? 'true' : 'false' }}) }"
                x-bind:value="checked"
                :checked="$value === 'light'"
                size="lg"
                @change="checked = $event.target.checked; window.location = `{{ action([UserSettingController::class, 'theme'], ['mode' => '/']) }}/${checked ? 'light' : 'dark'}`;"
>
    <x-slot:icons>
        <i class="absolute fa-moon fa-solid left-2.5 peer-checked:scale-0 scale-100 text-gray-600 transition-transform"></i>
        <i class="absolute fa-solid fa-sun peer-checked:scale-100 right-[7px] scale-0 text-gray-600 transition-transform"></i>
    </x-slot>
</x-input.toggle>
