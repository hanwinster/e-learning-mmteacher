@extends('layouts.default')

@section('title', __('Account Verification'))

@section('content')
<section class="page-section">
    <div class="container mt-5">
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="/">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Account Verification') }}</li>
                </ol>
            </nav>
        </div>

        <div class="container mt-2">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ __('Account Verification') }}</h2>
            <!-- <divider></divider> -->
            <div class="row justify-content-center mt-2">
                <div class="col-12 col-md-5">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                        <br>
                        <a href="{{ route('auth.request.otp') }}">@lang('Resend OTP')?</a>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('auth.verify.post_otp') }}">
                        <!-- @csrf-->
                        {{ csrf_field() }}
                        @if ($message = Session::get('error'))
							<div class="alert alert-danger alert-block">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<h4>{{ __('Error') }}</h4>
								{{ $message }}
							</div>
                        @endif

                        <div class="form-floating mb-3">
                            <input id="otp_code" type="text" class="form-control{{ $errors->has('otp_code') ? ' is-invalid' : '' }}" name="otp_code" 
								placeholder="@lang('Enter OTP')" value="{{ old('otp_code') }}">
                            <label for="otp_code">{{ __('OTP') }}<span class="text-red">*</span></label>
                            @if ($errors->has('otp_code'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('otp_code') }}</strong>
								</span>
                            @endif
                        </div>

                        <div class="form-group ">
                            <button class="btn btn-primary btn-lg" style="width:100%" type="submit">
                                {{ __('VERIFY') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection