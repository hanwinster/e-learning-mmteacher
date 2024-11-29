@extends('layouts.default')

@section('title', __('Account Verified'))

@section('content')
<section class="page-section">
    <div class="container mt-5">
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="/">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Account Verified') }}</li>
                </ol>
            </nav>
        </div>

        <div class="container mt-2">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('Account Verified') }}</h4>

                            <div class="alert alert-success" role="alert">
                                {{ __('Your account has been successfully verified!') }} <br>
                                {{ __('If you have verified for an independent learner or teacher account, you can log in to the system rightaway!')}} <br>
                                {{ __('If you have verified for an EDC teacher account, you can log in to the system once the administrator has approved your account') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
