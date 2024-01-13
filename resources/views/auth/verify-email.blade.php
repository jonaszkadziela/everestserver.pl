<x-main-layout title="{{ Lang::get('main.titles.verify-email') }}"
               body-class="bg-blue-700 bg-image-primary bg-image-responsive flex flex-col min-h-screen"
               with-actions-menu
               with-analytics
               with-footer
>
    <main class="flex flex-1 items-center justify-center my-12 w-full">
        <div class="bg-white border max-w-xl md:w-2/3 mx-8 p-8 rounded-lg shadow-lg w-full">
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block mb-6">
                    <div class="bg-blue-700 flex h-24 items-center justify-center m-auto rounded-full w-24">
                        <x-application-logo class="block h-16 pb-1 w-16" />
                    </div>
                </a>
                <h1 class="mb-6 text-3xl">
                    {{ Lang::get('auth.verify-email.title') }}
                </h1>
                <div class="mb-4 text-sm text-gray-600">
                    {{ Lang::get('auth.verify-email.description') }}
                </div>
                <div class="mb-4 text-sm text-gray-600">
                    {{ Lang::get('auth.verify-email.description-2') }}.
                </div>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                        {{ Lang::get('auth.actions.log-out') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <x-primary-button class="ms-4">
                            {{ Lang::get('auth.verify-email.resend-verification') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-main-layout>
