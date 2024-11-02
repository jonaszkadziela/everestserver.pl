<div aria-atomic="true"
     aria-live="assertive"
     class="{{ $borderClass }} bg-white border-2 dark:bg-gray-900 duration-300 hover:shadow-xl max-w-md min-w-64 p-4 relative rounded-lg shadow-md transition-all"
     role="alert"
     x-cloak
     x-data="{ open: false }"
     x-show="open"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:enter-start="opacity-0 translate-x-1/2"
     x-transition:enter="ease-out delay-300"
     x-transition:leave-end="opacity-0 translate-x-1/2"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave="ease-in"
     @pageshow.window="open = true"
>
    <button class="absolute hover:text-gray-700 px-1 right-4 text-gray-500 top-4 transition-colors"
            @click="open = !open"
    >
        <i class="fa-solid fa-xmark"></i>
    </button>
    <div class="flex gap-2 items-center">
        <i class="fa-lg {{ $iconClass }} {{ $textClass }}"></i>
        <p class="dark:text-white font-bold">
            {{ $title }}
        </p>
    </div>
    <div class="border-t my-2"></div>
    <p class="dark:text-gray-300 text-sm">
        {{ $body }}
    </p>
</div>
