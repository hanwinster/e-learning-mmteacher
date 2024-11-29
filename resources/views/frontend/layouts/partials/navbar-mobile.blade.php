<?php
    $isBe = strpos($_SERVER['REQUEST_URI'], "/backend/") ? true : false;
    $isRoot = $_SERVER['REQUEST_URI'] == ("/en" || "my-MM" || "my-ZG") ? true : false;
    $isZawgyi = App::getLocale() == 'my-ZG' ? true : false;
    $isEng = App::getLocale() == 'en' ? true : false;
?> 
<!-- Media less than 1200px -->
<nav class="navbar navbar-expand-xl navbar-light bg-secondary text-white d-block d-xl-none">
        <div class="container-fluid">
            <div class="row vw-100">
                <div class="col-4 col-sm-4 col-md-3">
                <a id="logo-wrapper" href="{{ route('home') }}" 
                    class="navbar-brand">
                    <img class="fe-logo" src="{{ asset('assets/img/logos/E_learning.png') }}"
                        alt="E-Learning" />
                        <a class="fe-logo-text">Myanmar Teacher Platform</a>
                </a>
                </div>
                <div class="col-2 col-sm-3 col-md-3 mt-3">
                    @include('frontend.layouts.partials.language_switcher')
                </div>
                <div class="col-3 col-sm-3 col-md-3 mt-3">
                    <div class="form-check form-switch me-2">
                        <input class="form-check-input" type="checkbox" id="is-offline-mode-mobile">
                        <label class="form-check-label" for="is-offline-mode-mobile">{{ __('Offline') }}</label> 
                    </div>
                </div>
                <div class="col-3 col-sm-2 col-md-3">
                    <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon "></span>
                    </button>
                </div>
            </div>
                      
            <div class="collapse navbar-collapse mt-3" id="navbarNav">
                        <ul class="navbar-nav">
                            <?php if ($isRoot) {   ?>
                                <li class="nav-item mx-0 mx-xl-1">
                                    <a class="nav-link active" href="{{ route('home') }}#page-top">
                                    {{ __('E-learning' ) }}
                                    </a> 
                                </li>
                            <?php } else { ?>
                                <li class="nav-item mx-0 mx-xl-1">
                                    <a class="nav-link active" href="/">
                                        {{ __('E-learning' ) }}
                                    </a> 
                                </li>
                            <?php } ?>
                            <li class="nav-item mx-0 mx-xl-1"> 
                                <a class="nav-link" 
                                href="{{ route('home') }}#courses">
                               {{ __( 'Courses' ) }}
                                </a>
                            </li>
                            <li class="nav-item mx-0 mx-xl-1">
                                <a class="nav-link" 
                                href="{{ route('home') }}#about">
                                {{ __('About Us' ) }}
                                </a>
                            </li>
                            <li class="nav-item mx-0 mx-xl-1">
                                <a class="nav-link" 
                                href="{{ route('home') }}#faq">
                                 {{ __('FAQs' ) }}
                                </a>
                            </li>
                            <li class="nav-item mx-0 mx-xl-1">
                                <a id="contact-menu" class="nav-link">
                                {{ __('Contact Us' )}}
                                </a>
                            </li>
                            <li class="nav-item mx-0 mx-xl-1">
                                @php 
                                    $eLibrary = $isEng ? "https://lib.mmteacherplatform.net/en" : "https://lib.mmteacherplatform.net/my-MM";
                                @endphp
                                <a class="nav-link" href="{{$eLibrary}}">
                                    {{ __('E-library' ) }}
                                </a>
                            </li>
                        </ul>
                        @guest
                            <div class="row">
                                <div class="col-6">
                                    <a type="button" class="nav-link btn btn-primary btn-sm me-2 mb-2" href="{{ route('login') }}">{!! __('Menu Login') !!}</a>
                                </div>
                                <div class="col-6">
                                    <a type="button" class=" nav-link btn btn-primary btn-sm " href="{{ route('register') }}">{!! __('Menu Register') !!}</a>
                                </div>
                            </div>                                        
                        @else
                            <div class="row">
                                <div class="col-6">
                                    @include('frontend.layouts.partials.user-dropdown', ['username' => str_limit(strip_tags(Auth::user()->name), 20, '...') ])
                                </div>
                                <div class="col-6">
                                    <a type="button" class="btn btn-primary btn-sm me-2 w-100" href="{{ route('auth.custom-logout') }}">
                                        @include('frontend.layouts.partials.menu-zg2uni', 
                                                [ 'isZawgyi' => $isZawgyi, 'text' =>  __('Menu Logout') ])
                                    </a>
                                    {{-- <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form> --}}
                                </div>
                            </div>
                            
                            
                            
                        @endguest  
            </div>              
        </div>
    </nav>

    <!-- <div id="offline-alert-mobile" class="container-fluid d-none">
        <div class="col-12">
            <div class="alert alert-dark alert-dismissible fade show" role="alert"> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong id="offline-alert-title" class="d-block"></strong>
                <p id="offline-alert-text"></p>
                <p id="offline-cache-alert-usage"></p>
            </div>
        </div>
    </div> -->