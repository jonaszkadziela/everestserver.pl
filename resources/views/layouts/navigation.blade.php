<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <div class="bg-blue-700 h-10 p-2 rounded-full w-10">
                            <x-application-logo />
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @foreach ($navigation->links() as $key => $value)
                        <x-link.nav :href="$value" :active="request()->routeIs($key)">
                            {{ Lang::get('main.titles.' . $key) }}
                        </x-link.nav>
                    @endforeach
                </div>
            </div>

            <!-- Actions Menu -->
            <x-actions-menu class="hidden sm:flex"
                            :navigation="false"
            />

            <div class="-me-2 flex gap-1 items-center sm:hidden">
                <!-- Responsive Dark Mode Toggle -->
                <x-dark-mode-toggle :value="Session::get('theme', config('app.default_theme')) === 'light'" />

                <!-- Responsive Language Dropdown -->
                <x-dropdown.language class="focus:bg-gray-100 hover:bg-gray-100 rounded" />

                <!-- Hamburger -->
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
            @foreach ($navigation->links() as $key => $value)
                <x-link.responsive-nav :href="$value" :active="request()->routeIs($key)">
                    {{ Lang::get('main.titles.' . $key) }}
                </x-link.responsive-nav>
            @endforeach
        </div>

        <!-- Responsive Account Links -->
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
                <x-link.responsive-nav :href="route('profile.edit')">
                    {{ Lang::get('main.titles.profile') }}
                </x-link.responsive-nav>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-link.responsive-nav :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ Lang::get('auth.actions.log-out') }}
                    </x-link.responsive-nav>
                </form>
            </div>
        </div>
    </div>
</nav>
