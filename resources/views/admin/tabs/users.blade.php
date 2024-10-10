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
                      @click.prevent="$dispatch('open-modal', 'add-user')"
                      class="flex-shrink-0"
    >
        <span class="mr-2">
            {{ Lang::get('admin.panel.users.add-user.title') }}
        </span>
        <i class="fa-solid fa-user-plus"></i>
    </x-primary-button>
</div>

<div class="bg-white overflow-x-auto rounded-lg shadow text-left">
    @if (!empty($data))
        <table class="w-full">
            <thead>
                <tr>
                    @foreach ($data[0] as $key => $value)
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
                @foreach ($data as $row)
                    <tr class="{{ $loop->last ? '' : 'border-b' }}">
                        @foreach ($row as $key => $value)
                            <td class="p-4">
                                {{ $value }}
                            </td>

                            @if ($loop->last)
                                <td class="p-4">
                                    <a href="#" class="text-blue-700 hover:text-blue-900">
                                        {{ Lang::get('admin.panel.users.edit') }}
                                    </a>
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

<x-modal.main name="add-user" :show="$errors->isNotEmpty()" focusable>
    <form method="post" action="{{ route('users.store') }}" class="p-6">
        @csrf
        <h2 class="font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.users.add-user.title') }}
        </h2>
        <p class="text-sm text-gray-600">
            {{ Lang::get('admin.panel.users.add-user.description') }}.
        </p>
        <p class="text-sm text-gray-600">
            {{ Lang::get('admin.panel.users.add-user.description-2') }}.
        </p>
        <div class="mt-6">
            <x-input-label for="username" :value="Lang::get('validation.attributes.username')" />
            <x-text-input id="username"
                          name="username"
                          class="block mt-1 w-full"
                          required
                          :value="old('username')"
            />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
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
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value="Lang::get('validation.attributes.password') . ' (' . Lang::get('main.optional') . ')'" />
            <x-password-input id="password"
                              name="password"
                              class="mt-1"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
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
            <x-input-error :messages="$errors->get('language')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label :value="Lang::get('admin.panel.users.add-user.toggles')" />
            <div class="flex flex-col gap-2 md:flex-row md:gap-8 mt-1">
                <label for="is_admin" class="cursor-pointer inline-flex items-center">
                    <input id="is_admin"
                           name="is_admin"
                           type="checkbox"
                           class="border-gray-300 cursor-pointer focus:ring-blue-700 rounded shadow-sm text-blue-600"
                           @checked(old('is_admin'))
                    >
                    <span class="ms-2 text-sm text-gray-600">
                        {{ Lang::get('admin.panel.users.add-user.is_admin') }}
                    </span>
                </label>
                <label for="is_enabled" class="cursor-pointer inline-flex items-center">
                    <input id="is_enabled"
                           name="is_enabled"
                           type="checkbox"
                           class="border-gray-300 cursor-pointer focus:ring-blue-700 rounded shadow-sm text-blue-600"
                           @checked(old('is_enabled'))
                    >
                    <span class="ms-2 text-sm text-gray-600">
                        {{ Lang::get('admin.panel.users.add-user.is_enabled') }}
                    </span>
                </label>
                <label for="is_verified" class="cursor-pointer inline-flex items-center">
                    <input id="is_verified"
                           name="is_verified"
                           type="checkbox"
                           class="border-gray-300 cursor-pointer focus:ring-blue-700 rounded shadow-sm text-blue-600"
                           @checked(old('is_verified'))
                    >
                    <span class="ms-2 text-sm text-gray-600">
                        {{ Lang::get('admin.panel.users.add-user.is_verified') }}
                    </span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('is_admin')" class="mt-2" />
            <x-input-error :messages="$errors->get('is_enabled')" class="mt-2" />
            <x-input-error :messages="$errors->get('is_verified')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.users.add-user.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>
