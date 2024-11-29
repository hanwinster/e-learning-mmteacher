@extends('layouts.default')
@section('title', __('Reset Password Option'))

@section('content')
<section class="page-section" style="height:98%;">
	<div class="container pt-5">
		<div class="row">
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a class="breadcrumb-link" href="/">{{ __('Home') }}</a></li>
					<li class="breadcrumb-item active" aria-current="page">{{ __('Reset Password Option') }}</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="container pt-3">
		<!-- Contact Section Heading-->
		<h3 class="page-section-heading text-center text-secondary mb-0">{{ __('Reset Password Option') }}</h3>
	
		<!-- Contact Section Form-->
		<div class="row justify-content-center pt-3">
			<div class="col-12 col-sm-12 col-md-11 col-lg-10 col-xl-9 col-xxl-8">
			
				<form id="loginForm" method="POST" action="{{ route('auth.post.password_reset_option') }}">
					<!--data-sb-form-api-token="API_TOKEN" -->
					@csrf
					
					<!-- Checkbox -->
					<div class="form-check mb-3">
						<input class="form-check-input" id="user-choice" checked="" disabled="" type="checkbox" name="notification_channel[]" value="email" />
						<label class="form-check-label" for="user-email">{{__('Email') }}<span class="text-red">*</span></label>
						<input class="custom-control-input" type="hidden" name="notification_channel[]" value="email">
						<input class="custom-control-input" type="hidden" name="notification_channel[]" value="">
					</div>

					<div class="row">
						<div class="col-3">
							<!-- Submit Button-->
							<button class="btn btn-primary btn-lg " id="continueButton" type="submit">
								{{ __('Continue') }}
							</button> <!-- disabled -->
						</div>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
@endsection