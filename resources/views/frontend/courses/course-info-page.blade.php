@extends('frontend.layouts.default')

@section('title', str_limit(strip_tags($course->title), 30) . ' - '. __('Courses'))

@section('content')
<section class="page-section learning-area" >
    <div class="container-fluid">
        <div class="row pl-2"> 
            <div id="course-side-bar" class="col-12 col-md-3 border-shadow-box"> 
                @if($course->order_type == 'default')
                    @include('frontend.courses.partials.course-default')
                @else 
                    @include('frontend.courses.partials.course-flexible')
                @endif
            </div>
            <div id="course-main-content" class="col-12 col-md-9">               
                <div class="row">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.my-courses') }}">{{ __('My Courses') }}</a></li>
                            <li class="breadcrumb-item active">{{ strip_tags($course->title) }}</li>
                        </ol>
                    </nav>
                </div>
                @include('frontend.layouts.form_alert')
                <div class="row">
                    <div class="col-12 col-lg-1"></div>
                    <div class="col-12 col-lg-10 course-content-area">
                        <div class ="row">
                            <div class="col-12 col-md-6">
                                <h5 class="mt-2 mb-3 primary-color">
                                    {!! $course->title !!}&nbsp;&nbsp;
                                    @if( ( $course->getMedia('course_resource_file')->first() ) && 
                                        file_exists( $course->getMedia('course_resource_file')->first()->getPath() ) )
                                        @if($downloadOption == 1)
                                            <a href="{{ route('courses.download-course', $course) }}"><i class="ml-3 fa fa-download"></i></a>
                                        @elseif($downloadOption == 2)
                                            @if(\App\Repositories\CourseLearnerRepository::isReadyToEvaluate($course))
                                                <a href="{{ route('courses.download-course', $course) }}"><i class="ml-3 fa fa-download"></i></a>
                                            @endif
                                        @endif
                                    @endif
                                </h5>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="tooltip-info form-check-label" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Overall Progress Percentage. The calculation is based on the completeness of lectures, quizzes and assessment!')">
                                    <span class="tag text-right">
                                        {{ $percentage }}%
                                    </span>
                                </span>
                                <div class="progress text-right">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $percentage }}%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>                                             
                        <div class="panel">
                            <div class="embed-responsive embed-responsive-16by9 mt-4" id="vdo-wrapper">
                                @if($course->is_display_video)
                                <iframe width="100%" height="315" src="{{$course->video_link}}" 
                                    title="YouTube video player" frameborder="0" allowfullscreen
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" ></iframe> 
                                @else
                                    <img src="{{ get_course_cover_image($course) }}" class="course-cover-image" 
                                        alt="{{ $course->title }}" id="course-cover-img" height="315">    
                                @endif        
                            </div>
                        </div>
                        <div class="col-12 text-right mt-1">
                            <!-- <a class="btn btn-outline-primary btn-sm p-2 mb-2 disabled }}">
                                &lt;&nbsp;@lang('Previous')  
                            </a>                                                 -->
                            @php 
                                $iscompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted('intro_'.$course->id, $completed);
                            @endphp
                            @if($iscompleted)
                                <a href="{{ isset($previousSection) && $previousSection ? $previousSection : '#'  }}"
                                        class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                        &lt;&nbsp;@lang('Previous') 
                                </a>
                                <a href="{{ isset($nextSection) && $nextSection ? $nextSection : '#' }}"
                                    class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) && $nextSection ? '' : 'disabled' }}">
                                    @lang('Next')&nbsp;>
                                </a> 
                                <button class="btn btn-primary disabled btn-sm p-2 mb-2" >
                                    @lang('Completed')&nbsp;<i class="fas fa-check"></i>
                                </button>                              
                            @else
                                {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-clect-prev-completion','class' => 'd-inline' )) !!}
                                    <input type="hidden" name="find_val" value="intro_{{$course->id}}">
                                    <input type="hidden" name="lecture_id" value="null">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                    <button type="submit" name="previous"  value="{{ isset($previousSection) && $previousSection ? $previousSection : '#' }}" 
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                            &lt;&nbsp;@lang('Previous') 
                                    </button>
                                </form>
                                {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-clect-next-completion', 'class' => 'd-inline' )) !!}                                                 
                                    <input type="hidden" name="find_val" value="intro_{{$course->id}}">
                                    <input type="hidden" name="lecture_id" value="null">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                    <button type="submit" name="next" 
                                        value="{{ isset($nextSection) && $nextSection ? $nextSection : '#' }}" 
                                        class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) ? '' : 'disabled' }}">
                                        @lang('Next')&nbsp;>
                                    </button>
                                </form>     
                            @endif                    
                        </div>
                        @if($course->description)
                            <div class="col-12 mt-3">
                                <h5 class="primary-color">{{__('Course Description') }}</h5>
                                <p>{!! $course->description !!}</p>
                            </div>
                        @endif
                        @if($course->objective)
                            <div class="col-12">
                                <h5 class="primary-color">{{__('Course Objectives') }}</h5>
                                <p>{!! $course->objective !!}</p>
                            </div>
                        @endif
                        @if($course->learning_outcome)
                            <div class="col-12">
                                <h5 class="primary-color">{{__('Learning Outcomes') }}</h5>
                                <p>{!! $course->learning_outcome !!}</p>
                            </div>
                        @endif
                        @if($course->url_link)
                            <div class="col-12">
                                <h5 class="primary-color">@lang('Resource Link')</h5>
                                <p><a href="{{$course->resource_link}}" target="_blank">{{$course->url_link}}</a></p>
                            </div>
                        @endif
                        
                    </div>
                    <div class="col-12 col-lg-1"></div>
                </div>
                
            </div>         
        </div>      
    </div>
</div>
@endsection



