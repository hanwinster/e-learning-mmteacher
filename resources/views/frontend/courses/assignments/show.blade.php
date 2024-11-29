@extends('frontend.layouts.default')
@section('title', str_limit(strip_tags($course->title), 30) . ' - '. __('Courses'))

@section('content')
<section class="page-section mt-5" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-3 border-shadow-box">
                @if($course->order_type == 'default')
                    @include('frontend.courses.partials.course-default')
                @else 
                    @include('frontend.courses.partials.course-flexible')
                @endif
            </div>
            <div class="col-12 col-md-9">
                @if(\Illuminate\Support\Facades\Session::get('message'))
                    <!-- @include('layouts.form_alert')   -->
                    <div class="alert alert-success alert-dismissible fade show" role="alert"> 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        {{ \Illuminate\Support\Facades\Session::get('message') }}
                    </div>
                @endif
                <div class="row">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.my-courses') }}">{{ __('My Courses') }}</a></li>
                            <li class="breadcrumb-item active">{{ $course->title }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-1"></div>
                    <div class="col-12 col-lg-10 course-content-area">
                        <div class ="row mb-3">
                            <div class="col-12 col-md-6">
                                <h5>{{ $assignment->title }}&nbsp;&nbsp;
                                    <a href="{{ asset($assignmentMedia->getUrl()) }}" class="mb-3">
                                        <i class="ml-3 fa fa-download"></i>
                                    </a>
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
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                            </div>                            
                        </div>
                        <div class ="row">
                            <div class="col-12 col-lg-1"></div>
                            <div class="col-12 col-lg-11">
                                <iframe src="https://docs.google.com/viewer?url={{ str_replace(config('app.url'), config('app.url'), 
                                    asset($assignmentMedia->getUrl()) ) }}&embedded=true" 
                                    width="100%"
                                    height="540"
                                    allowfullscreen
                                    webkitallowfullscreen
                                ></iframe>
                            </div>   
                            <div class="col-12 col-lg-1"></div>
                        </div> 
                        <!-- <iframe
                            src="{{ str_replace(config('app.url'), config('app.url') . '/ViewerJS/#..', 
                                    asset($assignmentMedia->getUrl()) ) }}" width='700' height='550' allowfullscreen webkitallowfullscreen></iframe> -->
                        <div class="text-right mr-5 mt-3">
                            <a href="#"
                                    class="btn btn-outline-success btn-sm p-2 mb-2 disabled">
                                    &lt;&nbsp;@lang('Previous') 
                            </a> 
                            <a href="#"
                                    class="btn btn-outline-success btn-sm p-2 mb-2 disabled">
                                    &lt;&nbsp;@lang('Next')> 
                            </a>                             
                        </div>
                        <p class="heading lead mt-5">
                            @lang('Assignment Instruction')
                        </p>
                        <p>
                            {!! $assignment->description !!}
                        </p>
                        <!-- <a href="{{ asset($assignmentMedia->getUrl()) }}" >@lang('Download Assignment')</a> -->
                        @php 
                            $findVal = $assignment->lecture_id === null ? 'assignment_'.$assignment->id : 'lassignment_'.$assignment->id; 
                            $iscompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted($findVal, $completed);
                        @endphp
                        @if($iscompleted)
                            <button class="btn btn-success disabled btn-sm p-2 mb-2" >
                                @lang('Submitted the assignment & completed')&nbsp;<i class="fas fa-check"></i>
                            </button>
                            <p class="heading lead mt-5">
                                @lang('Comment From Instructor')
                            </p>
                             <p>
                                {!! isset($assignmentInfo->comment) ? $assignmentInfo->comment : 'Not Yet' !!}
                            </p>                          
                        @else
                            <form action="{{ route('courses.submit-assignment', $assignment) }}" method="POST" enctype="multipart/form-data">                                          
                                @csrf
                                <div class="col-md-4 mt-3">
                                    <input type="file" name="assignment_file" id="assignment_file"><br>
                                    {!! $errors->first('assignment_file', '<span class="help-block" style="color:red">:message</span>') !!}
                                </div>
                            
                                <div class="col-md-12 mt-3">
                                    <div class="justify-content-center">
                                        <input type="hidden" name="find_val" value="{{$findVal}}">
                                        <input type="hidden" name="assignment_id" value="{{$assignment->id}}">
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                        <input type="submit" class="btn btn-primary btn-sm" value="@lang('Submit Assignment')">
                                    </div>
                                </div>                   
                            </form>
                        @endif
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</section>
@endsection