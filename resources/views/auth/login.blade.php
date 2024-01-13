<x-main-layout title="{{ Lang::get('main.titles.login') }}"
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
                    {{ Lang::get('auth.login.title') }}
                </h1>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="Lang::get('auth.attributes.email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="Lang::get('auth.attributes.password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                  type="password"
                                  name="password"
                                  autocomplete="current-password"
                                  required
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="cursor-pointer inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-700" name="remember">
                        <span class="ms-2 text-sm text-gray-600">
                            {{ Lang::get('auth.login.remember-me') }}
                        </span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700" href="{{ route('password.request') }}">
                            {{ Lang::get('auth.login.forgot-password') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3">
                        {{ Lang::get('auth.login.log-in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </main>
</x-main-layout>
