<?php
    //$isZawgyi = App::getLocale() == 'my-ZG' ? true : false;
    $isPDS = App::getLocale() == 'my-MM' ? true : false;
?>
<!-- Masthead-->
<header class="text-white text-center vh-100">
    
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="z-index:500;">
        <div class="carousel-indicators">
            @foreach($slides as $key => $slide)
                @if($key == 0 )
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$key}}" 
                        class="active" aria-current="true" aria-label="Slide {{$key+1}}"></button>
                @else
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$key}}" 
                        aria-label="Slide {{$key+1}}"></button>
                @endif
            @endforeach
        </div>
        <div class="carousel-inner"> 
            @foreach($slides as $key => $slide)
            @php
                $images = $slide->getMedia('slides');
            @endphp
            <div class="carousel-item sliders {{ $key == 0 ? 'active': '' }}">
                @foreach($images as $image)
                    @if($key ==  4)
                        <!-- <div class="wrap"> -->
                            <img src="{{ asset($image->getUrl()) }}" class="d-block w-100" alt="" >
                        <!-- </div> -->
                    @else
                        <img src="{{ asset($image->getUrl()) }}" class="d-block w-100" alt="" >
                    @endif
                @endforeach
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">@lang('Previous')</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">@lang('Next')</span>
        </button>
    </div>
    <div class="container-fluid justify-content-center align-items-center" style="z-index:1000;position:absolute;bottom:14rem; ">
        <div class="row">
            <div class="d-none d-md-block col-md-1 col-lg-3"></div>
            <div class="col-12 col-md-10 col-lg-6">
                <div class="search" >
                {{ Form::open(array('route' => 'elearning.index', 'method' => 'get')) }}  
                    <!-- <i class="fas fa-graduation-cap"></i> -->
                    <img src="{{ asset('assets/img/logos/E_learning.png') }}"
                        alt="E-Learning" width="20px" height="20px" />
                    
                    <input type="text" class="form-control" name="search" placeholder="{{ __('Explore our courses') }}..."
                        value="{{ request('search') }}">                                 
                    <button class="btn btn-primary" href="{{ route('courses.index') }}"><i class="fas fa-search"></i></button>
                </form>
                </div>
            </div>
            <div class="d-none d-md-block col-md-1 col-lg-3"></div>
        </div>
    </div>
    <div class="container-fluid stick-bottom bg-secondary home-cat-container"> 
        
            @if($isPDS) 
            <div class="row"> 
                <div class="col-12 col-sm-6 col-lg-3 mt-4">
                    <span class="fs-h3">{{__('Find courses in') }}</span>
                </div> 
                <div class="col-12 col-sm-6 col-lg-3 mt-1">
                    <a  name="course_category" value="@lang('Education for Peace and Sustainable Development (EPSD)')" href="{{ route('elearning.browseByCategory') }}/?category=10"
                        class="btn btn-primary btn-lg w-100" type="button" >
                            {{__('Education for Peace and Sustainable Development (EPSD)') }}
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 mt-1">
                    <a  name="course_category" value="@lang('Media and Information Literacy')" href="{{ route('elearning.browseByCategory') }}/?category=5"
                        class="btn btn-primary btn-lg w-100 " type="button" >
                            {{ __('Media and Information Literacy (MIL)') }}
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 mt-1">
                    <a  name="course_category" value="@lang('View All Categories')" href="{{ route('courses.index') }}"
                        class="btn btn-primary btn-lg w-100" type="button" >
                        @include('frontend.layouts.partials.menu-zg2uni', 
                            [ 'isZawgyi' => false, 'text' => __('View All Categories') ])
                    </a>
                </div>
            </div>
            @else 
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-4">
                    <a  name="course_category" value="@lang('Education for Sustainable Development (EPSD)')" href="{{ route('elearning.browseByCategory') }}/?category=10"
                        class="btn btn-primary btn-lg w-100" type="button" >
                            {{__('Education for Peace and Sustainable Development (EPSD)') }}
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 mt-3">
                    <a  name="course_category" value="@lang('Media and Information Literacy')" href="{{ route('elearning.browseByCategory') }}/?category=5"
                        class="btn btn-primary btn-lg w-100 " type="button" >
                            {{ __('Media and Information Literacy (MIL)') }}
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 mt-3">
                    <a  name="course_category" value="@lang('View All Categories')" href="{{ route('courses.index') }}"
                        class="btn btn-primary btn-lg w-100" type="button" >
                            {{ __('View All Categories') }}
                    </a>
                </div>
            </div>
            @endif
            
        </div>
    </div>
</header>

