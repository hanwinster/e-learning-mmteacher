@extends('frontend.layouts.default')
@section('title', __('Courses'))

@section('header')
<section class="page-section mt-5" id="courses-category">
    <div class="container pt-1">
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ url('/') }}">{{__('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__('Courses') }}</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection
    @section('content')
    <div class="container pt-3">
        <div class="row">
            <div class="col-4">
                <div class="section-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="keyword" name="keyword" class="form-control" 
                        placeholder="{{ __('Search courses') }}" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-2">
                <div class="dropdown" id="level-filter-dd">
                    <button id="level-filter-btn" class="btn btn-secondary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        &nbsp;&nbsp;&nbsp;{{__('Search by level') }}
                    </button>
                    <ul  id="level-filter" class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        @foreach ($levels as $key => $level)
                            <li><a class="dropdown-item" id="course_level-{{$key}}">{{ __($level) }}</a></li>
                            <!-- <input type="hidden" name="course_level_{{$key}}"  value="{{ $key }}" />                   -->
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-3 mb-2">
            @foreach ($courseCategories as $category)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-2">
                    <button  name="course_category_{{$loop->index}}" value="{{$category->id}}"
                        class="btn btn-primary btn-md w-100 category-filter-buttons" type="button" >
                        {{ __($category->name) }} 
                    </button>                    
                </div>
            @endforeach
        </div>
        @if(isset($selectedCategory))
            @include('frontend.courses.main-component', ['selectedCategory' => $selectedCategory])
        @else
            @include('frontend.courses.main-component', ['selectedCategory' => null ])
        @endif

    </div>
    @endsection

</section>

@section('script')
@parent
@include('frontend.courses.partials.js')
@endsection