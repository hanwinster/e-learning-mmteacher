{{--@extends('errors::illustrated-layout')

@section('code', '401')
@section('title', __('Unauthorized'))

@section('image')
<div style="background-image: url({{ asset('/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __('Sorry, you are not authorized to access this page.')) --}}

@extends('frontend.layouts.default')

@section('code', '404')
@section('title', __('Page Not Found'))

@section('header')
---
@endsection

@section('content')
<main class="main-content text-center pb-lg-8 section">
    <div class="container">

      <h1 class="display-1 text-muted mb-7">Unauthorized</h1>
      <p class="lead">Seems you're not unauthorized for this resource.</p>
      <br>
      <button class="btn btn-secondary w-150 mr-2" type="button" onclick="window.history.back();">Go back</button>
      <a class="btn btn-secondary w-150" href="{{ url('/') }}">Return Home</a>

    </div>
  </main>
@endsection

@section('message', __('Sorry, the page you are looking for could not be authorized.'))

