<!-- Head -->
    @include('frontend.layouts.partials.head')
<!-- /.Head -->

<body class="{{ App::getLocale() }} @yield('body-css') cousera" id="page-top" > <!--oncontextmenu="return false;" //to disable inspect-->
    <div id="app-root">
        

        <!-- Navbar -->
        @include('frontend.layouts.partials.navbar') 
        <!-- /.navbar -->

        @yield('header')
        @yield('content')
        
        @include('frontend.layouts.partials.footer-bar')
    </div>
    <!-- Footer -->
        @include('frontend.layouts.partials.footer')
    <!-- /.footer -->
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid={{ config('cms.sharing_publisher_id') }}"></script>
</body>

</html>