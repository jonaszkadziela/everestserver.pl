@php
    use App\Models\Service;
@endphp

<x-main-layout title="{{ Lang::get('main.titles.dashboard') }}"
               body-class="bg-gray-100 dark:bg-gray-900 flex flex-col min-h-screen"
               with-analytics
               with-navigation
               with-footer
>
    <x-slot name="header">
        <h2 class="dark:text-white font-semibold leading-tight text-gray-800 text-xl">
            {{ Lang::get('main.titles.dashboard') }}
        </h2>
    </x-slot>

    @php($enabledServices = Auth::user()->is_admin ? Service::enabled()->get() : Service::public()->enabled()->get())
    @php($linkedServices = Auth::user()->services()->enabled()->get())
    @php($unlinkedServices = $enabledServices->diff($linkedServices))

    <main class="flex flex-1 flex-col my-12 w-full">
        <div class="lg:px-8 max-w-7xl mx-auto sm:px-6 space-y-6 w-full">
            @forelse ($linkedServices as $service)
                <div class="bg-white dark:bg-gray-800 p-4 relative shadow sm:p-8 sm:rounded-lg">
                    <div class="flex gap-4 items-start justify-between">
                        <div>
                            <h2 class="dark:text-white flex font-medium gap-2 items-center text-gray-900 text-lg">
                                <i class="!text-2xl {{ $service->icon }}"></i>
                                {{ $service->name }}
                            </h2>
                            <p class="dark:text-gray-300 mt-1 text-gray-600 text-sm">
                                {{ $service->description }}.
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 items-end shrink-0">
                            <a href="{{ $service->link }}"
                               class="bg-blue-700 border hover:bg-blue-800 px-4 py-2 relative rounded-full text-white"
                               x-bind:class="loading ? 'pointer-events-none' : ''"
                               x-data="{ loading: false }"
                               @click="loading = true"
                               @pageshow.window="loading = false"
                            >
                                <span x-show="loading"
                                      x-cloak
                                      class="absolute flex inset-0 items-center justify-center"
                                >
                                    <i class="fa-circle-notch fa-solid fa-spin"></i>
                                </span>
                                <span class="flex gap-1 items-center justify-center" x-bind:class="loading ? 'opacity-0' : ''">
                                    {{ Lang::get('services.navigate') }}
                                    <i class="fa-arrow-right fa-fw fa-solid"></i>
                                </span>
                            </a>
                            @if ($service->pivot->identifier !== null)
                                <p class="dark:text-white text-right text-xs">
                                    <span class="block font-bold">
                                        {{ Lang::get('services.identifier') }}
                                    </span>
                                    {{ $service->pivot->identifier }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 p-4 relative shadow sm:p-8 sm:rounded-lg">
                    @if ($enabledServices->count() > 0)
                        <p class="dark:text-white font-bold mb-4">
                            {{ Lang::get('services.not-linked') }}
                        </p>
                        <x-button.link-service modal="link-service">
                            {{ Lang::get('services.link-new') }}
                        </x-button.link-service>
                    @else
                        <p class="font-bold">
                            {{ Lang::get('services.not-available') }}
                            <i class="fa-regular fa-face-frown ml-0.5"></i>
                        </p>
                        {{ Lang::get('main.try-again-later') }}
                    @endif
                </div>
            @endforelse
            @if ($linkedServices->count() > 0 && $unlinkedServices->count() > 0)
                <div class="bg-white dark:bg-gray-800 p-4 relative shadow sm:p-8 sm:rounded-lg">
                    <p class="dark:text-white font-bold mb-4 text-gray-900">
                        {{ Lang::get('services.link-more-services') }}
                    </p>
                    <x-button.link-service modal="link-service">
                        {{ Lang::get('services.link-new') }}
                    </x-button.link-service>
                </div>
            @endif
        </div>
        <x-modal.main name="link-service">
            <form method="post" action="{{ route('services.link') }}" class="p-6">
                @csrf
                <h2 class="dark:text-white flex font-medium gap-2 items-center mb-6 text-gray-900 text-lg">
                    <i class="fa-solid fa-square-plus text-2xl"></i>
                    {{ Lang::get('services.link-modal.title') }}
                </h2>
                <p class="dark:text-gray-300 mb-2 text-gray-600 text-sm">
                    {{ Lang::get('services.link-modal.description') }}.
                </p>
                <p class="dark:text-gray-300 mb-6 text-gray-600 text-sm">
                    {{ Lang::get('services.link-modal.description-2') }}.
                </p>
                <div>
                    <x-input.label for="serviceId" value="{{ Lang::get('services.link-modal.choose-service') }}" />
                    <x-input.select id="serviceId"
                                    name="serviceId"
                                    class="mt-1"
                                    :options="$unlinkedServices->mapWithKeys(fn (Service $service) => [$service->id => $service->name])"
                                    :selected="old('serviceId')"
                    />
                </div>
                <div class="mt-6 flex justify-end">
                    <x-modal.secondary-button @click="$dispatch('close')">
                        {{ Lang::get('main.actions.cancel') }}
                    </x-modal.secondary-button>
                    <x-modal.primary-button class="ms-3">
                        {{ Lang::get('services.link-modal.link-service') }}
                    </x-modal.primary-button>
                </div>
            </form>
        </x-modal.main>
    </main>
</x-main-layout>
