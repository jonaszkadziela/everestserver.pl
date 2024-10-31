@props(['modal'])

<button x-data
        class="border-2 border-dashed border-gray-300 cursor-pointer flex gap-2 hover:border-gray-400 hover:text-gray-800 items-center p-4 rounded-lg text-gray-600 transition-colors"
        @click="$dispatch('open-modal', '{{ $modal }}')"
>
    <i class="fa-solid fa-square-plus text-2xl"></i>
    <span class="font-medium text-lg">
        {{ $slot }}
    </span>
</button>
