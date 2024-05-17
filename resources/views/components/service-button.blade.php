@props(['service'])

<div class="bg-blue-700 flex h-24 hover:bg-blue-800 relative rounded-lg text-white transition-colors w-36"
     x-data="{ loading: false }"
     @pageshow.window="loading = false"
>
    <a href="{{ $service->link }}"
       class="flex justify-center flex-1 flex-col p-2"
       @click="loading = true"
    >
        <i x-bind:class="loading ? 'fa-circle-notch fa-solid fa-spin text-4xl' : '{{ $service->icon }}'"></i>
        <p class="font-light mt-1">
            {!! Lang::get('home.services.' . Str::lower($service->name)) !!}
        </p>
    </a>
    <button class="absolute cursor-help px-2 py-1 right-0 top-0"
            x-on:click="$dispatch('open-modal', 'info-{{ Str::lower($service->name) }}')"
    >
        <i class="fa-solid fa-info-circle"></i>
    </button>
</div>

<x-modal name="info-{{ Str::lower($service->name) }}">
    <div class="text-left p-6">
        <h2 class="font-medium mb-6 text-gray-900 text-lg">
            {{ $service->name }}
        </h2>

        <p class="text-sm text-gray-600">
            {{ $service->description }}.
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.close') }}
            </x-secondary-button>
        </div>
    </div>
</x-modal>
