@extends('layouts.default')
@section('title', __('Request Reset Token'))

@section('header')
<div class="section mb-0 pb-0">
	<header class="text-white" style="background-color: #4CAF50">
	<!--<canvas class="constellation" data-radius="0"></canvas>-->
		<div class="container text-center h-50">
			<div class="row">
				<div class="col-md-8 mx-auto">
				   <!-- <h1>{{ __('Dashboard') }}</h1>-->
				</div>
			</div>  
		</div>
	</header>
</div>

@endsection

@section('content')
<main class="main-content">
	<section class="section bg-gray" style="background-image:  url({{ asset('assets/img/bg/1.jpg') }});">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-4">
            
					<div class="card">
						<div class="bg-white rounded shadow-7 w-400 mw-100 p-6">		
							<h5 class="mb-7">{{ __('Request Reset Token') }}</h5>
						
							@if (session('error'))
								<div class="alert alert-danger" role="alert">
									{{ session('error') }}
									<br>
									{{--<a href="{{ route('auth.request.otp') }}">Resend OTP?</a>--}}
								</div>
							@endif

							<form method="POST" action="{{ route('auth.reset-password.verify-token') }}">
							<!-- @csrf-->
							
							{{ csrf_field() }}

								<div class="form-group ">
									
									<input id="token" type="text" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token" placeholder="Enter Token" value="{{ old('token') }}" >
									@if ($errors->has('token'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('token') }}</strong>
										</span>
									@endif
									
								</div>

								<div class="form-group ">								
									<button class="btn btn-block btn-primary" type="submit">
										{{ __('VERIFY') }}
									</button>								
								</div>
							</form>
                
						</div>
					</div>
    
			
				</div>
			</div>
		</div>
	</section>
</main>
@endsection 