<footer class="bg-white flex flex-col items-center p-8 text-center">
    <div class="border flex flex-col mb-4 md:flex-row md:w-auto rounded-lg w-full">
        <a href="{{ $firstLink }}" class="{{ Route::is($firstName) ? 'bg-blue-700 text-white' : '' }} border-b flex items-center justify-center md:border-b-0 md:border-r md:rounded-l-lg md:rounded-tr-none px-4 py-2 rounded-t-lg">
            {{ Lang::get('main.titles.' . $firstName) }}
        </a>
        @foreach ($links as $name => $link)
            <a href="{{ $link }}" class="{{ Route::is($name) ? 'bg-blue-700 text-white' : '' }} border-b flex items-center justify-center md:border-b-0 md:border-r px-4 py-2">
                {{ Lang::get('main.titles.' . $name) }}
            </a>
        @endforeach
        <a href="{{ $lastLink }}" class="{{ Route::is($lastName) ? 'bg-blue-700 text-white' : '' }} flex items-center justify-center md:rounded-bl-none md:rounded-r-lg px-4 py-2 rounded-b-lg">
            {{ Lang::get('main.titles.' . $lastName) }}
        </a>
    </div>
    <p>
        {{ Lang::get('main.footer.programmed-with') }}
        <i class="fa-heart fa-solid text-red-600"></i>
        {{ Lang::get('main.footer.by') }}
        <a href="https://jonaszkadziela.pl" class="text-blue-700" target="_blank" rel="noopener noreferrer">
            {{ Lang::get('main.footer.author') }}
        </a>
    </p>
</footer>
