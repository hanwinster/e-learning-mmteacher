@extends('frontend.layouts.default')

@section('code', '404')
@section('title', __('Page Not Found'))

@section('header')
---
@endsection

@section('content')
<main class="main-content text-center pb-lg-8 section">
    <div class="container">

      <h1 class="display-1 text-muted mb-7">Page Not Found</h1>
      <p class="lead">Seems you're looking for something that doesn't exist.</p>
      <br>
      <button class="btn btn-secondary w-150 mr-2" type="button" onclick="window.history.back();">Go back</button>
      <a class="btn btn-secondary w-150" href="{{ url('/') }}">Return Home</a>

    </div>
  </main>
@endsection

@section('message', __('Sorry, the page you are looking for could not be found.'))
