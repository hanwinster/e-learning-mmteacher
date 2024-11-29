<!DOCTYPE html>
<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
    <title> mmteacherplatform.net - {{__('Certificate')}} </title>
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            width: 992px;
        }
        html {
            height: 100%;
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
        
        .row {
            width: auto;
        }
        
        .cert {
            border: solid 8px #0069b4; //0069b4; unesco blue from latest logo #030ea1 -color shown to team with opa city 0.6
            background-color: white;
            /* background-image: url('/assets/img/logos/E_library.png'); to replace it with a good one*/
            background-repeat: no-repeat;
            background-position: 50% 0;
            background-size: cover;
            /* opacity: 0.6; */
            height: 600px;
            margin: 20px;
        }
        .wrapper {
            margin: 2px;
           
            padding: 10px;
            border: solid 2px #0069b4;
            height: 572px;
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
        .d-blue {
            color: #0069b4 !important; //#0069b4 !important;
            font-weight: 700;
        }
        .cert-logo-img {
            width: 60px;
            height: 60px;
        }
        .cert-signature-img {
            width: 50px;
            height: 40px;
        }
        .logo-container {
            padding: 20px 40px;
            text-align: left;
            width: 15%;
        }
        
        .flex-container {
            height: 54%;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
           
        }
        .signature-container {
            padding: 10px 40px;
            position: relative;
            margin-top: 50px;
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
        .up-2rem {
            margin-top: -2rem;
        }
        .up-3rem {
            margin-top: -3rem;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .left-item {
            float:left;
            text-align: left;
            width: 50%;
        }
        .right-item {
            text-align: right;
            float: right;
            width: 50%;
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
        <div class="wrapper">
            <div class="logo-container">
                @if($isPreview)
                    <img class="cert-logo-img text-center" src="{{ asset('assets/img/logos/E_library.png') }}">
                @else 
                    <img class="cert-logo-img text-center" src="assets/img/logos/E_library.png">
                @endif
            </div>
            <div class="flex-container up-3rem">           
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
                    <div class="flex-item up-2rem">
                        <h4 class="d-blue pt-1">{{ $today }}</h4> 
                    </div>
                </div>
            </div>
            <div class="signature-container">
                <!-- <div class="left-item">
                    <h4 class="d-blue pt-1">{{ $today }}</h4> 
                </div> -->
                <div class="right-item">
                    <div class="text-center">
                        <!-- <kbd>Signature</kbd> -->
                        @if($isPreview)
                            <img class="cert-signature-img text-center" src="{{ asset('assets/img/Ichiro_sign.jpg') }}">
                        @else 
                            <img class="cert-signature-img text-center" src="assets/img/Ichiro_sign.jpg">
                        @endif
                       
                    </div>
                    <hr> 
                    <div class="authorized"> 
                        <p class="d-blue text-center">@lang('Head of Office')</p>
                        <p class="d-blue text-center">@lang('UNESCO Myanmar Office')</p>
                    </div> 
                </div>
            </div>
        </div>
    </body>
</html>

<!-- 

                    
                   
                   
                   
                    


                    <p class="text-center"><kbd>Principal</kbd></p>
                        <hr>
                        <p class="text-center">@lang('Principal')</p> 

-->