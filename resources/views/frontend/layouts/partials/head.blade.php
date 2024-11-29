@php
    $isRoot = ( $_SERVER['REQUEST_URI'] === '/en/e-learning' || $_SERVER['REQUEST_URI'] === '/my-MM/e-learning' ) ? true : false;
    $isLearning = strpos($_SERVER['REQUEST_URI'], "/e-learning/courses") ? true : false;
@endphp
<!DOCTYPE html>
<html  dir="ltr" lang="{{ config('app.locale') }}">

<head>
    @if (config('cms.enable_google_analytics'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('cms.google_analytics_key') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ config('
            cms.google_analytics_key ') }}');
    </script>
    @endif
    <meta charset="utf-8">
    <!-- Instruct Internet Explorer to use its latest rendering engine -->
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />

    {{-- update --}}
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ config('app.url') }}" />
@if(App::getLocale() == 'my-MM')
    <title class="my-MM">@yield('title') - {{ config('app.name') }}</title>
@else
    <title >@yield('title') - {{ config('app.name') }}</title>
@endif
    <meta name="title" content="{{ config('app.name') }}">
    <meta name="description" content="@yield('meta_description', config('seo.meta_description'))">
    <meta name="keywords" content="@yield('meta_keywords', config('seo.meta_keywords'))">
    <link rel="canonical" href="{{ url()->current() }}">

    <base href="{{ config('app.url') }}">

    <!-- @if (App::isLocale('my-ZG'))
        <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=zawgyi' />
    @else
        <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=pyidaungsu' />
        <link href="https://fonts.googleapis.com/earlyaccess/notosansmyanmar.css" rel="stylesheet">
    @endif -->
    
    <!-- Font Awesome icons (free version)-->
    <script src="{{ asset('assets/js/font-awesome_5.15.4.js') }}" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" /> -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    @section('css')
        
        <!-- <link href="{{ asset('assets/css/page.min.css') }}" rel="stylesheet"> -->
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @if($isRoot)
            <!-- Add the slick-theme.css if you want default styling -->
            <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
            <!-- Add the slick-theme.css if you want default styling -->
            <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
        @elseif($isLearning)
            <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> 
        @else 

        @endif
       
    @show
    <!-- Favicons -->
    <!-- <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}"> -->
    <link rel="icon" href="{{ asset('assets/img/logos/favicon.ico') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="@yield('og_type', config('seo.og_type'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title') - {{ config('app.name') }}">
    <meta property="og:image" content="@yield('og_image', asset(config('seo.og_image')))">
    <meta property="og:description" content="@yield('og_description', config('seo.og_description'))">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="{{ config('app.locale', 'en_US') }}">
    <meta property="og:image:width" content="@yield('og_image_width', config('seo.og_image_width'))">
    <meta property="og:image:height" content="@yield('og_image_height', config('seo.og_image_height'))">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="{{ config('app.name') }}">
    <meta name="twitter:creator" content="{{ config('seo.twitter_creator') }}">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title') - {{ config('app.name') }}">
    <meta name="twitter:description" content="@yield('twitter_description', config('seo.twitter_description'))">
    <meta name="twitter:image" content="@yield('twitter_image', asset(config('seo.twitter_image')))">
    @laravelPWA 
</head>