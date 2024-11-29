@extends('frontend.layouts.default')

@section('title', str_limit(strip_tags($course->title), 30) . ' - '. __('Courses'))

@section('content')
<section class="page-section learning-area" >
    <div class="container-fluid">
        <div class="row pl-2"> 
            <div class="col-12 col-md-3 border-shadow-box"> 
                @if($course->order_type == 'default')
                    @include('frontend.courses.partials.course-default')
                @else 
                    @include('frontend.courses.partials.course-flexible')
                @endif
            </div>
            <div class="col-12 col-md-9">
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
                                <h5 class="mt-2 mb-3">
                                    {!! $currentSection->lecture_title !!}&nbsp;&nbsp;
                                    @if($currentSection->resource_type == 'attach_file')
                                        @if($downloadOption == 1)
                                            <a href="{{ route('courses.download-lecture', $currentSection) }}"><i class="ml-3 fa fa-download"></i></a>
                                        @elseif($downloadOption == 2)
                                            @if(\App\Repositories\CourseLearnerRepository::isReadyToEvaluate($course))
                                                <a href="{{ route('courses.download-lecture', $currentSection) }}"><i class="ml-3 fa fa-download"></i></a>
                                            @endif
                                        @endif
                                    @endif
                                </h5>
                            </div>
                            <div class="col-12 col-md-6">
                                 <span class="tooltip-info form-check-label" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Overall Progress Percentage. The calculation is based on the completeness of lectures, quizzes and assignments!')">
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
                        @if($currentSection->resource_type == 'none')
                            @if($currentSection->resource_link)
                                <div class="col-12">
                                    <h5 class="primary-color">@lang('Resource Link')</h5>
                                    <p>
                                        <a href="{{$currentSection->resource_link}}" target="_blank">
                                            {{$currentSection->resource_link}}
                                        </a>
                                    </p>
                                </div>
                            @endif
                            @if($currentSection->description)
                                <div class="col-12 mt-3">
                                    <h5 class="primary-color">{{__('Lecture Description') }}</h5>
                                    <p>{!! $currentSection->description !!}</p>
                                </div>
                            @endif
                        @elseif($currentSection->resource_type == 'attach_file')                                    
                            @include('frontend.courses.partials.include-viewers', ['type' => 'lecture']) 
                        @else
                            <iframe width="100%" height="315" src="{{$currentSection->video_link}}" 
                                    title="YouTube video player" frameborder="0" allowfullscreen
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" ></iframe> 
                        @endif
                        <div class="text-right mt-1">
                                 
                            @php 
                                    $findVal = 'lect_'.$currentSection->id; 
                                    $isNextSectionAssess = \App\Repositories\CourseLearnerRepository::isNextSectionAssessment($findVal, $completed);
                                    $isReadyToAssess =  \App\Repositories\CourseLearnerRepository::isReadyToAssess($completed);
                                    $iscompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted('lect_'.$currentSection->id, $completed);
                            @endphp
                            @if($iscompleted)
                                <a href="{{ isset($previousSection) ? $previousSection : '#' }}"
                                    class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                    &lt;&nbsp;@lang('Previous') 
                                </a>
                                @if($isNextSectionAssess)
                                    <a href="{{ $isReadyToAssess ? $nextSection: '#' }}"
                                        class="btn btn-outline-primary btn-sm p-2 mb-2 {{ $isReadyToAssess ? '' : 'disabled' }}">
                                            @lang('Next')&nbsp;>
                                    </a>                            
                                @else              
                                    <a href="{{ isset($nextSection) ? $nextSection : '#' }}"
                                        class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) ? '' : 'disabled' }}">
                                        @lang('Next')&nbsp;>
                                    </a>    
                                @endif 
                                <button class="btn btn-primary disabled btn-sm p-2 mb-2" >
                                    @lang('Completed')&nbsp;<i class="fas fa-check"></i>
                                </button>
                            @else
                                {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-clect-prev-completion','class' => 'd-inline' )) !!}
                                    <input type="hidden" name="find_val" value="lect_{{$currentSection->id}}">
                                    <input type="hidden" name="lecture_id" value="{{$currentSection->id}}">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                    <button type="submit" name="previous"  value="{{ isset($previousSection) ? $previousSection : '#' }}" 
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                            &lt;&nbsp;@lang('Previous') 
                                    </button>
                                </form>
                                {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-clect-next-completion','class' => 'd-inline' )) !!}
                                    <input type="hidden" name="find_val" value="lect_{{$currentSection->id}}">
                                    <input type="hidden" name="lecture_id" value="{{$currentSection->id}}">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                    
                                        <button type="submit" name="next"  value="{{ $nextSection ? $nextSection: '#' }}" 
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) ? '' : 'disabled' }}">
                                            @lang('Next')&nbsp;>
                                        </button>
                                    
                                </form>      
                            @endif                    
                        </div> 
                        @if($currentSection->resource_type != 'none')                     
                            @if($currentSection->resource_link)
                                <div class="col-12">
                                    <h5 class="primary-color">@lang('Resource Link')</h5>
                                    <p>
                                        <a href="{{$currentSection->resource_link}}" target="_blank">
                                            {{$currentSection->resource_link}}
                                        </a>
                                    </p>
                                </div>
                            @endif
                            @if($currentSection->description)
                                <div class="col-12 mt-3">
                                    <h5 class="primary-color">{{__('Lecture Description') }}</h5>
                                    <p>{!! $currentSection->description !!}</p>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="col-12 col-lg-1"></div>
                </div>
                
            </div>         
        </div>
    </div>
</div>
@endsection


