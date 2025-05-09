<x-main-layout title="{{ Lang::get('main.titles.admin.panel') }}"
               body-class="bg-gray-100 dark:bg-gray-900 flex flex-col min-h-screen"
               with-analytics
               with-navigation
               with-footer
>
    <x-slot name="header">
        <h2 class="dark:text-white font-semibold leading-tight text-gray-800 text-xl">
            {{ Lang::get('main.titles.admin.panel') }}
        </h2>
    </x-slot>

    <main class="flex flex-1 flex-col my-12 w-full">
        <div class="lg:px-8 max-w-7xl mx-auto sm:px-6 space-y-6 w-full">
            <div class="flex flex-col gap-4 md:flex-row">
                <nav class="flex flex-col gap-2 md:w-1/5 w-full">
                    @foreach ($tabs as $tab)
                        <a href="{{ route('admin.panel', ['tab' => $tab]) }}"
                           class="border rounded px-4 py-2 {{ $tab === $activeTab ? 'bg-blue-200 border-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800' : 'bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700' }}"
                        >
                            <span class="font-medium {{ $tab === $activeTab ? 'dark:text-white text-blue-800' : 'dark:text-gray-300 text-gray-800' }}">
                                {{ Lang::get('admin.panel.' . $tab . '.title') }}
                            </span>
                        </a>
                    @endforeach
                </nav>
                <div class="border"></div>
                <div class="md:w-4/5 w-full">
                    @if (View::exists('admin.tabs.' . $activeTab))
                        @include('admin.tabs.' . $activeTab)
                    @endif
                </div>
            </div>
        </div>
    </main>
</x-main-layout>
