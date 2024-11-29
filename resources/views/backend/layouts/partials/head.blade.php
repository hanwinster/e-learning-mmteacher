<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>@yield('title') - {{ config('app.name' ) }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ config('app.url') }}" />

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i|Dosis:300,500" rel="stylesheet">
    <!-- @if (App::isLocale('my-ZG'))
        <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=zawgyi' />
    @else
        <link rel="stylesheet" href='https://mmwebfonts.comquas.com/fonts/?font=pyidaungsu' />
        <link href="https://fonts.googleapis.com/earlyaccess/notosansmyanmar.css" rel="stylesheet">
    @endif -->

    <!-- Favicons -->
    <!-- <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}"> -->
    <link rel="icon" href="{{ asset('assets/img/logos/favicon.ico') }}">
    <!-- Styles -->
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
    @php 
        $isRoot = ( $_SERVER['REQUEST_URI'] === '/en/dashboard' || $_SERVER['REQUEST_URI'] === '/my-MM/dashboard') ? true : false;
        if( $isRoot && auth()->user()->isUnescoManager() ) { @endphp
            <link rel="stylesheet" href="{{ url('assets/backend/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
            <link rel="stylesheet" href="{{ url('assets/backend/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
            <link rel="stylesheet" href="{{ url('assets/backend/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    @php    
        }
    @endphp
    <!-- daterange -->
    <link rel="stylesheet" href="{{ url('assets/backend/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('assets/backend/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ url('assets/backend/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ url('assets/backend/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/backend/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('assets/backend/adminlte/adminlte.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    @section('css')
        <link href="{{ url('css/admin.css') }}" rel="stylesheet">
    @show
    

</head>