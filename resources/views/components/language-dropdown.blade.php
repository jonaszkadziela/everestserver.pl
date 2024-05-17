@php
    use App\Http\Controllers\LanguageController;
@endphp

<x-dropdown align="right" content-classes="bg-white px-6 py-4" width="w-64">
    <x-slot name="trigger">
        <button {{ $attributes->merge(['class' => 'flex hover:text-gray-700 p-2 text-gray-500 transition']) }}>
            <div>
                <i class="fa-solid fa-earth-europe"></i>
            </div>

            <div class="ms-1">
                <i class="fa-solid fa-angle-down"></i>
            </div>
        </button>
    </x-slot>

    <x-slot name="content">
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
    </x-slot>
</x-dropdown>
