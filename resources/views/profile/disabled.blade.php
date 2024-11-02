<x-main-layout title="{{ Lang::get('main.titles.profile') }}"
               body-class="bg-gray-100 dark:bg-gray-900 flex flex-col min-h-screen"
               with-analytics
               with-navigation
               with-footer
>
    <x-slot name="header">
        <h2 class="dark:text-white font-semibold leading-tight text-gray-800 text-xl">
            {{ Lang::get('main.titles.profile') }}
        </h2>
    </x-slot>

    <main class="flex flex-1 flex-col my-12 w-full">
        <div class="lg:px-8 max-w-7xl mx-auto sm:px-6 space-y-6 w-full">
            <div class="bg-white p-4 relative shadow sm:p-8 sm:rounded-lg">
                <h3 class="font-medium mb-3 text-lg">
                    {{ Lang::get('profile.disabled.title') }}
                </h3>
                <p>
                    {{ Lang::get('profile.disabled.description') }}.
                </p>
                <p>
                    {{ Lang::get('profile.disabled.description-2') }}
                    <a href="{{ 'mailto:' . Lang::get('main.contact-email') }}"
                       class="text-blue-700 hover:text-blue-900"
                    >
                        {{ Lang::get('main.contact-email') }}.
                    </a>
                </p>
            </div>
        </div>
    </main>
</x-main-layout>
