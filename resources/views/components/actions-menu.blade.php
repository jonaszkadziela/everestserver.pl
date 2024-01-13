@php
    use App\Http\Controllers\LanguageController;
@endphp

<aside class="fixed flex flex-col items-end mr-4 mt-4 right-0 top-0">
    <x-dropdown align="right" width="64">
        <x-slot name="trigger">
            <button type="button" tabindex="1" class="bg-white border cursor-pointer flex hover:shadow-xl items-center justify-center px-4 py-2 rounded-full select-none shadow-md transition-shadow">
                <span class="font-bold hidden md:block mr-2">
                    {{ Lang::get('main.menu.title') }}
                </span>
                <i class="fa-solid fa-bars"></i>
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="px-5 py-2">
                <section>
                    <div class="font-medium">
                        {{ Lang::get('main.menu.change-language') }}
                    </div>
                    <div class="border-t my-1"></div>
                    @foreach (config('app.languages') as $language)
                        <a href="{{ action([LanguageController::class, 'change'], ['code' => $language]) }}" class="block">
                            <i class="{{ App::getLocale() === $language ? 'fa-solid' : 'fa-regular '}} fa-flag mr-1"></i>
                            <span>
                                {{ Lang::get('main.languages.' . $language) }}
                            </span>
                        </a>
                    @endforeach
                </section>
                @auth
                    <section class="mt-3">
                        <div class="font-medium">
                            {{ Lang::get('main.menu.navigation') }}
                        </div>
                        <div class="border-t mb-2 mt-1"></div>
                        <div>
                            <i class="fa-solid fa-angle-right mr-1"></i>
                            <a href="{{ route('dashboard') }}" class="text-blue-700 hover:text-blue-900">
                                {{ Lang::get('main.titles.dashboard') }}
                            </a>
                        </div>
                    </section>
                @endauth
                <section class="mt-3">
                    <div class="font-medium">
                        {{ Lang::get('main.menu.your-account') }}
                    </div>
                    <div class="border-t mb-2 mt-1"></div>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <a class="bg-blue-700 border flex hover:bg-blue-800 items-center justify-center px-4 py-2 rounded-full text-white"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                <span class="mr-2">
                                    {{ Lang::get('auth.actions.log-out') }}
                                </span>
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
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
                    @endauth
                </section>
            </div>
        </x-slot>
    </x-dropdown>
</aside>
