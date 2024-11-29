<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <base href="{{ config('app.url') }}">
    @if (App::isLocale('my-ZG'))
    <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=zawgyi' />
    @else
    <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=pyidaungsu' />
    @endif
    <!-- Styles -->

@section('css')
    <link href="{{ asset('assets/css/page.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet"> @show
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/eapple-icon.png') }}">
    <link rel="icon" href="{{ asset('assets/img/elibraryfavicon.png') }}">
    <!--  Open Graph Tags -->
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="">
    <meta property="og:image" content="">
    <meta property="og:url" content="">
    <meta name="twitter:card" content="summary_large_image">
</head>
<body class="{{ App::getLocale() }} @yield('body-css')">
    @yield('content')
    <!-- Scripts -->
    <script src="{{ asset('assets/js/page.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
