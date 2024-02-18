@php
    $lang = App::getLocale();
    $emailAddress = $lang === 'en' ? 'contact@jonaszkadziela.pl' : 'kontakt@jonaszkadziela.pl';
@endphp

<x-main-layout title="{{ Lang::get('main.titles.privacy') }}"
               body-class="bg-blue-700 bg-image-primary bg-image-responsive flex flex-col min-h-screen"
               with-actions-menu
               with-analytics
               with-footer
>
    <main class="flex flex-1 items-center justify-center my-12 w-full">
        <div class="bg-white border md:w-2/3 mx-8 p-8 rounded-lg shadow-lg text-center w-full">
            <h1 class="mb-6 text-3xl">
                {{ Lang::get('privacy.title') }}
            </h1>
            <div class="mb-6 text-gray-500">
                <p>
                    {{ Lang::get('privacy.description') }}.
                </p>
                <p>
                    {{ Lang::get('privacy.contact') }}
                    <a href="mailto:{{ $emailAddress }}" class="text-blue-700 hover:text-blue-900">{{ $emailAddress }}</a>.
                </p>
            </div>
            <x-privacy-policy :lang="$lang"
                              :email-address="$emailAddress"
            />
        </div>
    </main>
</x-main-layout>
