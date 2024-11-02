@php
    use App\Models\Service;
    use App\Models\User;
@endphp

<div class="flex flex-col gap-2 items-start justify-between mb-8 md:flex-row">
    <div class="flex flex-col">
        <h3 class="dark:text-white font-semibold mb-2 text-xl">
            {{ Lang::get('admin.panel.linked-services.title') }}
        </h3>
        <p class="dark:text-gray-300 text-gray-600 text-sm">
            {{ Lang::get('admin.panel.linked-services.description') }}.
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
                          @click.prevent="$dispatch('open-modal', 'link-service')"
        >
            <span class="mr-2">
                {{ Lang::get('admin.panel.linked-services.link-service.title') }}
            </span>
            <i class="fa-solid fa-link"></i>
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
                                {{ Lang::get('admin.panel.linked-services.columns.' . $key) }}
                                @if (request()->get('column', 'service') === $key)
                                    <i class="fa @if(request()->get('direction', 'asc') === 'asc') fa-angle-up @else fa-angle-down @endif"></i>
                                @endif
                            </a>
                        </th>
                    @endforeach
                    <th class="bg-slate-50 border-b dark:bg-slate-700 dark:text-white p-4 rounded-tr-lg">
                        {{ Lang::get('admin.panel.linked-services.columns.actions') }}
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
                                            @click.prevent="$dispatch('unlink-service', @js($data['raw'][$loop->parent->index])); $dispatch('open-modal', 'unlink-service')"
                                            class="text-blue-700 hover:text-blue-900"
                                            type="button"
                                    >
                                        {{ Lang::get('admin.panel.unlink') }}
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
            {{ Lang::get('admin.panel.linked-services.no-results') }}
        </p>
    @endif
</div>

@if ($data['raw'] instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-4">
        {{ $data['raw']->links() }}
    </div>
@endif

<x-modal.main name="link-service" :show="$errors->linkService->isNotEmpty()" focusable>
    <form method="post" action="{{ route('linked-services.link') }}" class="p-6">
        @csrf
        <h2 class="dark:text-white font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.linked-services.link-service.title') }}
        </h2>
        <p class="dark:text-gray-300 text-gray-600 text-sm">
            {{ Lang::get('admin.panel.linked-services.link-service.description') }}.
        </p>
        <div class="mt-6">
            <x-input.label for="service_id" :value="Lang::get('admin.panel.linked-services.link-service.service_id')" />
            <x-input.select id="service_id"
                            name="service_id"
                            class="mt-1"
                            required
                            :options="Service::enabled()->get()->mapWithKeys(fn (Service $service) => [$service->id => $service->name])"
                            :selected="old('service_id')"
            />
            <x-input.error :messages="$errors->createUser->get('service_id')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label for="user_id" :value="Lang::get('admin.panel.linked-services.link-service.user_id')" />
            <x-input.select id="user_id"
                            name="user_id"
                            class="mt-1"
                            required
                            :options="User::get()->mapWithKeys(fn (User $user) => [$user->id => $user->username])"
                            :selected="old('user_id')"
            />
            <x-input.error :messages="$errors->createUser->get('user_id')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input.label for="identifier" :value="Lang::get('admin.panel.linked-services.link-service.identifier') . ' (' . Lang::get('main.optional') . ')'" />
            <x-input.text id="identifier"
                          name="identifier"
                          class="block mt-1 w-full"
                          :value="old('identifier')"
            />
            <x-input.error :messages="$errors->createService->get('identifier')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.linked-services.link-service.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>

<x-modal.main name="unlink-service" :show="$errors->unlinkService->isNotEmpty()">
    <form method="post"
          class="p-6"
          action="{{ route('linked-services.unlink') }}"
          x-data="{ form: {} }"
          x-on:unlink-service.window="form = $event.detail"
    >
        @csrf
        <h2 class="dark:text-white font-medium mb-6 text-gray-900 text-lg">
            {{ Lang::get('admin.panel.linked-services.unlink-service.title') }}
        </h2>
        <p class="dark:text-gray-300 text-gray-600 text-sm">
            {{ Lang::get('admin.panel.linked-services.unlink-service.description') }} <strong x-text="form.service"></strong>
            {{ Lang::get('admin.panel.linked-services.unlink-service.description-2') }} <strong x-text="form.user"></strong>?
        </p>
        <input type="hidden" name="user_id" x-bind:value="form.user_id">
        <input type="hidden" name="service_id" x-bind:value="form.service_id">
        <div class="mt-6 flex justify-end">
            <x-modal.secondary-button x-on:click="$dispatch('close')">
                {{ Lang::get('main.actions.cancel') }}
            </x-modal.secondary-button>
            <x-modal.primary-button class="ms-3">
                {{ Lang::get('admin.panel.linked-services.unlink-service.title') }}
            </x-modal.primary-button>
        </div>
    </form>
</x-modal.main>
