@props(['navigation'])

<aside {{ $attributes->merge(['class' => 'flex gap-2 items-center z-50']) }}>
    <x-dropdown.language class="bg-white border dark:bg-gray-900 hover:shadow-lg md:py-2.5 md:text-sm px-4 rounded-full text-xs" />

    <x-dropdown.main align="right" content-classes="bg-white dark:bg-gray-900 dark:text-white px-6 py-4" width="w-72">
        <x-slot name="trigger">
            <button type="button" class="bg-white border cursor-pointer dark:bg-gray-900 dark:text-white flex hover:shadow-lg items-center justify-center px-4 py-2 rounded-full select-none transition-shadow">
                <span class="font-bold hidden md:block mr-2">
                    @auth
                        {{ Auth::user()->username }}
                    @else
                        {{ Lang::get('main.menu.title') }}
                    @endauth
                </span>
                <i class="fa-solid fa-bars"></i>
            </button>
        </x-slot>

        <x-slot name="content">
            <section class="mb-3">
                <div class="font-medium">
                    {{ Lang::get('main.menu.application-theme') }}
                </div>
                <div class="border-t mb-2 mt-1"></div>
                <x-dark-mode-toggle :value="Session::get('theme', config('app.default_theme')) === 'light'" />
            </section>

            @if ($navigation)
                <nav class="mb-3">
                    <div class="font-medium">
                        {{ Lang::get('main.menu.navigation') }}
                    </div>
                    <div class="border-t mb-2 mt-1"></div>
                    @foreach ($navigation->links() as $key => $value)
                        @continue(request()->routeIs($key))
                        <x-link.menu :href="$value">
                            {{ Lang::get('main.titles.' . $key) }}
                        </x-link.menu>
                    @endforeach
                </nav>
            @endif

            <section>
                <div class="font-medium">
                    {{ Lang::get('main.menu.your-account') }}
                </div>
                <div class="border-t mb-2 mt-1"></div>
                @auth
                    <x-link.menu :href="route('profile.edit')">
                        {{ Lang::get('main.titles.profile') }}
                    </x-link.menu>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-button.submit class="mt-2 w-full">
                            <span class="mr-2">
                                {{ Lang::get('auth.actions.log-out') }}
                            </span>
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </x-button.submit>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-700 border flex hover:bg-blue-800 items-center justify-center px-4 py-2 rounded-full text-white mb-1">
                        <span class="mr-2">
                            {{ Lang::get('main.menu.log-in') }}
                        </span>
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-700 border flex hover:bg-blue-800 items-center justify-center px-4 py-2 rounded-full text-white">
                        <span class="mr-2">
                            {{ Lang::get('main.menu.register') }}
                        </span>
                        <i class="fa-solid fa-user-plus"></i>
                    </a>

                    @if(config('services.facebook.enabled') || config('services.google.enabled'))
                        <div class="flex items-center my-3">
                            <div class="border-gray-300 border-t w-full"></div>
                            <span class="dark:text-gray-300 lowercase px-2 text-gray-600">
                                {{ Lang::get('main.menu.or') }}
                            </span>
                            <div class="border-gray-300 border-t w-full"></div>
                        </div>
                        <div class="flex flex-col gap-1">
                            @if(config('services.facebook.enabled'))
                                <x-button.facebook>
                                    {{ Lang::get('main.menu.log-in-with-provider', ['provider' => 'Facebook']) }}
                                </x-button.facebook>
                            @endif
                            @if(config('services.google.enabled'))
                                <x-button.google>
                                    {{ Lang::get('main.menu.log-in-with-provider', ['provider' => 'Google']) }}
                                </x-button.google>
                            @endif
                        </div>
                    @endif
                @endauth
            </section>
        </x-slot>
    </x-dropdown.main>
</aside>
