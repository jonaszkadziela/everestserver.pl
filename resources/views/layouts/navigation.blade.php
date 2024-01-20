<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <div class="bg-blue-700 h-10 p-2 rounded-full w-10">
                            <x-application-logo />
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ Lang::get('main.titles.home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ Lang::get('main.titles.dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Dropdowns -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="mr-2">
                    <x-language-dropdown />
                </div>

                <x-dropdown align="right" content-classes="bg-white px-6 py-4" width="w-64">
                    <x-slot name="trigger">
                        <button class="bg-white border cursor-pointer flex hover:shadow-xl items-center justify-center px-4 py-2 rounded-full select-none transition-shadow">
                            <span class="font-bold mr-2">
                                {{ Auth::user()->username }}
                            </span>
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <section>
                            <div class="font-medium">
                                {{ Lang::get('main.menu.your-account') }}
                            </div>
                            <div class="border-t mb-2 mt-1"></div>
                            <x-menu-link :href="route('profile.edit')">
                                {{ Lang::get('main.titles.profile') }}
                            </x-menu-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a class="bg-blue-700 border flex hover:bg-blue-800 items-center justify-center mt-2 px-4 py-2 rounded-full text-white"
                                   href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    <span class="mr-2">
                                        {{ Lang::get('auth.actions.log-out') }}
                                    </span>
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </a>
                            </form>
                        </section>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <div class="mr-1">
                    <x-language-dropdown class="focus:bg-gray-100 hover:bg-gray-100 rounded" />
                </div>

                <button @click="open = !open" class="duration-150 ease-in-out focus:bg-gray-100 focus:outline-none focus:text-gray-700 h-8 hover:bg-gray-100 hover:text-gray-700 inline-flex items-center justify-center rounded-md text-gray-500 transition w-8">
                    <i class="fa-solid fa-bars fa-lg inline-flex" :class="{'hidden': open, 'inline-flex': !open }"></i>
                    <i class="fa-solid fa-xmark fa-lg hidden" :class="{'hidden': !open, 'inline-flex': open }"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ Lang::get('main.titles.home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ Lang::get('main.titles.dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ Auth::user()->username }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ Lang::get('main.titles.profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ Lang::get('auth.actions.log-out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
