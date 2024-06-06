<x-main-layout title="{{ Lang::get('main.titles.confirm-password') }}"
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
                    {{ Lang::get('auth.confirm-password.title') }}
                </h1>
                <div class="mb-4 text-sm text-gray-600">
                    {{ Lang::get('auth.confirm-password.description') }}.
                </div>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="Lang::get('validation.attributes.password')" />

                    <x-password-input id="password"
                                      class="mt-1"
                                      name="password"
                                      autocomplete="current-password"
                                      required
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end mt-4">
                    <x-submit-button>
                        {{ Lang::get('auth.confirm-password.confirm') }}
                    </x-submit-button>
                </div>
            </form>
        </div>
    </main>
</x-main-layout>
