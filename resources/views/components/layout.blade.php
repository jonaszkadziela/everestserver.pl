<!doctype html>
<html lang="{{ App::getLocale() }}">
    <head>
        <title>
            {{ (isset($title) ? $title . ' - ' : '') . config('app.name') }}
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ Lang::get('main.description') }}">
        <meta name="keywords" content="everestserver, everestcloud, everest, server, cloud, storage">
        <meta name="author" content="Jonasz Kądziela">
        <meta property="og:locale" content="{{ App::getLocale() }}">
        <meta property="og:locale:alternate" content="{{ App::getFallbackLocale() }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="{{ (isset($title) ? $title . ' - ' : '') . config('app.name') }}">
        <meta property="og:description" content="{{ Lang::get('main.description') }}">
        <meta property="og:image" content="{{ Vite::asset('resources/images/brand/og-image.jpg') }}">
        <meta property="og:url" content="{{ config('app.url') }}">
        <link href="{{ Vite::asset('resources/images/brand/favicon.png') }}" rel="shortcut icon" type="image/png">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
        @vite('resources/css/app.css')
    </head>
    <body class="{{ $bodyClass }}">
        {{ $slot }}
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
