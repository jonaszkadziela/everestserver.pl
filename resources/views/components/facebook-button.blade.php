<a {{ $attributes->merge(['class' => 'bg-sky-600 border flex hover:bg-sky-700 items-center justify-center px-4 py-2 relative rounded-full text-white']) }}
   href="{{ route('external-auth.facebook') }}"
   x-bind:class="loading ? 'pointer-events-none' : ''"
   x-data="{ loading: false }"
   @click="loading = true"
   @pageshow.window="loading = false"
>
    <span x-show="loading"
          x-cloak
          class="absolute flex inset-0 items-center justify-center"
    >
        <i class="fa-circle-notch fa-solid fa-spin"></i>
    </span>
    <span class="flex gap-2 items-center justify-center"
          x-bind:class="loading ? 'opacity-0' : ''"
    >
        {{ $slot }}
        <i class="fa-brands fa-facebook"></i>
    </span>
</a>
