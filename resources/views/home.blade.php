@php
    use App\Models\Service;
@endphp

<x-main-layout title="{{ Lang::get('main.titles.home') }}"
               body-class="bg-blue-700 bg-image-primary bg-image-responsive flex flex-col min-h-screen"
               with-actions-menu
               with-analytics
               with-footer
>
    <main class="flex flex-1 items-center justify-center my-12 w-full">
        <div class="bg-white border dark:bg-gray-800 max-w-xl md:w-2/3 mx-8 p-8 rounded-lg shadow-lg text-center w-full">
            <a href="{{ route('home') }}" class="inline-block mb-6">
                <div class="bg-blue-700 flex h-24 items-center justify-center m-auto rounded-full w-24">
                    <x-application-logo class="block h-16 pb-1 w-16" />
                </div>
            </a>
            <h1 class="dark:text-white mb-6 text-3xl">
                {!! Lang::get('home.welcome') !!}!
            </h1>
            <div class="bg-gray-100 border dark:bg-gray-700 p-4 rounded-lg">
                <p class="mb-4 text-blue-700">
                    {{ Lang::get('home.choose-service') }}
                </p>
                <div class="flex flex-col flex-wrap gap-4 items-center justify-center md:flex-row">
                    @forelse (Service::enabled()->public()->get() as $service)
                        <x-button.service :service="$service" />
                    @empty
                        <div>
                            <p class="font-bold">
                                {{ Lang::get('services.not-available') }}
                                <i class="fa-regular fa-face-frown ml-0.5"></i>
                            </p>
                            {{ Lang::get('main.try-again-later') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <x-modal.main name="info-service">
        <div x-data="{ service: { description: '' } }"
             x-on:info-service.window="service = $event.detail"
             class="text-left p-6"
        >
            <h2 class="dark:text-white flex font-medium gap-2 items-center mb-6 text-gray-900 text-lg">
                <i class="!text-2xl" x-bind:class="service.icon"></i>
                <span x-text="service.name"></span>
            </h2>

            <p class="dark:text-gray-300 text-gray-600 text-sm" x-text="service.description['{{ Lang::getLocale() }}'] + '.'"></p>

            <div class="mt-6 flex justify-end">
                <x-modal.secondary-button x-on:click="$dispatch('close')">
                    {{ Lang::get('main.actions.close') }}
                </x-modal.secondary-button>
            </div>
        </div>
    </x-modal.main>
</x-main-layout>
