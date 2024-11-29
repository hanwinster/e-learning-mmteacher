<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Myanmar Teacher Platform - @lang('Home')</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="icon" href="{{ asset('assets/img/logos/favicon.ico') }}">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/vendor/home-template/assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="/vendor/home-template/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="/vendor/home-template/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/vendor/home-template/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/vendor/home-template/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/vendor/home-template/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="/vendor/home-template/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/vendor/home-template/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="/vendor/home-template/assets/css/style.css?v={{ time() }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Anyar - v4.8.0
  * Template URL: https://bootstrapmade.com/anyar-free-multipurpose-one-page-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<body>
@php
    $isRoot = ( $_SERVER['REQUEST_URI'] === '/en' || $_SERVER['REQUEST_URI'] === '/my-MM' ) ? true : false;
@endphp
  <!-- ======= Top Bar ======= -->
  <div id="topbar" class="fixed-top d-flex align-items-center {{$isRoot ? '' : 'topbar-inner-pages' }} ">
    <div class="container d-flex align-items-center justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center text-red">
        <i class="bi bi-envelope-fill"></i><a class="text-white" href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a>
         
          <div class="dropdown mx-3" style="z-index: 1100;">
              <a class="dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" id="dropdownMenuLink">
                  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                      @if (App::isLocale($localeCode))
                        {!! $properties['native'] !!}
                      @endif
                  @endforeach
              </a>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="z-index: 1100;top:-40px; left:70px;"> 
                  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                  <li style="z-index: 1100; padding: 6px 12px; font-size: 0.9rem;"> 
                      <a class="dropdown-item" rel="alternate" style="color:black;padding: 2px 4px;"
                          hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                          {{ $properties['native'] }}
                      </a>
                  </li>
                  @endforeach
              </ul>
          </div>
      
      </div>
      <div class="top-right-social d-none d-md-block">
        <a href="https://apps.apple.com/us/app/myanmar-teacher-platform/id6444518874" target="_blank">
          <img src="{{ asset('assets/img/app-store.png') }}" class="mb-1" height="36px"/>
        </a>
        <a href="https://play.google.com/store/apps/details?id=com.misfit.mtp&hl=en&gl=US" target="_blank">
          <img src="{{ asset('assets/img/play-store.png') }}" class="mb-1"  height="36px"/>
        </a>
        <a href="https://www.facebook.com/UNESCOMyanmar" alt="Facebook" class="facebook" style=""><i class="bx bxl-facebook"></i></a>
        <a href="https://www.youtube.com/channel/UCWFMwfh_7JT29nwB1Czmuvg" class="youtube" style="" alt="YouTube"><i class="bx bxl-youtube"></i></a>
      </div>
      <div class="top-right-social d-block d-md-none">
        <a href="https://apps.apple.com/us/app/myanmar-teacher-platform/id6444518874" target="_blank">
          <img src="{{ asset('assets/img/app-mobile.png') }}" class="mb-2" width="36px"/>
        </a>
        <a href="https://play.google.com/store/apps/details?id=com.misfit.mtp&hl=en&gl=US" target="_blank">
          <img src="{{ asset('assets/img/play-mobile.png') }}" class="mb-2"  width="36px"/>
        </a>
        <a href="https://www.facebook.com/UNESCOMyanmar" alt="Facebook" class="facebook" style=""><i class="bx bxl-facebook"></i></a>
        <a href="https://www.youtube.com/channel/UCWFMwfh_7JT29nwB1Czmuvg" class="youtube" style="" alt="YouTube"><i class="bx bxl-youtube"></i></a>
      </div>
    </div>
  </div>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center {{$isRoot ? '' : 'header-inner-pages' }}">
    <div class="container d-flex align-items-center justify-content-between">

      <h1 class="logo">
        <img src="{{ asset('assets/img/logos/E_learning.png') }}" class="img-fluid" width="38px" height="36px" style="margin-bottom:4px;"/>&nbsp;
        <a href="#" style="font-size: 0.9rem;">Myanmar Teacher Platform</a>
      </h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href=index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="{{env('APP_URL').'/'.App::getLocale() }}/#hero">@lang('Home')</a></li>
          <li><a class="nav-link scrollto" href="{{$isRoot ? '#news' : env('APP_URL').'/'.App::getLocale().'/#news' }}">@lang('News & Events')</a></li>         
          <li><a class="nav-link" href="{{env('APP_URL').'/'.App::getLocale() }}/e-learning">@lang('E-learning')</a></li>
          <li><a class="nav-link" href="https://lib.mmteacherplatform.net/{{App::getLocale()}}">@lang('E-library')</a></li>
          <li><a class="nav-link scrollto" href="{{$isRoot ? '#resources' : env('APP_URL').'/'.App::getLocale().'/#resources' }}">@lang('Resources')</a></li>          
          <!-- <li><a class="nav-link" href="{{env('APP_URL').'/'.App::getLocale() }}/other-resources">@lang('Other Resources')</a></li> -->
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->