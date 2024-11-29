@extends('frontend.layouts.default')

@section('header')
    <div class="section mb-0 pb-0">
        <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
            <div class="container text-center">
                <h2>{{__('My Courses') }}</h2>
            </div>
        </header>
    </div>
@endsection

@section('breadcrumbs')
    <div class="bg-gray">
        <div class="container">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.my-courses') }}">{{ __('My Courses') }}</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-md-12">
                <form action="{{ route('power-point-test') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="file" class="form-control" name="ppt-file">
                    <input type="submit" value="Upload" class="btn btn-primary mt-5">
                </form>
            </div>
        </div>
    </div>
@endsection




{{--t!c5BsWVjy7DjhG--}}
