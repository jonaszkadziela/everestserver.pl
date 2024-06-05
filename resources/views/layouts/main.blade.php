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
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta property="og:locale" content="{{ App::getLocale() }}">
        <meta property="og:locale:alternate" content="{{ App::getFallbackLocale() }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="{{ (isset($title) ? $title . ' - ' : '') . config('app.name') }}">
        <meta property="og:description" content="{{ Lang::get('main.description') }}">
        <meta property="og:image" content="{{ Vite::asset('resources/images/brand/og-image.jpg') }}">
        <meta property="og:url" content="{{ config('app.url') }}">
        <link href="{{ Vite::asset('resources/images/brand/favicon.png') }}" rel="shortcut icon" type="image/png">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @if(App::isProduction() && isset($withAnalytics))
            <x-analytics g-id="G-S4071NMXR0" />
        @endif
    </head>
    <body class="{{ isset($bodyClass) ? $bodyClass : '' }}">
        @isset($withActionsMenu)
            <x-actions-menu class="fixed mr-4 mt-4 right-0 top-0"
                            :with-navigation="Auth::check()"
            />
        @endisset
        @isset($withNavigation)
            @include('layouts.navigation')
        @endisset
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset
        {{ $slot }}
        @isset($withFooter)
            <x-footer encoded-links="{!! json_encode([
                'home' => route('home'),
                'dashboard' => route('dashboard'),
                'privacy' => route('privacy'),
                'contact' => 'mailto:' . Lang::get('main.contact-email'),
            ]) !!}" />
        @endisset
        <div class="bottom-0 fixed flex flex-col gap-2 left-0 m-8 md:left-auto right-0">
            @include('toasts.cookies')
            @if(Session::has('notifications'))
                @foreach (Session::pull('notifications') as $notification)
                    <x-notification :title="$notification['title']"
                                    :body="$notification['body']"
                                    :type="Arr::get($notification, 'type')"
                    />
                @endforeach
            @endif
        </div>
    </body>
</html>
