<x-main-layout title="{{ Lang::get('main.titles.reset-password') }}"
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
                    {{ Lang::get('auth.reset-password.title') }}
                </h1>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="Lang::get('validation.attributes.email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="Lang::get('validation.attributes.new_password')" />

                    <x-password-input id="password"
                                      class="mt-1"
                                      name="password"
                                      autocomplete="new-password"
                                      required
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="Lang::get('validation.attributes.new_password_confirmation')" />

                    <x-text-input id="password_confirmation"
                                  class="block mt-1 w-full"
                                  type="password"
                                  name="password_confirmation"
                                  autocomplete="new-password"
                                  required
                    />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-submit-button>
                        {{ Lang::get('auth.reset-password.save') }}
                    </x-submit-button>
                </div>
            </form>
        </div>
    </main>
</x-main-layout>
