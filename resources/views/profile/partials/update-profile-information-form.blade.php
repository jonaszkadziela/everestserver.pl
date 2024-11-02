<section>
    <header>
        <h2 class="dark:text-white font-medium text-gray-900 text-lg">
            {{ Lang::get('profile.update-profile-information.title') }}
        </h2>
        <p class="dark:text-gray-300 mt-1 text-gray-600 text-sm">
            {{ Lang::get('profile.update-profile-information.description') }}.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input.label for="username" :value="Lang::get('validation.attributes.username')" />
            <x-input.text id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autofocus autocomplete="username" />
            <x-input.error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input.label for="email" :value="Lang::get('validation.attributes.email')" />
            <x-input.text id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input.error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ Lang::get('profile.update-profile-information.email-unverified') }}.

                        <button form="send-verification" class="dark:hover:text-gray-400 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2 hover:text-gray-900 rounded-md text-gray-600 text-sm underline">
                            {{ Lang::get('profile.update-profile-information.resend-email') }}.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ Lang::get('profile.update-profile-information.verification-resent') }}.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-button.submit>
                {{ Lang::get('profile.actions.save') }}
            </x-button.submit>

            @if (session('status') === 'profile-updated')
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
