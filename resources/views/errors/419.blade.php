@extends('errors::illustrated-layout')

@section('code', '419')
@section('title', __('Page Expired'))

@section('image')
<div style="background-image: url({{ asset('/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('extra_button')
    <a href="{{ route('login') }}">
        <button class="bg-transparent text-grey-darkest font-bold uppercase tracking-wide py-3 px-6 border-2 border-teal-light hover:border-grey rounded-lg">
            {{ __('Login') }}
        </button>           
    </a> 
@endsection

@section('message', __('Sorry, your session has expired. Please login again!'))

{{-- @extends('frontend.layouts.default')

@section('code', '419')
@section('title', __('Page Expired'))

@section('header')
@endsection

@section('content')
<main class="main-content text-center pb-lg-8 section">
    <div class="container">

      <h1 class="display-1 text-muted mb-7">{{ __('Session Expired') }}</h1>
      <p class="lead">__('Sorry, your session has expired. Please login again!')</p>
      <br>
      <a class="btn btn-primary btn-md" href="{{ url('/') }}"> {{ __('Home') }}</a>
      <br>
      <a class="btn btn-success btn-md" href="{{ route('login') }}"> {{ __('Login') }}</a>
    </div>
  </main>
@endsection

@section('message', __('Sorry, your session has expired. Please login again!')) --}}

