<!DOCTYPE html>
<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
    <title>Certificate Template 1 </title>
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        h6,
        .h6,
        h5,
        .h5,
        h4,
        .h4,
        h3,
        .h3,
        h2,
        .h2,
        h1,
        .h1 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-weight: 700;
            line-height: 1.2;
        }

        h1,
        .h1 {
            font-size: calc(1.375rem + 1.5vw);
        }

        @media (min-width: 1200px) {

            h1,
            .h1 {
                font-size: 2.5rem;
            }
        }

        h2,
        .h2 {
            font-size: calc(1.325rem + 0.9vw);
        }

        @media (min-width: 1200px) {

            h2,
            .h2 {
                font-size: 2rem;
            }
        }

        h3,
        .h3 {
            font-size: calc(1.3rem + 0.6vw);
        }

        @media (min-width: 1200px) {

            h3,
            .h3 {
                font-size: 1.75rem;
            }
        }

        h4,
        .h4 {
            font-size: calc(1.275rem + 0.3vw);
        }

        @media (min-width: 1200px) {

            h4,
            .h4 {
                font-size: 1.5rem;
            }
        }

        h5,
        .h5 {
            font-size: 1.25rem;
        }

        h6,
        .h6 {
            font-size: 1rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .cert {
            border: solid 10px darkblue;
            background-color: lightskyblue;
            /* background-image: url('/assets/img/logos/E_library.png'); to replace it with a good one*/
            background-repeat: no-repeat;
            background-position: 50% 0;
            background-size: contain;
            opacity: 0.6;
        }

        .cert-logo-img {
            width: 80px;
            height: 80px;
            margin-left: 9rem;
        }

        .text-certi-learner {
            color: darkblue !important;
        }

        .signatures {
            margin-top: 6rem !important;
        }

        /* bootstrap classes */
        .container,
        .container-fluid,
        .container-xxl,
        .container-xl,
        .container-lg,
        .container-md,
        .container-sm {
            width: 100%;
            padding-right: var(--bs-gutter-x, 0.75rem);
            padding-left: var(--bs-gutter-x, 0.75rem);
            margin-right: auto;
            margin-left: auto;
        }

        @media (min-width: 576px) {

            .container-sm,
            .container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {

            .container-md,
            .container-sm,
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {

            .container-lg,
            .container-md,
            .container-sm,
            .container {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {

            .container-xl,
            .container-lg,
            .container-md,
            .container-sm,
            .container {
                max-width: 1140px;
            }
        }

        @media (min-width: 1400px) {

            .container-xxl,
            .container-xl,
            .container-lg,
            .container-md,
            .container-sm,
            .container {
                max-width: 1320px;
            }
        }

        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-0.5 * var(--bs-gutter-x));
            margin-left: calc(-0.5 * var(--bs-gutter-x));
        }

        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x) * 0.5);
            padding-left: calc(var(--bs-gutter-x) * 0.5);
            margin-top: var(--bs-gutter-y);
        }

        .col {
            flex: 1 0 0%;
        }

        .row-cols-auto>* {
            flex: 0 0 auto;
            width: auto;
        }

        .row-cols-1>* {
            flex: 0 0 auto;
            width: 100%;
        }

        .row-cols-2>* {
            flex: 0 0 auto;
            width: 50%;
        }

        .row-cols-3>* {
            flex: 0 0 auto;
            width: 33.3333333333%;
        }

        .row-cols-4>* {
            flex: 0 0 auto;
            width: 25%;
        }

        .row-cols-5>* {
            flex: 0 0 auto;
            width: 20%;
        }

        .row-cols-6>* {
            flex: 0 0 auto;
            width: 16.6666666667%;
        }

        .col-auto {
            flex: 0 0 auto;
            width: auto;
        }

        .col-1 {
            flex: 0 0 auto;
            width: 8.33333333%;
        }

        .col-2 {
            flex: 0 0 auto;
            width: 16.66666667%;
        }

        .col-3 {
            flex: 0 0 auto;
            width: 25%;
        }

        .col-4 {
            flex: 0 0 auto;
            width: 33.33333333%;
        }

        .col-5 {
            flex: 0 0 auto;
            width: 41.66666667%;
        }

        .col-6 {
            flex: 0 0 auto;
            width: 50%;
        }

        .col-7 {
            flex: 0 0 auto;
            width: 58.33333333%;
        }

        .col-8 {
            flex: 0 0 auto;
            width: 66.66666667%;
        }

        .col-9 {
            flex: 0 0 auto;
            width: 75%;
        }

        .col-10 {
            flex: 0 0 auto;
            width: 83.33333333%;
        }

        .col-11 {
            flex: 0 0 auto;
            width: 91.66666667%;
        }

        .col-12 {
            flex: 0 0 auto;
            width: 100%;
        }

        .vh-100 {
            height: 100vh !important;
        }

        .text-center {
            text-align: center !important;
        }

        pre,
        code,
        kbd,
        samp {
            font-family: var(--bs-font-monospace);
            font-size: 1em;
            direction: ltr
                /* rtl:ignore */
            ;
            unicode-bidi: bidi-override;
        }

        .pt-5 {
            padding-top: 3rem !important;
        }

        .p-5 {
            padding: 3rem !important;
        }

        kbd {
            padding: 0.2rem 0.4rem;
            font-size: 0.875em;
            color: #fff;
            background-color: #212529;
            border-radius: 0.25rem;
        }

        kbd kbd {
            padding: 0;
            font-size: 1em;
            font-weight: 700;
        }
        hr {
            margin: 1rem 0;
            color: inherit;
            background-color: currentColor;
            border: 0;
            opacity: 0.25;
        }

        hr:not([size]) {
            height: 0.125rem;
        }
    </style>
</head>
@php
        //print_r($data);exit;
@endphp
<body>
    <div class="container cert p-5" style="height:580px">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center" style="color: darkblue;">{{ $title }}</h2>
                <h3 class="text-center mt-2 pt-5">{{ $certify }}</h3>
                <h2 class="text-center pt-5" style="color: darkblue;">
                    @if(isset($name))
                     {{$name}}
                    @else
                     [&nbsp;{{ __('STUDENT/LEARNER NAME') }}&nbsp;]
                    @endif
                </h2>
                <h5 class="text-center pt-5">{{ $completion }}</h5>
                <h4 class="text-center pt-5">{{$today}}</h4> 
            </div>
        </div>
        <div class="row signatures">
            <div class="col-1"></div>
            <!-- <div class="col-3">
                <p class="text-center"><kbd>Principal</kbd></p> 
                <hr>
                <p class="text-center">Person 1, Principal</p>
            </div> -->
            <div class="col-4">
                @if($isPreview)
                    <img class="cert-logo-img text-center" src="{{ asset('assets/img/logos/E_library_blue.png') }}">
                @else 
                    <img class="cert-logo-img text-center" src="assets/img/logos/E_library_blue.png">
                @endif
            </div>
            <div class="col-3">
                <p class="text-center"><kbd>Principal</kbd></p>
                <hr>
                <p class="text-center">Principal</p>
            </div>
            <div class="col-1"></div>
        </div>
    </div>


</body>

</html>