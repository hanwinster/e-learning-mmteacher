<!-- Head -->
@include('frontend.layouts.partials.head')
<!-- /.Head -->

<body class="{{ App::getLocale() }} @yield('body-css') cousera">
    <div id="app-root">
        <!-- Navbar -->
            @include('frontend.layouts.partials.navbar')
        <!-- /.navbar -->

        @yield('content')
        @include('frontend.layouts.partials.footer-bar')
    </div>
    <!-- Footer -->
        @include('frontend.layouts.partials.footer') 
    <!-- /.footer -->
    
</body>

</html>