<section>
    <header>
        <h2 class="dark:text-white font-medium text-gray-900 text-lg">
            {{ Lang::get('profile.update-password.title') }}
        </h2>
        <p class="dark:text-gray-300 mt-1 text-gray-600 text-sm">
            {{ Lang::get('profile.update-password.description') }}.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input.label for="update_password_current_password" :value="Lang::get('validation.attributes.current_password')" />

            <x-input.password id="update_password_current_password"
                              name="current_password"
                              class="mt-1"
                              autocomplete="current-password"
                              required
            />

            <x-input.error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input.label for="update_password_password" :value="Lang::get('validation.attributes.new_password')" />

            <x-input.password id="update_password_password"
                              name="password"
                              class="mt-1"
                              autocomplete="new-password"
                              required
            />

            <x-input.error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input.label for="update_password_password_confirmation" :value="Lang::get('validation.attributes.new_password_confirmation')" />

            <x-input.password id="update_password_password_confirmation"
                              name="password_confirmation"
                              class="mt-1"
                              autocomplete="new-password"
                              required
            />

            <x-input.error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-button.submit>
                {{ Lang::get('profile.actions.save') }}
            </x-button.submit>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600"
                >
                    {{ Lang::get('profile.actions.save-confirmation') }}
                </p>
            @endif
        </div>
    </form>
</section>
