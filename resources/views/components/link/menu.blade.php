<div>
    <i class="fa-solid fa-angle-right mr-1"></i>
    <a {{ $attributes->merge(['class' => 'dark:hover:text-blue-700 dark:text-blue-500 hover:text-blue-900 text-blue-700']) }}>
        {{ $slot }}
    </a>
</div>
