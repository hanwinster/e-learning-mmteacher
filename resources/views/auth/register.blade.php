@extends('layouts.default')

@section('title', __('Register'))


@section('content')
<section class="page-section">
    <div class="container mt-5">
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="/">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Register') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container-fluid mt-2">
        <!-- Contact Section Heading-->
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ __('Register') }}</h2>
        <!-- <divider></divider> -->
        <!-- Contact Section Form-->
        <form id="register-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            <div class="row justify-content-center mt-2">
                <!--  data-sb-form-api-token="API_TOKEN" -->
                @csrf
                <div class="col-12 col-lg-1 col-xl-1 col-xxl-2"></div>
                <div class="col-12 col-lg-5 col-xl-5 col-xxl-4">
                    <!-- Account Type -->
                    <div class="form-floating mb-3 mt-5">
                        
                        {!! Form::select('account_type', \App\Repositories\UserRepository::getAccountTypes(),
                        old('account_type'), ['class' => "form-select {{ $errors->has('account_type') ? 'is-invalid' : '' }} ", 'id' => 'account-type']) !!}
                        <label for="accountType">&nbsp;&nbsp;{{ __('Account Type') }}<span class="text-red">*</span></label>
                        @if ($errors->has('account_type'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('account_type') }}</strong>
                            </div>
                        @endif
                    </div>
                    <!-- User Type input -->
                    <div class="form-floating mb-3 user-type-teacher"> 
                        {!! Form::select('user_type_teacher', \App\Repositories\UserRepository::getTeacherUserTypes(),
                        old('user_type_teacher'), ['id' => 'utype-teacher', 'class' => "form-select {{ $errors->has('user_type_teacher') ? 'is-invalid' : '' }} "]) !!}
                        <label for="accountType">&nbsp;&nbsp;{{ __('User Type') }}<span class="text-red">*</span></label>
                        @if ($errors->has('user_type_teacher'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('user_type_teacher') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="form-floating mb-3 user-type-all d-none">
                        {!! Form::select('user_type_all', \App\Repositories\UserRepository::getUserTypesForRegister(),
                        old('user_type_all'), ['id' => 'utype-all', 'class' => "form-select {{ $errors->has('user_type_all') ? 'is-invalid' : '' }} "]) !!}
                        <label for="accountType">&nbsp;&nbsp;{{ __('User Type') }}<span class="text-red">*</span></label>
                        @if ($errors->has('user_type_all'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('user_type_all') }}</strong>
                            </div>
                        @endif
                    </div>
                    <!--Full name input-->
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            id="fname" name="name" type="text" 
                            placeholder="{{ __('Name') }}" value="{{ old('name') }}"/>
                        <label for="fname">{{ __('Name') }}<span class="text-red">*</span></label>
                        @if ($errors->has('name'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                        </div>
                        @endif
                    </div>
                    <!--Username input-->
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" 
                            name="username" type="text" placeholder="{{ __('Username') }}" value="{{ old('username') }}"/>
                        <label for="username">{{ __('Username') }}<span class="text-red">*</span></label>
                        @if ($errors->has('username'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('username') }}</strong>
                            </div>
                        @endif
                        <small class="form-text text-muted">
                            {!! __('Please use character, number, dash and underscore only and do not include spaces.')  !!}
                             @lang('e.g.,') <kbd>test-user, test_user, testUser, testuser22</kbd>
                        </small>
                    </div>
                    <!-- Email address input-->
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" 
                            name="email" type="email" placeholder="name@example.com" value="{{ old('email') }}"/>
                        <label for="email">{{ __('Email address') }}<span class="text-red">*</span></label>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                    </div>
                    <!-- Gender -->
                    <div class="form-floating mt-2 mb-4 gender-div">
                        <div class="row">
                            <div class="col-12">
                                <label>{{__('Gender')}}<span class="text-red">*</span></label>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender-male" value="male" checked>
                                    <span class="form-check-label" for="gender-male">
                                        {{__('Male')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="female" id="gender-female">
                                    <span class="form-check-label" for="gender-female">
                                        {{__('Female')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="others" id="gender-others">
                                    <span class="form-check-label" for="gender-others">
                                        {{__('Others')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Password  input-->
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" 
                            name="password" type="password" placeholder="{{ __('Password') }}" />
                        <label for="password">{{ __('Password') }}<span class="text-red">*</span></label>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                        @endif
                    </div>
                    <!-- Confirm Password  input-->
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" 
                            id="cpassword" name="password_confirmation" type="password" 
                            placeholder="{{ __('Confirm Password') }}" />
                        <label for="cpassword">{{ __('Confirm Password') }}<span class="text-red">*</span></label>
                        @if ($errors->has('password_confirmation'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-lg-5 col-xl-5 col-xxl-4">
                    <!-- education colleges dropdown -->
                    <div class="form-floating mb-3 mt-5 ec-div d-none">
                        {!! Form::select('ec_college',
                        \App\Repositories\CollegeRepository::getItemList(true),
                        old('ec_college'), ['class' => $errors->has('ec_college') ? 'form-select is-invalid' : 'form-select']) !!}
                        <label for="accountType">&nbsp;&nbsp;{{ __('Education Colleges') }}<span class="text-red">*</span></label>
                        @if ($errors->has('ec_college'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('ec_college') }}</strong>
                            </div>
                        @endif
                    </div>
                    <!-- Year of Study input -->
                    <div class="form-floating mb-4 year-div d-none">
                        @php $years = \App\Repositories\ResourceRepository::getEducationCollegeYears();@endphp
                        <div class="row"> 
                            <div class="col-12">
                                <label for="suitable_for_ec_year" class="col-12">{{ __('Year of Study/Teaching') }}</label>
                            </div>
                            <div class="col-12">
                                @foreach ($years as $key => $year)
                                    <div class="custom-control custom-checkbox">
                                        <input id="ecy_{{$key}}" name="suitable_for_ec_year[]" type="checkbox" value="1" 
                                            class="custom-control-input {{$errors->has('suitable_for_ec_year') ? 'is-invalid' : '' }}">
                                        <label for="ecy_{{$key}}" class="custom-control-label">@lang($year)</label>
                                        {!! $errors->first('suitable_for_ec_year', '
                                        <div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!--Affiliation  input for Journalist only-->
                    <div class="form-floating mt-5 mb-3 affiliation-div d-none">
                        <input class="form-control {{ $errors->has('affiliation') ? ' is-invalid' : '' }}" id="affiliation " name="affiliation" type="text" 
                            value="{{ old('affiliation') }}" placeholder="{{ __('Affiliation') }}" />
                        <label for="affiliation">{{ __('Affiliation') }}<span class="text-red">*</span></label>
                        @if ($errors->has('affiliation'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('affiliation') }}</strong>
                            </div>
                        @endif
                    </div>
                    <!--Position  input for Journalist only-->
                    <div class="form-floating mb-3 position-div d-none">
                        <input class="form-control {{ $errors->has('position') ? ' is-invalid' : '' }}" id="position" 
                            name="position" type="text" value="{{ old('position') }}" placeholder="{{ __('Position') }}" />
                        <label for="position">{{ __('Position') }}<span class="text-red">*</span></label>
                        @if ($errors->has('position'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('position') }}</strong>
                            </div>
                        @endif
                    </div>
                    <!--Organization input for Independent Learner only-->
                    <div class="form-floating mt-5 mb-3 organization-div"> 
                        <input class="form-control {{ $errors->has('organization') ? ' is-invalid' : '' }}"
                             id="organization " name="organization" type="text" 
                            value="{{ old('organization') }}" placeholder="{{ __('Organization') }}" />
                        <label for="organization">{{ __('Organization') }}</label>
                        @if ($errors->has('organization'))
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('organization') }}</strong>
                            </div>
                        @endif
                    </div>
                    <!--Phone input-->
                    <div class="row mb-4">
                        <div class="col-5">
                            <div class="form-floating">
                                <!-- <select class="form-select"> 
                                    <option value="95" data-content="<img src='{{ asset('assets/img/logos/E_learning.png') }}' width='20px' height='20px'>">&nbsp;Myanmar</option>
                                    <option value="65">Thailand</option>                          
                                </select>             -->
                                @php 
                                   $srcMm = asset('assets/img/myanmar.png');
                                   $srcTh = asset('assets/img/thailand.png'); 
                                   $mmText = trans('Myanmar ');
                                   $thText = trans('Thailand');        
                                @endphp
                                <div class="dropdown mt-1-8">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown">
                                        <img id="selected-country-img" src="{{ asset('assets/img/myanmar.png') }}" width='24px' height='16px'>&nbsp;
                                        <span id="selected-country-text">@lang('Myanmar ')</span>
                                    </button>
                                    <ul class="dropdown-menu register-dropdown-menu">
                                        <li class="register-li">
                                            <a class="register-link cursor-pointer" at="mm">
                                                <img src="{{ asset('assets/img/myanmar.png') }}" width='24px' height='16px'>&nbsp;@lang('Myanmar ')
                                            </a>
                                        </li>
                                        <li class="register-li">
                                            <a class="register-link cursor-pointer" at="th">
                                                <img src="{{ asset('assets/img/thailand.png') }}" width='24px' height='16px'>&nbsp;@lang('Thailand')
                                            </a>
                                        </li>                               
                                    </ul>
                                </div>
                                <input type="hidden" name="country_code" id="register-country-code" value="mm" />
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="form-floating mb-4">
                                <input class="form-control number-input {{ $errors->has('mobile_no') ? ' is-invalid' : '' }}" id="phone" 
                                    name="mobile_no" type="number" placeholder="9111111111" value="{{ old('mobile_no') }}"/>
                                <label for="phone">{{ __('Phone Number') }}<span id="mobile-required" class="text-red d-none">*</span></label>
                                
                                @if ($errors->has('mobile_no'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mobile_no') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 up-1rem">
                            <small id="mobileno-help" class="form-text text-muted">
                                {!! __('Please select your country and enter your mobile number (Number in English only)') !!}
                                {{ __('e.g.,')}}&nbsp;<kbd>0900000000</kbd>&nbsp;{{ __('Or')}}&nbsp;<kbd>900000000</kbd>&nbsp;
                            </small>
                        </div>
                    </div>
                    <!-- Notification -->
                    <div class="form-floating mb-5">
                        <div class="row">
                            <div class="col-12">
                                <label for="notification_channel">{{ __('OTP Receiving Channel') }}</label>
                            </div>
                            <div class="col-6 pt-4">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" checked="" disabled="" type="checkbox" 
                                                name="notification_channel[]" value="email">
                                    <span class="custom-control-label">{{ __('Email') }} ({{ __('Default') }})</span>
                                    <input class="custom-control-input" type="hidden" name="notification_channel[]" value="email">
                                </div>
                            </div>
                            <div class="col-6 pt-4">
                                <div class="custom-control custom-checkbox">
                                    <input id="type-sms" class="custom-control-input" type="checkbox"
                                        name="notification_channel[]" value="sms"
                                        {{ (old('notification_channel.1') == 'sms') ? 'checked=""' : '' }}>
                                        <span class="custom-control-label">{{ __('SMS') }}</span>
                                </div>
                            </div> 
                        </div>                                              
                    </div>
                    <!-- Profile -->
                    <div class="form-floating mb-3">
                        <div class="row">
                            <div class="col-12">
                                <label for="profile_image">@lang('Profile Image')</label>
                            </div>
                            <div class="col-12 pt-3">
                                {{ \Form::file('profile_image', ['class' => $errors->has('profile_image') ? 'is-invalid' : '']) }}
                                {!! $errors->first('profile_image', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-1 col-xl-1 col-xxl-2"></div>
                </div>
                <div class="col-12 col-lg-1 col-xl-1 col-xxl-2"></div>
            </div>
            <!-- Submit Button-->
            <div class="row mt-2">
                <div class="col-12 col-md-2"></div>
                <div class="col-12 col-md-3">
                    <button class="btn btn-primary btn-lg" style="width:100%;" id="registerButton" type="submit">
                        {{ __('Register') }} <!-- id="submitForm"  -->
                    </button>
                </div>
                <div class="col-12 col-md-5">
                    <p class="pt-2">{{ __('Already a member?') }}
                        <a href="{{ route('login') }}">&nbsp;{{ __('Login Here') }}</a>
                        <!-- &nbsp;
                        <a href="{{ route('testses') }}">&nbsp;{{ __('Test SMS') }}</a> -->
                    </p> 
                </div>
                <div class="col-12 col-md-2"></div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('script')
@parent
<script>
    document.querySelector(".number-input").addEventListener("keypress", function (evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    });
    $(function() {
        var srcTh = {!!json_encode($srcTh, JSON_HEX_TAG) !!};
        var srcMm = {!!json_encode($srcMm, JSON_HEX_TAG) !!};
        var mmText = {!!json_encode($mmText, JSON_HEX_TAG) !!};
        var thText = {!!json_encode($thText, JSON_HEX_TAG) !!};
        $(".register-dropdown-menu a.register-link").click(function() {
            let selected = $(this).attr('at');
            $('#register-country-code').val(selected);
            if (selected === 'th') {               
                $("#selected-country-img").attr("src", srcTh);
                $("#selected-country-text").text(thText);
            } else {
                $("#selected-country-img").attr("src", srcMm);
                $("#selected-country-text").text(mmText);
            }           
        });
        function checkAccountTypeandChange() {
            if ($('#account-type').val() == 2) { //learner type
                $('.year-div').addClass('d-none');
                $('.ec-div').addClass('d-none');
                $('.user-type-teacher').addClass('d-none');
                $('.user-type-all').removeClass('d-none');
            } else {
                $('.year-div').addClass('d-none');
                $('.ec-div').addClass('d-none');
                // $('.year-div').removeClass('d-none');
                // $('.ec-div').removeClass('d-none');
                 $('.user-type-teacher').removeClass('d-none');
                $('.user-type-all').addClass('d-none');
            }
        }
        checkAccountTypeandChange();
        function checkUTypeAllAndChange() { 
            if ( $('#utype-all').val() == 'journalist') {
                $('.year-div').addClass('d-none');
                $('.ec-div').addClass('d-none');
                $('.organization-div').addClass('d-none');
                $('.affiliation-div').removeClass('d-none');
                $('.position-div').removeClass('d-none');
            } else if( $('#utype-all').val() == 'independent_learner' ) { 
                $('.year-div').addClass('d-none');
                $('.ec-div').addClass('d-none');
                $('.organization-div').removeClass('d-none');
                $('.affiliation-div').addClass('d-none');
                $('.position-div').addClass('d-none');
            } else { 
                if($('#utype-all').val() == null || $('#utype-all').val() == '') { 
                   // $('.organization-div').removeClass('d-none');
                } else { //edc
                    $('.year-div').removeClass('d-none');
                    $('.ec-div').removeClass('d-none'); 
                    $('.organization-div').addClass('d-none');
                    $('.affiliation-div').addClass('d-none');
                    $('.position-div').addClass('d-none');
                }
                
            }
        }
        checkUTypeAllAndChange();
        $('#account-type').change(function() {
            //console.log('account type changed!')
            checkAccountTypeandChange();
            if($('.affiliation-div').hasClass('d-none')) {
                
            } else {
                $('.affiliation-div').addClass('d-none');
                $('.position-div').addClass('d-none');
            }          
        });

        $('#utype-all').change(function() {
            //console.log('user type changed!', $('.user_types_all').val());
            checkUTypeAllAndChange();
        });
        $('#utype-teacher').change(function() {
            if($('#utype-teacher').val() == 'independent_teacher') {
                $('.year-div').addClass('d-none');
                $('.ec-div').addClass('d-none');
                $('.organization-div').removeClass('d-none');
            } else { console.log('clicked edu')
                $('.year-div').removeClass('d-none');
                $('.ec-div').removeClass('d-none');
                $('.organization-div').addClass('d-none');
            } 
            
            // var elem = document.getElementById("u-type-all");
            // elem.parentNode.removeChild(elem);
            if($('.affiliation-div').hasClass('d-none')) {
                
            } else {
                $('.affiliation-div').addClass('d-none');
                $('.position-div').addClass('d-none');
            } 
        });
        $('#type-sms').change(function() {
            if($('#type-sms').is(":checked") ) {
               $('#mobile-required').removeClass('d-none');
              // $("input[name=mobile_no]").prop('required',true);
            } else {
                $('#mobile-required').addClass('d-none');
               // $("input[name=mobile_no]").prop('required',false);
            }
        });
    });
</script>
@endsection