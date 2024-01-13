<div>
    <i class="fa-solid fa-angle-right mr-1"></i>
    <a {{ $attributes->merge(['class' => 'text-blue-700 hover:text-blue-900']) }}>
        {{ $slot }}
    </a>
</div>
