<x-main-layout title="{{ Lang::get('main.titles.register') }}"
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
                    {{ Lang::get('auth.register.title') }}
                </h1>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Username -->
                <div>
                    <x-input.label for="username" :value="Lang::get('validation.attributes.username')" />
                    <x-input.text id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                    <x-input.error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input.label for="email" :value="Lang::get('validation.attributes.email')" />
                    <x-input.text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                    <x-input.error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input.label for="password" :value="Lang::get('validation.attributes.password')" />

                    <x-input.password id="password"
                                      class="mt-1"
                                      name="password"
                                      autocomplete="new-password"
                                      required
                    />

                    <x-input.error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input.label for="password_confirmation" :value="Lang::get('validation.attributes.password_confirmation')" />

                    <x-input.password id="password_confirmation"
                                      class="mt-1"
                                      name="password_confirmation"
                                      autocomplete="new-password"
                                      required
                    />

                    <x-input.error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-link.secondary href="{{ route('login') }}">
                        {{ Lang::get('auth.register.already-registered') }}
                    </x-link.secondary>

                    <x-button.submit class="ms-4">
                        {{ Lang::get('auth.register.register') }}
                    </x-button.submit>
                </div>
            </form>

            @if (config('services.facebook.enabled') || config('services.google.enabled'))
                <div class="flex items-center my-3">
                    <div class="border-gray-300 border-t w-full"></div>
                    <span class="dark:text-gray-300 lowercase px-2 text-gray-600">
                        {{ Lang::get('main.menu.or') }}
                    </span>
                    <div class="border-gray-300 border-t w-full"></div>
                </div>

                <!-- External Auth Providers -->
                <div class="flex flex-col gap-2 justify-center md:flex-row">
                    @if(config('services.facebook.enabled'))
                        <x-button.facebook class="w-full">
                            {{ Lang::get('main.menu.register-with-provider', ['provider' => 'Facebook']) }}
                        </x-button.facebook>
                    @endif
                    @if(config('services.google.enabled'))
                        <x-button.google class="w-full">
                            {{ Lang::get('main.menu.register-with-provider', ['provider' => 'Google']) }}
                        </x-button.google>
                    @endif
                </div>
            @endif
        </div>
    </main>
</x-main-layout>
