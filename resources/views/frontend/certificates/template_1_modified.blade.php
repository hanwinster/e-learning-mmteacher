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
            /* margin: 20px; */
        }
        .wrapper-outer {     
            padding: 2px;
            border: solid 2px #0069b4;
            height: 616px;
            margin: 20px;
        }
        .wrapper {
            margin: 2px;
           
            padding: 10px;
            border: solid 2px #0069b4;
            height: 572px;
        }
        .pt-1 {
            padding-top: 1rem !important;
        }
        .pt-3 {
            padding-top: 3rem !important;
        }
        .pt-5 {
            padding-top: 5rem !important;
        }
        .d-blue {
            color: #0069b4 !important; //#0069b4 !important;
            font-weight: 700;
        }
        .d-blue-w-600 {
            color: #0069b4 !important; 
            font-weight: 600;
        }
        .d-blue-w-500 {
            color: #0069b4 !important; 
            font-weight: 500;
            font-size: 1rem !important;
        }
        .cert-logo-img {
            width: 60px;
            height: 60px;
            padding: 10px 20px 10px 60px;
        }
        .cert-signature-img {
            width: 50px;
            height: 40px;
        }
        .logo-container {
            padding: 10px 10px 20px 5px;
            text-align: left;
            width: 20%;
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
            margin-top: -10px;
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
        .half {
            width: 49% !important;
        }
        .float-l {
            float: left;
        }
        .float-r {
            float: right;
        }
        hr {
            border-color: grey;
        }
        .authorized {           
            right: 20rem;
        }
        @font-face { 
            font-family:'CloisterBlack';
            src:local('CloisterBlack'),
            url('/assets/fonts/fonts/CloisterBlack.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .certificate-font {
            font-family: CloisterBlack !important; 
            font-size: 2.4rem;
        }
        .certificate-name {
            font-family: fixed  !important; //Brush Script MT, Brush Script Std, cursive
        }
    }
    </style>
</head>
<body>
    <div class="wrapper-outer">
        <div class="cert">
            <div class="wrapper">
                <div class="logo-container">
                    <img class="cert-logo-img text-center" src="{{ asset('assets/img/logos/E_library.png') }}">
                    <a class="d-blue" style="display:block;font-size:12px;font-weight:800 !important;margin-top: -10px;">Myanmar Teacher Platform</a>
                </div>
                <div class="flex-container">           
                    <div class="row">
                        <div class="flex-item up-2rem">                
                            <h2 class="d-blue certificate-font">{{ $title }}</h2>                
                        </div>
                        <div class="flex-item up-2rem">          
                            <h3 class="d-blue">{{ $certify }}</h3>                                               
                        </div>
                        <div class="flex-item up-2rem">
                            <h2 class="certificate-name">
                                @if(isset($name))
                                    {{$name}}
                                @else
                                    [&nbsp;{{ __('Learner Name') }}&nbsp;]
                                @endif
                            </h2>
                        </div>
                        <div class="flex-item up-2rem">
                            <h4 class="d-blue">{{ $completion }}</h4>
                        </div>
                        <div class="flex-item up-2rem">
                            <h4 class="d-blue pt-1">Issued: {{ $today }}</h4> 
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
        </div>
    </div>
    <!-- <br/>
    <div class="wrapper-outer">
        <div class="cert">
            <div class="wrapper">               
                <div class="container" style="">                        
                        <div class="">                
                            <h3 class="d-blue-w-600 text-center">Course Description</h3>     
                            <span class="d-blue-w-500">{!! $courseDes !!}</span>                
                        </div>
                        <div class="">                
                            <h3 class="d-blue-w-600 text-center">Course Objectives</h3>     
                            <span class="d-blue-w-500">{!! $courseObj !!}</span>                
                        </div>
                </div>           
            </div>
        </div>
    </div> -->
    <!-- <br/> -->
</body>
</html>
