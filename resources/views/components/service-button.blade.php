<a href="{{ $link }}">
    <div class="bg-blue-700 cursor-pointer flex flex-col h-24 hover:bg-blue-800 justify-center p-2 rounded-lg text-white transition-colors w-36">
        <i class="{{ $icon }} mb-1"></i>
        <p class="font-light">
            {!! Lang::get('home.services.' . $type) !!}
        </p>
    </div>
</a>
