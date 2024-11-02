<div id="privacy-warning"
     class="bg-white border dark:bg-gray-900 dark:text-gray-300 duration-500 hidden hover:shadow-xl max-w-md opacity-0 p-4 rounded-lg shadow-md transition-all"
     role="alert"
     aria-live="assertive"
     aria-atomic="true"
>
    <div class="text-sm">
        <p>
            {{ Lang::get('main.cookies.description') }}
            <a href="{{ route('privacy') }}" class="text-blue-700 hover:text-blue-900">{{ Lang::get('main.cookies.privacy-policy') }}</a>.
        </p>
        <div class="border-t flex gap-4 items-center justify-end mt-4 pt-4">
            <a href="{{ route('privacy') }}" class="text-blue-700 hover:text-blue-900">
                {{ Lang::get('main.cookies.learn-more') }}
            </a>
            <button type="button" class="bg-blue-700 hover:bg-blue-800 px-2 py-1.5 rounded text-white transition-colors" data-dismiss="#privacy-warning">
                {{ Lang::get('main.cookies.acknowledge') }}
            </button>
        </div>
    </div>
</div>
@vite('resources/js/cookies.js')
