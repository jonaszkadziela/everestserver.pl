<x-main-layout title="{{ Lang::get('main.titles.verify-email') }}"
               body-class="bg-blue-700 bg-image-primary bg-image-responsive flex flex-col min-h-screen"
               with-actions-menu
               with-analytics
               with-footer
>
    <main class="flex flex-1 items-center justify-center my-12 w-full">
        <div class="bg-white border dark:bg-gray-800 max-w-xl md:w-2/3 mx-8 p-8 rounded-lg shadow-lg text-center w-full">
            <a href="{{ route('home') }}" class="inline-block mb-6">
                <div class="bg-blue-700 flex h-24 items-center justify-center m-auto rounded-full w-24">
                    <x-application-logo class="block h-16 pb-1 w-16" />
                </div>
            </a>
            <h1 class="dark:text-white mb-6 text-3xl">
                {{ Lang::get('auth.verify-email.title') }}
            </h1>
            <div class="dark:text-gray-300 mb-4 text-gray-600 text-sm">
                {{ Lang::get('auth.verify-email.description') }}
            </div>
            <div class="dark:text-gray-300 mb-4 text-gray-600 text-sm">
                {{ Lang::get('auth.verify-email.description-2') }}.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ Lang::get('auth.verify-email.verification-link-sent') }}.
                </div>
            @endif

            <div class="mt-4 flex items-center justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="dark:hover:text-gray-400 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 hover:text-gray-900 rounded-md text-gray-600 text-sm underline">
                        {{ Lang::get('auth.actions.log-out') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <x-button.submit class="ms-4">
                            {{ Lang::get('auth.verify-email.resend-verification') }}
                        </x-button.submit>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-main-layout>
