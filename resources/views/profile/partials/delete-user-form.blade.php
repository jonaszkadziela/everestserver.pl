<section class="space-y-6">
    <header>
        <h2 class="font-medium text-gray-900 text-lg">
            {{ Lang::get('profile.delete-user.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ Lang::get('profile.delete-user.description') }}.
        </p>

        <p class="text-sm text-gray-600">
            {{ Lang::get('profile.delete-user.description-2') }}.
        </p>
    </header>

    <x-modal.danger-button x-data
                           @click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        <span class="mr-2">
            {{ Lang::get('profile.actions.delete-account') }}
        </span>
        <i class="fa-solid fa-square-arrow-up-right"></i>
    </x-modal.danger-button>

    <x-modal.main name="confirm-user-deletion" :show="$errors->deleteProfile->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-medium mb-6 text-gray-900 text-lg">
                {{ Lang::get('profile.delete-user.modal.title') }}
            </h2>

            <p class="text-sm text-gray-600">
                {{ Lang::get('profile.delete-user.modal.description') }}.
            </p>

            <p class="text-sm text-gray-600">
                {{ Lang::get('profile.delete-user.modal.confirm') }}.
            </p>

            <div class="mt-6">
                <x-input-label for="password" :value="Lang::get('validation.attributes.password')" />

                <x-password-input id="password"
                                  name="password"
                                  class="mt-1"
                                  required
                />

                <x-input-error :messages="$errors->deleteProfile->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-modal.secondary-button x-on:click="$dispatch('close')">
                    {{ Lang::get('main.actions.cancel') }}
                </x-modal.secondary-button>

                <x-modal.danger-button class="ms-3">
                    {{ Lang::get('profile.actions.delete-account') }}
                </x-modal.danger-button>
            </div>
        </form>
    </x-modal.main>
</section>
