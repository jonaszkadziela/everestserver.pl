<div class="flex flex-col gap-2 items-start justify-between mb-8 md:flex-row">
    <div class="flex flex-col">
        <h3 class="dark:text-white font-semibold mb-2 text-xl">
            {{ Lang::get('admin.panel.services.title') }}
        </h3>
        <p class="dark:text-gray-300 text-gray-600 text-sm">
            {{ Lang::get('admin.panel.services.description') }}.
        </p>
    </div>
    <div class="flex flex-col flex-shrink-0 gap-2 md:flex-row">
        <form method="get" action="{{ url()->current() }}">
            <input type="hidden" name="tab" value="{{ request()->get('tab') }}">
            <x-input.text id="search"
                          name="search"
                          class="w-48"
                          placeholder="{{ Lang::get('admin.panel.search') }}..."
                          :value="request()->get('search')"
            />
        </form>
        <x-button.primary x-data
                          @click.prevent="$dispatch('open-modal', 'create-service')"
        >
            <span class="mr-2">
                {{ Lang::get('admin.panel.services.create-service.title') }}
            </span>
            <i class="fa-solid fa-plus"></i>
        </x-button.primary>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 dark:text-gray-300 overflow-x-auto rounded-lg shadow text-left">
    @if (!empty($data['transformed']))
        <table class="w-full">
            <thead>
                <tr>
                    @foreach ($data['transformed'][0] as $key => $value)
                        <th class="bg-slate-50 border-b dark:bg-slate-700 dark:text-white p-4 @if($loop->first) rounded-tl-lg @endif">
                            <a href="{{ url()->current() . '?' . http_build_query([
                                    'tab' => request()->get('tab'),
                                    'column' => $key,
                                    'direction' => request()->get('direction', 'asc') === 'asc' ? 'desc' : 'asc',
                                ]) }}"
                               class="flex gap-1 items-center"
                            >
                                {{ Lang::get('admin.panel.services.columns.' . $key) }}
                                @if (request()->get('column', 'id') === $key)
                                    <i class="fa @if(request()->get('direction', 'asc') === 'asc') fa-angle-up @else fa-angle-down @endif"></i>
                                @endif
                            </a>
                        </th>
                    @endforeach
                    <th class="bg-slate-50 border-b dark:bg-slate-700 dark:text-white p-4 rounded-tr-lg">
                        {{ Lang::get('admin.panel.services.columns.actions') }}
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
                                            @click.prevent="$dispatch('update-service', {{ $data['raw'][$loop->parent->index]->toJson() }}); $dispatch('open-modal', 'update-service')"
                                            class="text-blue-700 hover:text-blue-900"
                                            type="button"
                                    >
                                        {{ Lang::get('admin.panel.edit') }}
                                    </button>
                                    <span>|</span>
                                    <button x-data
                                            @click.prevent="$dispatch('delete-service', {{ $data['raw'][$loop->parent->index]->toJson() }}); $dispatch('open-modal', 'delete-service')"
                                            class="text-blue-700 hover:text-blue-900"
                                            type="button"
                                    >
                                        {{ Lang::get('admin.panel.delete') }}
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
            {{ Lang::get('admin.panel.services.no-results') }}
        </p>
    @endif
</div>

