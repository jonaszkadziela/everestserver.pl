<x-app-layout title="{{ Lang::get('main.titles.dashboard') }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Lang::get('main.titles.dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-6 shadow-sm sm:rounded-lg text-gray-900">
                <p>
                    {{ Lang::get('dashboard.login-confirmation') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
