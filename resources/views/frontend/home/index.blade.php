@extends('frontend.layouts.default')

@section('title', __('Home'))

@section('header')
    @include('frontend.layouts.partials.header') 
@endsection

@section('content')
    @include('frontend.home.partials.home-featuring') 
    @include('frontend.home.partials.home-static') 
    @include('frontend.home.partials.home-contact') 
@endsection