@if ($data['raw'] instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-4">
        {{ $data['raw']->links() }}
    </div>
@endif

<x-modal.main name="create-service" :show="$errors->createService->isNotEmpty()" focusable>
    <form method="post" action="{{ route('services.store') }}" class="p-6">
        @csrf
        <h2 class="dark:text-white font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.services.create-service.title') }}
        </h2>
        <p class="dark:text-gray-300 text-gray-600 text-sm">
            {{ Lang::get('admin.panel.services.create-service.description') }}.
        </p>
        <div class="mt-6">
            <x-input.label for="name" :value="Lang::get('validation.attributes.name')" />
            <x-input.text id="name"
                          name="name"
                          class="block mt-1 w-full"
                          required
                          :value="old('name')"
            />
            <x-input.error :messages="$errors->createService->get('name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label for="description" :value="Lang::get('validation.attributes.description')" />
            <x-input.textarea id="description"
                              name="description"
                              class="block mt-1 w-full"
                              rows="4"
                              required
                              x-bind:value="'{{ old('description') }}'"
            />
            <x-input.error :messages="$errors->createService->get('description')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label for="icon" :value="Lang::get('validation.attributes.icon')" />
            <x-input.text id="icon"
                          name="icon"
                          class="block mt-1 w-full"
                          required
                          :value="old('icon')"
            />
            <x-input.error :messages="$errors->createService->get('icon')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label for="link" :value="Lang::get('validation.attributes.link')" />
            <x-input.text id="link"
                          name="link"
                          class="block mt-1 w-full"
                          required
                          :value="old('link')"
            />
            <x-input.error :messages="$errors->createService->get('link')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label :value="Lang::get('admin.panel.services.create-service.toggles')" />
            <div class="flex flex-col gap-2 md:flex-row md:gap-8 mt-1">
                <x-input.checkbox id="is_public"
                                  name="is_public"
                                  :checked="old('is_public')"
                >
                    {{ Lang::get('admin.panel.services.create-service.is_public') }}
                </x-input.checkbox>
                <x-input.checkbox id="is_enabled"
                                  name="is_enabled"
                                  :checked="old('is_enabled')"
                >
                    {{ Lang::get('admin.panel.services.create-service.is_enabled') }}
                </x-input.checkbox>
            </div>
            <x-input.error :messages="$errors->createService->get('is_public')" class="mt-2" />
            <x-input.error :messages="$errors->createService->get('is_enabled')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.services.create-service.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>

<x-modal.main name="update-service" :show="$errors->updateService->isNotEmpty()" focusable>
    <form method="post"
          class="p-6"
          x-bind:action="action.slice(0, -2).concat(form.id || '{{ old('id') }}')"
          x-data="{ action: '{{ route('services.update', ['service' => -1]) }}', form: {} }"
          x-on:update-service.window="form = $event.detail"
    >
        @csrf
        @method('patch')
        <h2 class="dark:text-white font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.services.update-service.title') }}
        </h2>
        <p class="dark:text-gray-300 text-gray-600 text-sm">
            {{ Lang::get('admin.panel.services.update-service.description') }}.
        </p>
        <input type="hidden" name="id" x-bind:value="form.id || '{{ old('id') }}'">
        <div class="mt-6">
            <x-input.label for="new_name" :value="Lang::get('validation.attributes.name')" />
            <x-input.text id="new_name"
                          name="new_name"
                          class="block mt-1 w-full"
                          required
                          x-bind:value="form.name || '{{ old('new_name') }}'"
            />
            <x-input.error :messages="$errors->updateService->get('new_name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label for="new_description" :value="Lang::get('validation.attributes.description')" />
            <x-input.textarea id="new_description"
                              name="new_description"
                              class="block mt-1 w-full"
                              rows="4"
                              required
                              x-bind:value="JSON.stringify(form.description) || '{{ old('new_description') }}'"
            />
            <x-input.error :messages="$errors->updateService->get('new_description')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label for="new_icon" :value="Lang::get('validation.attributes.icon')" />
            <x-input.text id="new_icon"
                          name="new_icon"
                          class="block mt-1 w-full"
                          required
                          x-bind:value="form.icon || '{{ old('new_icon') }}'"
            />
            <x-input.error :messages="$errors->updateService->get('new_icon')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label for="new_link" :value="Lang::get('validation.attributes.link')" />
            <x-input.text id="new_link"
                          name="new_link"
                          class="block mt-1 w-full"
                          required
                          x-bind:value="form.link || '{{ old('new_link') }}'"
            />
            <x-input.error :messages="$errors->updateService->get('new_link')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label :value="Lang::get('admin.panel.services.update-service.toggles')" />
            <div class="flex flex-col gap-2 md:flex-row md:gap-8 mt-1">
                <x-input.checkbox id="new_is_public"
                                  name="new_is_public"
                                  x-bind:value="form.is_public === true || '{{ old('new_is_public') }}' === 'on'"
                >
                    {{ Lang::get('admin.panel.services.update-service.is_public') }}
                </x-input.checkbox>
                <x-input.checkbox id="new_is_enabled"
                                  name="new_is_enabled"
                                  x-bind:value="form.is_enabled === true || '{{ old('new_is_enabled') }}' === 'on'"
                >
                    {{ Lang::get('admin.panel.services.update-service.is_enabled') }}
                </x-input.checkbox>
            </div>
            <x-input.error :messages="$errors->updateService->get('new_is_public')" class="mt-2" />
            <x-input.error :messages="$errors->updateService->get('new_is_enabled')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.services.update-service.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>

<x-modal.main name="delete-service" :show="$errors->deleteService->isNotEmpty()">
    <form method="post"
          class="p-6"
          x-bind:action="action.slice(0, -2).concat(form.id)"
          x-data="{ action: '{{ route('services.destroy', ['service' => -1]) }}', form: {} }"
          x-on:delete-service.window="form = $event.detail"
    >
        @csrf
        @method('delete')
        <h2 class="dark:text-white font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.services.delete-service.title') }}
        </h2>
        <p class="dark:text-gray-300 text-gray-600 text-sm">
            {{ Lang::get('admin.panel.services.delete-service.description') }} <strong x-text="form.name"></strong>?
        </p>
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.services.delete-service.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>
