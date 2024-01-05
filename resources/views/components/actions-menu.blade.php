@php
    use App\Http\Controllers\LanguageController;
@endphp

<aside class="fixed flex flex-col items-end mr-4 mt-4 right-0 top-0">
    <div class="bg-white border cursor-pointer flex hover:shadow-xl items-center justify-center mb-2 px-4 py-2 rounded-full select-none shadow-md transition-shadow" data-toggle="#actions-menu">
        <span class="font-bold hidden md:block mr-2">
            {{ Lang::get('main.menu.title') }}
        </span>
        <i class="fa-solid fa-bars"></i>
    </div>
    <div id="actions-menu" class="bg-white border hidden hover:shadow-xl px-4 py-2 rounded-lg shadow-md transition-shadow w-64">
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
    </div>
</aside>

<script>
    const actionsMenu = document.querySelector('#actions-menu')
    const toggleButton = document.querySelector('[data-toggle="#actions-menu"]')

    if (actionsMenu !== null && toggleButton !== null) {
        toggleButton.addEventListener('click', () => {
            let opened = JSON.parse(actionsMenu.getAttribute('data-opened') || 'false')

            opened = !opened

            actionsMenu.setAttribute('data-opened', opened)
            actionsMenu.style.setProperty('display', opened ? 'block' : 'none')
        })

        document.addEventListener('click', event => {
            const withinBoundaries = event.composedPath().includes(actionsMenu) || event.composedPath().includes(toggleButton)

            if (!withinBoundaries) {
                actionsMenu.setAttribute('data-opened', false)
                actionsMenu.style.setProperty('display', 'none')
            }
        })
    }
</script>