<div id="privacy-warning"
     class="bg-white border dark:bg-gray-900 dark:text-gray-300 duration-500 hidden hover:shadow-xl max-w-md opacity-0 p-4 rounded-lg shadow-md transition-all"
     role="alert"
     aria-live="assertive"
     aria-atomic="true"
>
    <div class="text-sm">
        <p>
            {{ Lang::get('main.cookies.description') }}
            <x-link.primary href="{{ route('privacy') }}">
                {{ Lang::get('main.cookies.privacy-policy') }}.
            </x-link.primary>
        </p>
        <div class="border-t flex gap-4 items-center justify-end mt-4 pt-4">
            <x-link.primary href="{{ route('privacy') }}">
                {{ Lang::get('main.cookies.learn-more') }}
            </x-link.primary>
            <x-button.primary type="button" data-dismiss="#privacy-warning">
                {{ Lang::get('main.cookies.acknowledge') }}
            </x-button.primary>
        </div>
    </div>
</div>
@vite('resources/js/cookies.js')
