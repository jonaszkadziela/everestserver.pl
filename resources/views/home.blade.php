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
        <div class="bg-white border max-w-xl md:w-2/3 mx-8 p-8 rounded-lg shadow-lg text-center w-full">
            <a href="{{ route('home') }}" class="inline-block mb-6">
                <div class="bg-blue-700 flex h-24 items-center justify-center m-auto rounded-full w-24">
                    <x-application-logo class="block h-16 pb-1 w-16" />
                </div>
            </a>
            <h1 class="mb-6 text-3xl">
                {!! Lang::get('home.welcome') !!}!
            </h1>
            <div class="bg-gray-100 border p-4 rounded-lg">
                <p class="mb-4 text-blue-700">
                    {{ Lang::get('home.choose-service') }}
                </p>
                <div class="flex flex-col items-center justify-center md:flex-row gap-4">
                    @forelse (Service::enabled()->public()->get() as $service)
                        <x-service-button :service="$service" />
                    @empty
                        <div>
                            <p class="font-bold">
                                {{ Lang::get('home.no-services') }}
                                <i class="fa-regular fa-face-frown ml-0.5"></i>
                            </p>
                            {{ Lang::get('main.try-again-later') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</x-main-layout>
