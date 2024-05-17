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
        <div class="lg:px-8 max-w-7xl mx-auto sm:px-6 w-full">
            <div class="bg-white overflow-hidden p-6 shadow-sm sm:rounded-lg text-gray-900">
                <p>
                    {{ Lang::get('dashboard.login-confirmation') }}
                </p>
            </div>
        </div>
    </main>
</x-main-layout>
