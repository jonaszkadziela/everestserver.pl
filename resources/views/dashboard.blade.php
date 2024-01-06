<x-app-layout title="{{ Lang::get('main.titles.dashboard') }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-6 shadow-sm sm:rounded-lg text-gray-900">
                <p>
                    {{ __("You're logged in!") }}
                </p>
                @if (Auth::user()->email_verified_at === null)
                    <p class="mt-2">
                        {{ __("Please verify your email") }}
                        <a href="{{ route('verification.notice') }}" class="text-blue-700 hover:text-blue-900">here</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
