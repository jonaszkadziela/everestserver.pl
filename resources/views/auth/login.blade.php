<x-main-layout title="{{ Lang::get('main.titles.login') }}"
               body-class="bg-blue-700 bg-image-primary bg-image-responsive flex flex-col min-h-screen"
               with-actions-menu
               with-analytics
               with-footer
>
    <main class="flex flex-1 items-center justify-center my-12 w-full">
        <div class="bg-white border dark:bg-gray-800 max-w-xl md:w-2/3 mx-8 p-8 rounded-lg shadow-lg w-full">
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block mb-6">
                    <div class="bg-blue-700 flex h-24 items-center justify-center m-auto rounded-full w-24">
                        <x-application-logo class="block h-16 pb-1 w-16" />
                    </div>
                </a>
                <h1 class="dark:text-white mb-6 text-3xl">
                    {{ Lang::get('auth.login.title') }}
                </h1>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Login -->
                <div>
                    <x-input.label for="login" :value="Lang::get('validation.attributes.login')" />
                    <x-input.text id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
                    <x-input.error :messages="$errors->get('login')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input.label for="password" :value="Lang::get('validation.attributes.password')" />

                    <x-input.password id="password"
                                      class="mt-1"
                                      name="password"
                                      autocomplete="current-password"
                                      required
                    />

                    <x-input.error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <x-input.checkbox id="remember"
                                      name="remember"
                                      :checked="old('remember')"
                    >
                        {{ Lang::get('auth.login.remember-me') }}
                    </x-input.checkbox>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="dark:hover:text-gray-400 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 hover:text-gray-900 rounded-md text-gray-600 text-sm underline" href="{{ route('password.request') }}">
                            {{ Lang::get('auth.login.forgot-password') }}
                        </a>
                    @endif

                    <x-button.submit class="ms-3">
                        {{ Lang::get('auth.login.log-in') }}
                    </x-button.submit>
                </div>
            </form>

            @if(config('services.facebook.enabled') || config('services.google.enabled'))
                <div class="flex items-center my-3">
                    <div class="border-gray-300 border-t w-full"></div>
                    <span class="lowercase px-2 text-gray-600">
                        {{ Lang::get('main.menu.or') }}
                    </span>
                    <div class="border-gray-300 border-t w-full"></div>
                </div>

                <!-- External Auth Providers -->
                <div class="flex flex-col gap-2 justify-center md:flex-row">
                    @if(config('services.facebook.enabled'))
                        <x-button.facebook class="w-full">
                            {{ Lang::get('main.menu.log-in-with-provider', ['provider' => 'Facebook']) }}
                        </x-button.facebook>
                    @endif
                    @if(config('services.google.enabled'))
                        <x-button.google class="w-full">
                            {{ Lang::get('main.menu.log-in-with-provider', ['provider' => 'Google']) }}
                        </x-button.google>
                    @endif
                </div>
            @endif
        </div>
    </main>
</x-main-layout>
