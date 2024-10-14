<div class="flex flex-col gap-2 items-start justify-between mb-8 md:flex-row">
    <div class="flex flex-col">
        <h3 class="font-semibold text-xl mb-2">
            {{ Lang::get('admin.panel.users.title') }}
        </h3>
        <p class="text-gray-600 text-sm">
            {{ Lang::get('admin.panel.users.description') }}.
        </p>
    </div>
    <x-primary-button x-data
                      @click.prevent="$dispatch('open-modal', 'create-user')"
                      class="flex-shrink-0"
    >
        <span class="mr-2">
            {{ Lang::get('admin.panel.users.create-user.title') }}
        </span>
        <i class="fa-solid fa-user-plus"></i>
    </x-primary-button>
</div>

<div class="bg-white overflow-x-auto rounded-lg shadow text-left">
    @if (!empty($data['transformed']))
        <table class="w-full">
            <thead>
                <tr>
                    @foreach ($data['transformed'][0] as $key => $value)
                        <th class="bg-slate-50 border-b p-4 @if($loop->first) rounded-tl-lg @endif">
                            {{ Lang::get('admin.panel.users.columns.' . $key) }}
                        </th>
                    @endforeach
                    <th class="bg-slate-50 border-b p-4 rounded-tr-lg">
                        {{ Lang::get('admin.panel.users.columns.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['transformed'] as $row)
                    <tr class="{{ $loop->last ? '' : 'border-b' }}">
                        @foreach ($row as $key => $value)
                            <td class="p-4">
                                {{ $value }}
                            </td>

                            @if ($loop->last)
                                <td class="p-4">
                                    <button x-data
                                            @click.prevent="$dispatch('update-user', @js($data['raw'][$loop->parent->index])); $dispatch('open-modal', 'update-user')"
                                            class="text-blue-700 hover:text-blue-900"
                                            type="button"
                                    >
                                        {{ Lang::get('admin.panel.users.edit') }}
                                    </button>
                                    <span>|</span>
                                    <button x-data
                                            @click.prevent="$dispatch('delete-user', @js($data['raw'][$loop->parent->index])); $dispatch('open-modal', 'delete-user')"
                                            class="text-blue-700 hover:text-blue-900"
                                            type="button"
                                    >
                                        {{ Lang::get('admin.panel.users.delete') }}
                                    </button>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="p-4">
            {{ Lang::get('admin.panel.users.no-results') }}
        </p>
    @endif
</div>

<x-modal.main name="create-user" :show="$errors->createUser->isNotEmpty()" focusable>
    <form method="post" action="{{ route('users.store') }}" class="p-6">
        @csrf
        <h2 class="font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.users.create-user.title') }}
        </h2>
        <p class="text-sm text-gray-600">
            {{ Lang::get('admin.panel.users.create-user.description') }}.
        </p>
        <p class="text-sm text-gray-600">
            {{ Lang::get('admin.panel.users.create-user.description-2') }}.
        </p>
        <div class="mt-6">
            <x-input-label for="username" :value="Lang::get('validation.attributes.username')" />
            <x-text-input id="username"
                          name="username"
                          class="block mt-1 w-full"
                          required
                          :value="old('username')"
            />
            <x-input-error :messages="$errors->createUser->get('username')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" :value="Lang::get('validation.attributes.email')" />
            <x-text-input id="email"
                          name="email"
                          class="block mt-1 w-full"
                          type="email"
                          required
                          :value="old('email')"
            />
            <x-input-error :messages="$errors->createUser->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value="Lang::get('validation.attributes.password') . ' (' . Lang::get('main.optional') . ')'" />
            <x-password-input id="password"
                              name="password"
                              class="mt-1"
            />
            <x-input-error :messages="$errors->createUser->get('password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="language" :value="Lang::get('validation.attributes.language')" />
            <x-select-input id="language"
                            name="language"
                            class="mt-1"
                            required
                            :value="old('language')"
                            :options="collect(config('app.languages'))->mapWithKeys(fn (string $code) => [$code => Lang::get('main.languages.' . $code)])"
            />
            <x-input-error :messages="$errors->createUser->get('language')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label :value="Lang::get('admin.panel.users.create-user.toggles')" />
            <div class="flex flex-col gap-2 md:flex-row md:gap-8 mt-1">
                <x-checkbox-input id="is_admin"
                                  name="is_admin"
                                  :checked="old('is_admin')"
                >
                    {{ Lang::get('admin.panel.users.create-user.is_admin') }}
                </x-checkbox-input>
                <x-checkbox-input id="is_enabled"
                                  name="is_enabled"
                                  :checked="old('is_enabled')"
                >
                    {{ Lang::get('admin.panel.users.create-user.is_enabled') }}
                </x-checkbox-input>
                <x-checkbox-input id="is_verified"
                                  name="is_verified"
                                  :checked="old('is_verified')"
                >
                    {{ Lang::get('admin.panel.users.create-user.is_verified') }}
                </x-checkbox-input>
            </div>
            <x-input-error :messages="$errors->createUser->get('is_admin')" class="mt-2" />
            <x-input-error :messages="$errors->createUser->get('is_enabled')" class="mt-2" />
            <x-input-error :messages="$errors->createUser->get('is_verified')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.users.create-user.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>

<x-modal.main name="update-user" :show="$errors->updateUser->isNotEmpty()" focusable>
    <form method="post"
          class="p-6"
          x-bind:action="action.slice(0, -2).concat(form.id || '{{ old('id') }}')"
          x-data="{ action: '{{ route('users.update', ['user' => -1]) }}', form: {} }"
          x-on:update-user.window="form = $event.detail"
    >
        @csrf
        @method('patch')
        <h2 class="font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.users.update-user.title') }}
        </h2>
        <p class="text-sm text-gray-600">
            {{ Lang::get('admin.panel.users.update-user.description') }}.
        </p>
        <input type="hidden" name="id" x-bind:value="form.id || '{{ old('id') }}'">
        <div class="mt-6">
            <x-input-label for="new_username" :value="Lang::get('validation.attributes.username')" />
            <x-text-input id="new_username"
                          name="new_username"
                          class="block mt-1 w-full"
                          required
                          x-bind:value="form.username || '{{ old('new_username') }}'"
            />
            <x-input-error :messages="$errors->updateUser->get('new_username')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="new_email" :value="Lang::get('validation.attributes.email')" />
            <x-text-input id="new_email"
                          name="new_email"
                          class="block mt-1 w-full"
                          type="email"
                          required
                          x-bind:value="form.email || '{{ old('new_email') }}'"
            />
            <x-input-error :messages="$errors->updateUser->get('new_email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="new_password" :value="Lang::get('validation.attributes.password') . ' (' . Lang::get('main.optional') . ')'" />
            <x-password-input id="new_password"
                              name="new_password"
                              class="mt-1"
            />
            <x-input-error :messages="$errors->updateUser->get('new_password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="new_language" :value="Lang::get('validation.attributes.language')" />
            <x-select-input id="new_language"
                            name="new_language"
                            class="mt-1"
                            required
                            :value="old('new_language')"
                            :options="collect(config('app.languages'))->mapWithKeys(fn (string $code) => [$code => Lang::get('main.languages.' . $code)])"
            />
            <x-input-error :messages="$errors->updateUser->get('new_language')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label :value="Lang::get('admin.panel.users.update-user.toggles')" />
            <div class="flex flex-col gap-2 md:flex-row md:gap-8 mt-1">
                <x-checkbox-input id="new_is_admin"
                                  name="new_is_admin"
                                  x-bind:value="form.is_admin === true || '{{ old('new_is_admin') }}' === 'on'"
                >
                    {{ Lang::get('admin.panel.users.update-user.is_admin') }}
                </x-checkbox-input>
                <x-checkbox-input id="new_is_enabled"
                                  name="new_is_enabled"
                                  x-bind:value="form.is_enabled === true || '{{ old('new_is_enabled') }}' === 'on'"
                >
                    {{ Lang::get('admin.panel.users.update-user.is_enabled') }}
                </x-checkbox-input>
                <x-checkbox-input id="new_is_verified"
                                  name="new_is_verified"
                                  x-bind:value="form.email_verified_at !== null || '{{ old('new_is_verified') }}' === 'on'"
                >
                    {{ Lang::get('admin.panel.users.update-user.is_verified') }}
                </x-checkbox-input>
            </div>
            <x-input-error :messages="$errors->updateUser->get('new_is_admin')" class="mt-2" />
            <x-input-error :messages="$errors->updateUser->get('new_is_enabled')" class="mt-2" />
            <x-input-error :messages="$errors->updateUser->get('new_is_verified')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.users.update-user.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>

<x-modal.main name="delete-user" :show="$errors->deleteUser->isNotEmpty()">
    <form method="post"
          class="p-6"
          x-bind:action="action.slice(0, -2).concat(form.id)"
          x-data="{ action: '{{ route('users.destroy', ['user' => -1]) }}', form: {} }"
          x-on:delete-user.window="form = $event.detail"
    >
        @csrf
        @method('delete')
        <h2 class="font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.users.delete-user.title') }}
        </h2>
        <p class="text-sm text-gray-600">
            {{ Lang::get('admin.panel.users.delete-user.description') }} <strong x-text="form.username"></strong>?
        </p>
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.users.delete-user.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>
