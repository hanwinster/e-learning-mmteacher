<?php
    $isBe = strpos($_SERVER['REQUEST_URI'], "/backend/") ? true : false;
    $isRoot = $_SERVER['REQUEST_URI'] == ("/en/e-learning" || "/my-MM/e-learning") ? true : false;
    $isZawgyi = App::getLocale() == 'my-ZG' ? true : false;
    $isEng = App::getLocale() == 'en' ? true : false;
?> 
<header id="navbar-header" class="bg-secondary text-white fixed-top">
    <div class="container d-none d-xl-block" id="navbarResponsive">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a id="logo-wrapper" href="{{ route('mm-teacher-platform') }}" 
                class="mb-2 mb-lg-0 text-decoration-none">
                <!--d-flex align-items-center text-white  -->
                <img src="{{ asset('assets/img/logos/E_learning.png') }}"
                    class="fe-logo"  alt="E-Learning" />
                <a class="fe-logo-text">Myanmar Teacher Platform</a>
            </a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <!-- <li><a href="#page-top" class="nav-link px-2 text-white">E-learning</a></li> -->
                <?php if ($isRoot) {   ?>
                    <li class="nav-item">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded active" 
                            href="{{ route('home') }}#page-top">
                            @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu E-learning')])
                        </a> 
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded active" 
                        href="/">
                            @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu E-learning')])
                        </a> 
                    </li>
                <?php } ?>
                <li class="nav-item mx-0 mx-lg-1"> 
                    <a class="nav-link py-3 px-0 px-lg-3 rounded" 
                    href="{{ route('home') }}#courses">
                    @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu Courses')])
                    </a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3 rounded" 
                    href="{{ route('home') }}#about">
                    @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu About Us')])
                    </a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3 rounded" 
                    href="{{ route('home') }}#faq">
                    @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu FAQs')])
                    </a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a id="contact-menu" class="nav-link py-3 px-0 px-lg-3 rounded">               
                        @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu Contact Us')])
                    </a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    @php 
                        $eLibrary = $isEng ? "https://lib.mmteacherplatform.net/en" : "https://lib.mmteacherplatform.net/my-MM";
                    @endphp
                    <a class="nav-link py-3 px-0 px-lg-3 rounded" 
                        href="{{$eLibrary}}"> 
                        @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu E-library')])
                    </a>
                </li>
            </ul>
            <div class="form-check form-switch me-2">
                <input class="form-check-input" type="checkbox" id="is-offline-mode">
                <label class="form-check-label" for="is-offline-mode">{{ __('Offline') }}</label> 
            </div>
            @if($isRoot && $isEng)
                <!-- <div id="color-switcher">
                    <a onclick="colorSwitcher('default');" class="default selected">1</a>
                    <a onclick="colorSwitcher('alter');"  class="alter">2</a>
                    <a onclick="colorSwitcher('alter2');"  class="alter2">3</a> 
                    <a onclick="colorSwitcher('cousera');"  class="cousera">4</a> -->
                    <!-- <a onclick="colorSwitcher('opposite');"  class="opposite">4</a>
                    <a onclick="colorSwitcher('darkseagreen');"  class="darkseagreen">5</a>
                    <a onclick="colorSwitcher('bluegreen');" class="bluegreen" >6</a> -->
                <!-- </div>  -->
            @endif
            @include('frontend.layouts.partials.language_switcher')

            @guest
                <a type="button" class="btn btn-primary btn-sm me-2" href="{{ route('login') }}">
                @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu Login') ]) </a>
                <a type="button" class="btn btn-primary btn-sm " href="{{ route('register') }}">
                @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => $isZawgyi, 'text' => __('Menu Register') ])</a>
            @else
    
            @include('frontend.layouts.partials.user-dropdown', ['username' => str_limit(strip_tags(Auth::user()->name), 8, '...') ])
            <a type="button" class="btn btn-primary btn-sm me-2" href="{{ route('auth.custom-logout') }}">
                @include('frontend.layouts.partials.menu-zg2uni',  
                        [ 'isZawgyi' => $isZawgyi, 'text' =>  __('Menu Logout') ])
            </a>
            {{-- <form id="logout-form" action="{{ route('auth.custom-logout') }}" method="POST" style="display: none;">
                {{-- @csrf 
            </form> --}}
                
            @endguest
            <!-- </div> -->
            
        </div>
    </div>

    @include('frontend.layouts.partials.navbar-mobile')
</header>

<div id="offline-alert" class="container-fluid d-none">
    <div class="col-12">
        <div class="alert alert-dark alert-dismissible fade show" role="alert"> 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong id="offline-alert-title" class="d-block"></strong>
            <p id="offline-alert-text"></p>
            <p id="offline-cache-alert-usage"></p>
        </div>
    </div>
</div>