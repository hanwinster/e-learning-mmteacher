<div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('assets/img/logos/E_learning.png') }}" 
            alt="{{__('E-Learning') }}" height="50" width="50">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!--left navbar  links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="/{{ config('app.locale') }}/dashboard" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Language Switcher -->
            <li class="nav-item dropdown lang me-2">
                <div class="dropdown">
                    <span class="dropdown-toggle" data-toggle="dropdown">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @if (App::isLocale($localeCode))
                                {{ $properties['native'] }}
                            @endif
                        @endforeach
                    </span>
                    <div class="dropdown-menu">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a class="dropdown-item {{ App::isLocale($localeCode) ? 'active' : '' }}" rel="alternate" 
                            hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            {{ $properties['native'] }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </li>
            <!-- View Full Screen -->
            <!-- <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li> -->
            <!-- User Info --> 
            <li class="nav-item dropdown lang me-2">
                <div class="dropdown">  
                    <span class="dropdown-toggle user-badge" data-toggle="dropdown">
                        <!-- <span class="badge badge-secondary navbar-badge user"> -->
                            {{ __('Hi') }}, {{ str_limit(strip_tags(auth()->user()->name), 8, '...') }}
                        <!-- </span> -->
                    </span>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('courses.my-courses') }}">
                            <i class="fas fa-list"></i>&nbsp;{{ __('My Courses') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('member.profile.edit') }}">
                            <i class="fas fa-user"></i>&nbsp;{{ __('Profile') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('member.change-password.edit') }}">
                            <i class="fas fa-lock-open"></i>&nbsp;{{ __('Change Password') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('auth.custom-logout') }}" >
                            <i class="fas fa-power-off"></i>&nbsp;{{ __('Logout') }}
                        </a>

                        {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form> --}}
                    </div>
                </div>
            </li>
            <!-- Notifications -->
            <li class="nav-item dropdown me-2">
                 @php 
                    $notifications = auth()->user()->notifications;
                    $unreadNotifications = \App\Repositories\UserRepository::getUnreadNotifications();
                        
                 @endphp
                <a class="nav-link text-warning" data-toggle="dropdown" href="#">
                    <i class="far fa-bell fa-lg"></i>
                    @if(count($unreadNotifications))
                        <span class="badge badge-warning navbar-badge">
                            {{ count($unreadNotifications) }}
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">
                        {{ count($unreadNotifications) }}&nbsp;@lang('Unread Notifications')
                    </span>
                    <div class="dropdown-divider"></div>
                    @if(count($unreadNotifications))
                        @foreach($unreadNotifications as $notification)
                            <a href="{{ route('member.notification.show', $notification->id) }}" class="dropdown-item">
                                @php $temp = explode("\\", $notification->type);
                                     $type = $temp[sizeof($temp) -1];
                                @endphp
                                <i class="fas fa-envelope mr-1"></i>{{ $type }}
                                <span class="float-right text-muted text-sm"> 
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </a>
                            <div class="dropdown-divider"></div>
                        @endforeach
                    @endif
                    <a href="{{ route('member.notification.index') }}" class="dropdown-item dropdown-footer">
                        @lang('View All Notifications')
                    </a>
                </div>
            </li>
            <!-- View Website -->
            @if( auth()->user()->isAdmin() || auth()->user()->isUnescoManager() || 
                 auth()->user()->isManager() || auth()->user()->isTeacherEducator()
            )
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"> <!-- target="_blank"  -->
                        <i class="fas fa-link"></i>
                        {{ __('View Website') }}
                    </a>
                </li>
            @endif

        </ul>
    </nav> 
    <div id="offline-alert-be" class="container-fluid d-none">
        <div class="col-12">
            <div class="alert alert-dark alert-dismissible fade show" role="alert">                
                <strong id="offline-alert-title-be" class="d-block"></strong>
                <p id="offline-alert-text-be"></p>
                <p id="offline-cache-alert-usage-be"></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>