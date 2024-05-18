@php
    use App\Models\Service;
@endphp

<x-main-layout title="{{ Lang::get('main.titles.dashboard') }}"
               body-class="bg-gray-100 flex flex-col min-h-screen"
               with-analytics
               with-navigation
               with-footer
>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Lang::get('main.titles.dashboard') }}
        </h2>
    </x-slot>

    <main class="flex flex-1 flex-col my-12 w-full">
        <div class="lg:px-8 max-w-7xl mx-auto sm:px-6 space-y-6 w-full">
            @forelse (Auth::user()->services()->enabled()->get() as $service)
                <div class="bg-white p-4 relative shadow sm:p-8 sm:rounded-lg">
                    <div class="flex gap-4 items-start justify-between">
                        <div>
                            <h2 class="flex font-medium gap-2 items-center text-gray-900 text-lg">
                                <i class="!text-2xl {{ $service->icon }}"></i>
                                {{ $service->name }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $service->description }}.
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 items-end">
                            <a href="{{ $service->link }}" class="bg-blue-700 border flex hover:bg-blue-800 items-center justify-center px-4 py-2 rounded-full text-white">
                                <span class="mr-2">
                                    {{ Lang::get('services.navigate') }}
                                </span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            @if ($service->pivot->identifier !== null)
                                <p class="text-xs text-right">
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
                <div>
                    @if (Service::enabled()->count() > 0)
                        <p class="font-bold">
                            {{ Lang::get('services.not-linked') }}
                        </p>
                    @else
                        <p class="font-bold">
                            {{ Lang::get('services.not-available') }}
                            <i class="fa-regular fa-face-frown ml-0.5"></i>
                        </p>
                        {{ Lang::get('main.try-again-later') }}
                    @endif
                </div>
            @endforelse
        </div>
    </main>
</x-main-layout>
