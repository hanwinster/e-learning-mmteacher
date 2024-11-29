@extends('layouts.default')
@section('title', __('Email'))


@section('content')
<section class="page-section" style="height:98%;">
	<div class="container pt-5">
		<div class="row">
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a class="breadcrumb-link" href="/">{{ __('Home') }}</a></li>
					<li class="breadcrumb-item active" aria-current="page">{{ __('Reset Password') }}</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="container pt-3">
		<!-- Contact Section Heading-->
		<h2 class="page-section-heading text-center text-secondary mb-0">{{ __('Reset Password') }}</h2>
		<!-- <divider></divider> -->
		<!-- Contact Section Form-->
		<div class="row justify-content-center pt-3">
			<div class="col-12 col-sm-12 col-md-11 col-lg-10 col-xl-9 col-xxl-8">
			
				<form id="loginForm" method="POST" action="{{ route('password.email') }}">
					<!--data-sb-form-api-token="API_TOKEN" -->
					@csrf
					@if (session('status'))
						<div class="alert alert-secondary alert-dismissible fade show text-center">
						{{ session('status') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif
					<div class="form-floating mb-3">
                        <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                            name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required>
						<label for="email">{{__('Email') }}<span class="text-red">*</span></label>
						@if ($errors->has('email'))
                            <div class="invalid-feedback" data-sb-feedback="user-email:required">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
					</div>

					<div class="row">
						<div class="col-6">
							<!-- Submit Button-->
							<button class="btn btn-primary btn-lg " id="sendPasswordRestLinkButton" type="submit">
                            {{ __('Send Password Reset Link') }}
							</button> <!-- disabled -->
						</div>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
@endsection
 