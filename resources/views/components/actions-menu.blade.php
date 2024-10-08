@php
    use App\Http\Controllers\LanguageController;
@endphp

@props(['withNavigation' => false])

<aside {{ $attributes->merge(['class' => 'flex gap-2 items-center z-50']) }}>
    <x-language-dropdown class="bg-white border hover:shadow-lg md:text-sm px-4 rounded-full md:py-2.5 text-xs" />

    <x-dropdown align="right" content-classes="bg-white px-6 py-4" width="w-72">
        <x-slot name="trigger">
            <button type="button" class="bg-white border cursor-pointer flex hover:shadow-lg items-center justify-center px-4 py-2 rounded-full select-none transition-shadow">
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
            @if($withNavigation)
                <nav class="mb-3">
                    <div class="font-medium">
                        {{ Lang::get('main.menu.navigation') }}
                    </div>
                    <div class="border-t mb-2 mt-1"></div>
                    <x-menu-link :href="route('dashboard')">
                        {{ Lang::get('main.titles.dashboard') }}
                    </x-menu-link>

                    <!-- Admin Links -->
                    @if (Auth::user()?->is_admin)
                        <x-menu-link :href="route('admin.panel')">
                            {{ Lang::get('main.titles.admin.panel') }}
                        </x-menu-link>
                    @endif
                </nav>
            @endif
            <section>
                <div class="font-medium">
                    {{ Lang::get('main.menu.your-account') }}
                </div>
                <div class="border-t mb-2 mt-1"></div>
                @auth
                    <x-menu-link :href="route('profile.edit')">
                        {{ Lang::get('main.titles.profile') }}
                    </x-menu-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-submit-button class="mt-2 w-full">
                            <span class="mr-2">
                                {{ Lang::get('auth.actions.log-out') }}
                            </span>
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </x-submit-button>
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
                            <span class="lowercase px-2 text-gray-600">
                                {{ Lang::get('main.menu.or') }}
                            </span>
                            <div class="border-gray-300 border-t w-full"></div>
                        </div>
                        <div class="flex flex-col gap-1">
                            @if(config('services.facebook.enabled'))
                                <x-facebook-button>
                                    {{ Lang::get('main.menu.log-in-with-provider', ['provider' => 'Facebook']) }}
                                </x-facebook-button>
                            @endif
                            @if(config('services.google.enabled'))
                                <x-google-button>
                                    {{ Lang::get('main.menu.log-in-with-provider', ['provider' => 'Google']) }}
                                </x-google-button>
                            @endif
                        </div>
                    @endif
                @endauth
            </section>
        </x-slot>
    </x-dropdown>
</aside>
