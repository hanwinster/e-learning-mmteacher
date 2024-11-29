@php
        $isDashboard = ( $_SERVER['REQUEST_URI'] == '/en/dashboard' ||
                        $_SERVER['REQUEST_URI'] == '/my-MM/dashboard' || $_SERVER['REQUEST_URI'] == '/my-ZG/dashboard' );
@endphp

<!-- Head -->
@include('backend.layouts.partials.head')
<!-- /.Head -->

<body class="hold-transition sidebar-mini layout-fixed {{ App::getLocale() }} @yield('body-css') ">
    <div id="app-root">
        <!-- Navbar -->
        @include('backend.layouts.partials.navbar')
        <!-- /.navbar -->
        <!-- Sidebar -->
        @include('backend.layouts.partials.sidebar')
        <!-- /.sidebar -->
        @yield('content')

    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>{{ __('Copyright') }} © {{ Carbon\Carbon::now()->year }}
            <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>. {{__('All rights reserved.') }}</a>.</strong>

        <div class="float-right d-none d-sm-inline-block">
            <b>{{__('Version') }}</b> {{ config('app.version' ) }}
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <!-- /.footer -->
    <!-- Scripts -->
   

    @if (auth()->user()->isAdmin() && $isDashboard) 
        @include('backend.layouts.partials.footer-admin')
    @elseif (auth()->user()->isUnescoManager() && $isDashboard) 
        @include('backend.layouts.partials.footer-unesco-mgr') 
    @else 
        @include('backend.layouts.partials.footer')
    
    @endif
    @stack('scripts')
    <!-- /.Scripts -->

</body>

</html>