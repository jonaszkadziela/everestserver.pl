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
            {!!
                Lang::has('services.labels.' . Str::lower($service->name)) ?
                    Lang::get('services.labels.' . Str::lower($service->name)) :
                    $service->name
            !!}
        </p>
    </a>
    <button class="absolute cursor-help px-2 py-1 right-0 top-0"
            x-on:click="$dispatch('info-service', @js($service)); $dispatch('open-modal', 'info-service');"
    >
        <i class="fa-solid fa-info-circle"></i>
    </button>
</div>
