<x-layout title="{{ Lang::get('main.titles.home') }}"
          body-class="bg-blue-700 bg-image-primary bg-image-responsive flex flex-col min-h-screen"
          with-actions-menu
          with-analytics
          with-footer
>
    <main class="flex flex-1 items-center justify-center my-12 w-full">
        <div class="bg-white border max-w-xl md:w-2/3 mx-8 p-8 rounded-lg shadow-lg text-center w-full">
            <a href="{{ route('home') }}">
                <div class="bg-blue-700 flex h-24 items-center justify-center m-auto mb-6 rounded-full w-24">
                    <img src="{{ Vite::asset('resources/images/brand/everestserver-logo.svg') }}" alt="{{ Lang::get('home.logo-alt') }}" class="flex h-16 pb-1 w-16">
                </div>
            </a>
            <h1 class="mb-6 text-3xl">
                {!! Lang::get('home.welcome') !!}!
            </h1>
            <div class="bg-gray-100 border p-4 rounded-lg">
                <p class="mb-4 text-blue-700">
                    {{ Lang::get('home.choose-service') }}
                </p>
                <div class="flex flex-col items-center justify-evenly md:flex-row gap-4 md:gap-2">
                    <x-service-button type="everestcloud"
                                      link="https://cloud.everestserver.pl"
                    />
                    <x-service-button type="everestpass"
                                      link="https://pass.everestserver.pl"
                    />
                    <x-service-button type="everestgit"
                                      link="https://git.everestserver.pl"
                    />
                </div>
            </div>
        </div>
    </main>
</x-layout>
