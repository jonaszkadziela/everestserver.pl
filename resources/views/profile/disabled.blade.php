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
            <div class="bg-white dark:bg-gray-800 p-4 relative shadow sm:p-8 sm:rounded-lg">
                <h3 class="dark:text-white font-medium mb-3 text-lg">
                    {{ Lang::get('profile.disabled.title') }}
                </h3>
                <p class="dark:text-gray-300 text-gray-600">
                    {{ Lang::get('profile.disabled.description') }}.
                </p>
                <p class="dark:text-gray-300 text-gray-600">
                    {{ Lang::get('profile.disabled.description-2') }}
                    <a href="{{ 'mailto:' . Lang::get('main.contact-email') }}"
                       class="dark:hover:text-blue-700 dark:text-blue-500 hover:text-blue-900 text-blue-700"
                    >
                        {{ Lang::get('main.contact-email') }}.
                    </a>
                </p>
            </div>
        </div>
    </main>
</x-main-layout>
