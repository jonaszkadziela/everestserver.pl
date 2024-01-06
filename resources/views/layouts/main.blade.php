<!doctype html>
<html lang="{{ App::getLocale() }}">
    <head>
        <title>
            {{ (isset($title) ? $title . ' - ' : '') . config('app.name') }}
        </title>
        @include('layouts.head-meta')
        <link href="{{ Vite::asset('resources/images/brand/favicon.png') }}" rel="shortcut icon" type="image/png">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if(App::isProduction() && isset($withAnalytics))
            <x-analytics g-id="G-S4071NMXR0" />
        @endif
    </head>
    <body class="{{ $bodyClass }}">
        {{ $slot }}
        @isset($withActionsMenu)
            <x-actions-menu />
        @endisset
        @isset($withFooter)
            <x-footer encoded-links="{!! json_encode([
                'home' => route('home'),
                'privacy' => route('privacy'),
                'contact' => 'mailto:kontakt@jonaszkadziela.pl',
            ]) !!}" />
        @endisset
        <div class="fixed bottom-0 m-8 right-0 toast-container">
            @include('toasts.cookies')
        </div>
    </body>
</html>
