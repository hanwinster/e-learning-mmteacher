<!DOCTYPE html>
<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
    <title>Certificate Template 3 </title>
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        html, body {
            height: 100%;
        }
        body {
            margin: 0;
        }
        h6,.h6,
        h5,.h5,
        h4,.h4,
        h3,.h3,
        h2,.h2,
        h1,.h1 {
            font-weight: 700;
            line-height: 1.2;
        }
        .flex-container {
            height: 60%;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .flexx-container {
            height: 100px; 
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .row {
            width: auto;
        }
        .flex-item {
            /* background-color: tomato; */
            padding: 3px 5px;
            width: auto;
            height: auto;
            line-height: 26px;
            color: #000000;
            font-weight: normal;
            font-size: 1.2rem;
            text-align: center;
        }
        .flex-item h2 {
            color: #666; //#0069b4;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .cert {
            border: solid 1px #fff; //0069b4; unesco blue from latest logo */
            height: 640px;
            margin: 20px;
            background-image: url('/assets/img/bg/certificate-bg-2.jpg'); 
            background-repeat: no-repeat;
            background-position: 50% 0;
            background-size: contain;
        }
        .wrapper {
            margin: 2px;
            padding: 10px;
            /* border: solid 2px #022151; */
            height: 610px;
            
        }
        .pt-1 {
            padding-top: 1rem;
        }
        .pt-3 {
            padding-top: 3rem;
        }
        .pt-5 {
            padding-top: 5rem;
        }
        .cert-logo-img {
            width: 80px;
            height: 80px;
        }
        .cert-signature-img {
            width: 60px;
            height: 50px;
        }
        .logo-container {
            padding: 20px 40px;
            text-align: left;
            height: 6%;
            visibility: hidden;
        }
        .d-blue {
            color: #50789C !important; //#0069b4 !important;
            font-weight: 700;
        }
        .signature-container {
            width: auto;
            padding: 10px 40px;
            position: relative;
            height: 15%;
        }
        .left-item {
            display: inline;
            text-align: left;
            width: 50%;
            position: relative;
        }
        .right-item {
            text-align: right;
            display: inline;
            width: 30%;
            position: relative;
        }
        hr {
            border-color: grey;
        }
        .authorized {
            
            right: 20rem;
        }
    }
    </style>
</head>
@php
    //print_r($data);exit;
@endphp
    <body class="cert">
        <!-- <div class="wrapper"> -->
            <!-- <div class="logo-container">
                @if($isPreview)
                    <img class="cert-logo-img text-center" src="{{ asset('assets/img/logos/E_library.png') }}">
                @else 
                    <img class="cert-logo-img text-center" src="assets/img/logos/E_library.png">
                @endif
            </div> -->
            <div class="flex-container">           
                <div class="row">
                    <div class="flex-item">                
                        <h2 class="d-blue">{{ $title }}</h2>                
                    </div>
                    <div class="flex-item">          
                        <h3 class="d-blue">{{ $certify }}</h3>                                               
                    </div>
                    <div class="flex-item">
                        <h2>
                            @if(isset($name))
                                {{$name}}
                            @else
                                [&nbsp;{{ __('STUDENT/LEARNER NAME') }}&nbsp;]
                            @endif
                        </h2>
                    </div>
                    <div class="flex-item">
                        <h5 class="d-blue">{{ $completion }}</h5>
                    </div>
                    
                </div>
            </div>
            <div class="flexx-container">
                <div class="row">
                    <div class="flex-item">
                        <h4 class="d-blue pt-1">{{ $today }}</h4> 
                    </div>
                    <div class="flex-item">
                        <p class="text-center">
                            
                            @if($isPreview)
                                <img class="cert-signature-img text-center" src="{{ asset('assets/img/bg/sample-signature.png') }}">
                            @else 
                                <img class="cert-signature-img text-center" src="assets/img/bg/sample-signature.png">
                            @endif
                        </p>
                        <hr>
                        <p class="authorized"> 
                            <p class="d-blue text-center">@lang('Director')</p>
                            <p class="d-blue text-center">@lang('Strengthening Teacher Education in Myanmar (STEM)')</p>
                        </p> 
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </body>
</html>

<!-- 

                    
                   
                   
                   
                    


                    <p class="text-center"><kbd>Principal</kbd></p>
                        <hr>
                        <p class="text-center">@lang('Principal')</p> 

-->