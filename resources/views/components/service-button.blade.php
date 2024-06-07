@props(['service'])

<div class="bg-blue-700 flex h-24 hover:bg-blue-800 relative rounded-lg text-white transition-colors w-36"
     x-data="{ loading: false }"
     @pageshow.window="loading = false"
>
    <a href="{{ $service->link }}"
       class="flex justify-center flex-1 flex-col p-2"
       @click="loading = true"
    >
        <i class="{{ $service->icon }}" x-bind:class="{ '{{ $service->icon }}': !loading, 'fa-circle-notch fa-solid fa-spin text-4xl': loading }"></i>
        <p class="font-light mt-1">
            {!! Lang::get('services.labels.' . Str::lower($service->name)) !!}
        </p>
    </a>
    <button class="absolute cursor-help px-2 py-1 right-0 top-0"
            x-on:click="$dispatch('open-modal', 'info-{{ Str::lower($service->name) }}')"
    >
        <i class="fa-solid fa-info-circle"></i>
    </button>
</div>

<x-modal.main name="info-{{ Str::lower($service->name) }}">
    <div class="text-left p-6">
        <h2 class="flex font-medium gap-2 items-center mb-6 text-gray-900 text-lg">
            <i class="!text-2xl {{ $service->icon }}"></i>
            {{ $service->name }}
        </h2>

        <p class="text-sm text-gray-600">
            {{ $service->description }}.
        </p>

        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.close') }}
            </x-modal.secondary-button>
        </div>
    </div>
</x-modal.main>
