@extends('layouts.default')

@section('title', __('Login'))

@section('content')
<section class="page-section" style="height:94%;">
    <div class="container mt-5">
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="/">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Login') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container mt-2">
        <!-- Contact Section Heading--> 
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ __('Login') }}</h2>
        <!-- <divider></divider> -->
        <!-- Contact Section Form-->
        <div class="row justify-content-center mt-2">
            <div class="col-12 col-sm-12 col-md-11 col-lg-10 col-xl-9 col-xxl-8">
                @if ($message = Session::get('approve'))
                    <div class="alert alert-secondary alert-dismissible fade show text-center">
                        {{ __('Error') }} : {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @include('frontend.layouts.form_alert')
                <form id="loginForm" method="POST" action="{{ route('login') }}" > <!--data-sb-form-api-token="API_TOKEN" -->
                    @csrf
                    <!-- Email address input-->
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}" 
                               id="user-email" type="text" placeholder="{{__('Username or Email Address') }}" 
                               name="email" value="{{ old('username') ?: old('email') }}"  autofocus/> <!-- data-sb-validations="required" -->
                        <label for="user-email">{{__('Username or Email Address') }}<span class="text-red">*</span></label>
                        <!-- <div class="invalid-feedback" data-sb-feedback="user-email:required">
                            {{ __('Username or email address is required.') }}
                        </div> -->
                        @if ($errors->has('username') || $errors->has('email'))
                            <div class="invalid-feedback" data-sb-feedback="user-email:required">
                                {{ $errors->first('username') ?: $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                    <!-- Phone number input-->
                    <div class="form-floating mb-3">
                        <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" 
                                name="password" type="password" placeholder="{{ __('Password') }}" /> <!-- data-sb-validations="required"  -->
                        <label for="password">{{ __('Password') }}<span class="text-red">*</span></label>
                        <!-- <div class="invalid-feedback" data-sb-feedback="password:required">
                            {{ __('Password is required.') }}
                        </div> -->
                        @if ($errors->has('password'))
                            <div class="invalid-feedback" data-sb-feedback="password:required">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>
                    <!-- has successfully submitted-->
                    <div class="d-none" id="submitSuccessMessage">
                        <div class="text-center mb-3">
                            <div class="fw-bolder">{{ __('Form submission is successful!') }}</div>
                            <br />
                        </div>
                    </div>
                
                    <!-- an error submitting the form-->
                    <div class="d-none" id="submitErrorMessage">
                        <div class="text-center text-danger mb-3">
                            {{ __('Error occured while submitting the form. Please try again!') }}
                        </div>
                    </div>
                    <div class="row">
                    @if ( Config::get('app.locale') == 'en')
                        <div class="col-6 col-md-2">
                            <!-- Submit Button-->
                            <button class="btn btn-primary btn-lg " id="submitButton" type="submit">
                                {{ __('Login') }}
                            </button> <!-- disabled -->
                        </div>
                        <div class="col-6 col-md-3">
                            <p class="pt-2"> 
                                <a href="{{ route('auth.get.password_reset_option') }}">
                                    &nbsp;{{ __('Forgot Password') }}
                                </a>
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="pt-2"> {{ __("Don't have an account yet?") }}
                                <a href="{{ route('register') }}">&nbsp;
                                    {{ __('Register Here') }}
                                </a>
                            </p>
                        </div>
                    @elseif ( Config::get('app.locale') == 'my-MM' || 'my-ZG' )
                        <div class="col-6 col-md-3">                       
                            <button class="btn btn-primary btn-lg " id="submitButton" type="submit">
                                {{ __('Login') }}
                            </button> <!-- disabled -->
                        </div>
                        <div class="col-6 col-md-3">
                            <p class="pt-2"> 
                                <a href="{{ route('auth.get.password_reset_option') }}">&nbsp;
                                    {{ __('Forgot Password') }}
                                </a>
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="pt-2"> {{ __("Don't have an account yet?") }}
                                <a href="{{ route('register') }}">&nbsp;
                                    {{ __('Register Here') }}
                                </a>
                            </p>
                        </div>
                    @endif

                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection