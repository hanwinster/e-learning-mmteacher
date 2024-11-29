@extends('frontend.layouts.default')

@section('title', __('Profile') . $user->name)

@section('header')
<header class="header text-white" style="background-image: linear-gradient(80deg,#510fa8 0,#320fa8 100%) !important;">
        <div class="container text-center h-100">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h1>{{ $user->name }}</h1>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
<main class="main-content">
aaa
<section class="section bg-gray">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
        </div>
    </div>

</section>
</main>
@endsection