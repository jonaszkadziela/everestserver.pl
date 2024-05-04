@props(['service'])

<a href="{{ $service->link }}">
    <div class="bg-blue-700 cursor-pointer flex flex-col h-24 hover:bg-blue-800 justify-center p-2 rounded-lg text-white transition-colors w-36"
         title="{{ $service->description }}"
    >
        <i class="{{ $service->icon }} mb-1"></i>
        <p class="font-light">
            {!! Lang::get('home.services.' . Str::lower($service->name)) !!}
        </p>
    </div>
</a>
