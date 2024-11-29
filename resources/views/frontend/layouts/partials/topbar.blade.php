<div class="container small-3">
        <div class="dropdown mr-4">
            <span class="dropdown-toggle" data-toggle="dropdown">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                @if (App::isLocale($localeCode))
                {{ $properties['native'] }}
                @endif
            @endforeach
            </span>
            <div class="dropdown-menu dropdown-menu">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a class="dropdown-item {{ App::isLocale($localeCode) ? 'active' : '' }}" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                {{ $properties['native'] }}
                </a> @endforeach
            </div>
        </div>

        <nav class="nav">
            @guest
                <a class="nav-link pl-0" href="{{ route('login') }}">{{ __('Login') }}</a>
                @if (Route::has('register'))
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
            @else
                <span class="nav-link pl-0">{{ __('Hi') }} <strong>{{ Auth::user()->name }}</strong></span>
                <span class="nav-link pl-0"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></span>
                @if (auth()->user()->isAdmin())
                <span class="nav-link pl-0"><a class="bg-primary btn-rounded p-1 text-white" href="{{ route('admin.dashboard') }}">{{ __('Admin Panel') }}</a></span>
                @endif
                <a class="nav-link pl-0" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </nav>

    </div>
