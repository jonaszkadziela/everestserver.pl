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
