@extends('layouts.default')
@section('title', __('Account Verification'))

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
							<h5 class="mb-7">{{ __('Request OTP') }}</h5>
						
							@if (session('status'))
								<div class="alert alert-success" role="alert">
									{{ session('status') }}
								</div>
							@endif

							<form method="POST" action="{{ route('auth.resend.otp') }}">
							<!-- @csrf-->
							
							{{ csrf_field() }}

								<div class="form-group ">
									
									<input id="credential" type="text" class="form-control{{ $errors->has('credential') ? ' is-invalid' : '' }}" name="credential" placeholder="Enter Username or Email" value="{{ old('credential') }}" >
									@if ($errors->has('credential'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('credential') }}</strong>
										</span>
									@endif
									
								</div>

								<div class="form-group ">								
									<button class="btn btn-block btn-primary" type="submit">
										{{ __('SEND OTP') }}
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