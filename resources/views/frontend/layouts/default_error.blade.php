<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="ltr">
<head>
    @if (config('cms.enable_google_analytics'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('cms.google_analytics_key') }}"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ config('cms.google_analytics_key') }}');
    </script>
    @endif
    <meta charset="utf-8">
    <!-- Instruct Internet Explorer to use its latest rendering engine -->
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', config('seo.meta_description'))">
    <meta name="keywords" content="@yield('meta_keywords', config('seo.meta_keywords'))33">
    <link rel="canonical" href="{{ url()->current() }}">

    <base href="{{ config('app.url') }}">

    @if (App::isLocale('my-ZG'))
    <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=zawgyi' />
{{--     <style>
        body, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{
            font-family: "Zawgyi-One", Arial, Helvetica, sans-serif !important;
        }
    </style> --}}
    @else
    <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=pyidaungsu' />
{{--     <style>
        body, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{
            font-family: "Pyidaungsu", Arial, Helvetica, sans-serif !important;
        }
    </style> --}}
    @endif
    <!-- Styles -->
    @section('css')
    <link href="{{ asset('assets/css/page.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @show
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">

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
	
	<!-- Google reCAPTCHA  -->
	<!--<script src='https://www.google.com/recaptcha/api.js?render=6Ldiz4QUAAAAAMdvj5uSq5vBvEmtXwDHJ9bdIJtc'></script>-->
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body class="{{ App::getLocale() }} @yield('body-css')">
    <!-- Navbar -->
    @include('frontend.layouts.partials.navbar')
    <!-- /.navbar -->

    
    <!-- /.header -->

    <!-- Main Content -->
    @section('page-top')
    @include('frontend.partials.block', ['region' => 'page-top'])
    @show

    @yield('content')

    @section('page-bottom')
    @include('frontend.partials.block', ['region' => 'page-bottom'])
    @show
    <!-- Footer -->
    @include('frontend.layouts.partials.footer')
    <!-- /.footer -->
    <!-- Scripts -->
    @section('script')
    <script src="{{ asset('assets/js/page.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
	<script src="{{ asset('assets/backend/vendor/ckeditor/ckeditor.js') }}"></script>
    @show
</body>
</html>
