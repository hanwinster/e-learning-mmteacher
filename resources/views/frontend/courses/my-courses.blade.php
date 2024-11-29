@extends('frontend.layouts.default')
@section('title', __('My Courses'))

@section('content') 
<section class="page-section mt-5" id="my-courses">
    <div class="container pt-1">
        @if(\Illuminate\Support\Facades\Session::get('message'))
        <div class="alert alert-primary">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
            {{ \Illuminate\Support\Facades\Session::get('message') }}
        </div>
        @endif
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
                    <li class="breadcrumb-item active">@lang('My Courses')</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- end of breadcrumb -->

    <div class="container pt-5" style="min-height: 20rem">
        <form action="{{ route('courses.my-courses') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <select name="sort_by" id="sort_by" class="form-control">
                        <option value="">~ Sorted By ~</option>
                        <option value="title">Title</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="course_category" id="course_category" class="form-control">
                        <option value="">~ Select Category ~</option>
                        @foreach($courseCategories as $courseCategory)
                            <option value="{{ $courseCategory->id }}"
                                {{ $request->course_category == $courseCategory->id ? 'selected' : '' }}
                            >{{ $courseCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="progress" id="progress" class="form-control">
                        <option value="">~ @lang('Progress') ~</option>
                        <option value="not_started"
                            {{ $request->progress == 'not_started' ? 'selected' : '' }}
                        >@lang('Not Started')</option>
                        <option value="learning"
                            {{ $request->progress == 'learning' ? 'selected' : '' }}
                        >@lang('Learning')</option>
                        <option value="completed"
                            {{ $request->progress == 'completed' ? 'selected' : '' }}
                        >@lang('Completed')</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex">
                        <input type="submit" class="btn btn-primary btn-md" value="Search">&nbsp;&nbsp;
                        <a href="{{ route('courses.my-courses') }}" class="btn btn-secondary btn-md ml-1">@lang('Reset')</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="row justify-content-center mt-5">
            @foreach($courses as $key => $course)
                <div class="col-md-6 col-lg-4 mb-5">
                    <div class="portfolio-item mx-auto">
                        <div class="card">
                            @if($course->getThumbnailPath())
                                    <img class="card-img-top" src="{{ asset($course->getThumbnailPath()) }}" alt="{{ $course->title }}">
                            @elseif(!file_exists(public_path($course->getThumbnailPath())))
                                @foreach($course->media as $mediafile)
                                    @php
                                        $_filename = $mediafile->file_name;
                                    @endphp
                                    <img class="card-img-top" src="{{ asset('storage/'.$mediafile->id.'/'.$_filename) }}" 
                                        alt="{{$course->title }}">
                                @break
                                @endforeach                            
                            @else
                                <img class="card-img-top" src="{{ asset('assets/img/vector/3.png') }}" 
                                      alt="{{ $course->title }}">
                            @endif 
                            <div class="card-body p-2 pb-3">
                                <h5 class="card-title mt-2">
                                @if($course->is_published)
                                    <a href="{{ url('/e-learning/courses') }}/{{ $course->slug }}" >
                                        {{ str_limit(strip_tags($course->title), 40, '...') }}
                                    </a>
                                @else 
                                    {{ str_limit(strip_tags($course->title), 40, '...') }}
                                @endif
                                </h5>
                                <p class="info-text" style="min-height:78px;height:78px">
                                    {{ str_limit(strip_tags($course->description), 100, '...') }}
                                </p>
                                <p class="info-text">
                                @foreach($course->course_categories as $idx=>$category)
                                    {{ __($categories[$category]) }} 
                                    @if($idx !== count($course->course_categories) -1 ) , @endif
                                @endforeach
                                </p>
                                <p class="info-text">
                                    {{ __(\App\Models\Course::LEVELS[$course->course_level_id]) }}                                
                                </p>
                                <p class="info-text">       
                                    @if($statusAndPercent[$key]['status'])                  
                                        {{ __(\App\Models\CourseLearner::COURSE_LEARNER_STATUSES[$statusAndPercent[$key]['status']]) }}
                                    @else 
                                        -
                                    @endif
                                </p>
                                <span class="tag">
                                {{ $statusAndPercent[$key]['percentage'] }}%
                                </span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $statusAndPercent[$key]['percentage'] }}%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                @if($course->lectures->first())
                                    <div class="d-grid gap-2">
                                        @if($course->is_published)
                                            <a href="{{ \App\Repositories\CourseLearnerRepository::goToLastSection(auth()->user()->id, $course) }}"
                                                class="btn btn-primary btn-sm btn-block mt-3">
                                                @lang('Continue')
                                            </a>
                                        @else 
                                            <span class="tooltip-info form-check-label" data-toggle="tooltip" data-placement="top" 
                                                title="@lang('This course is currently unpublished')">
                                                <span class="btn btn-secondary btn-sm btn-block disabled mt-3" style="width: 100%;">@lang('Continue')</span>
                                            </span> 
                                        @endif
                                        @if($statusAndPercent[$key]['percentage']==100 &&  auth()->user()->id != $course->user_id)                          
                                            <a class="btn btn-danger btn-sm disabled mt-3">
                                                @lang('Cancel')
                                            </a>
                                        @else
                                            @php 
                                                $isEng = App::getLocale() == 'en' ? true : false;
                                            @endphp
                                            @if($isEng)
                                                {!! Form::open(array('route' => array('courses.cancel-course', $course), 'method' => 'get'
                                                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");')) !!}
                                            @else
                                                {!! Form::open(array('route' => array('courses.cancel-course', $course), 'method' => 'get'
                                                                , 'onsubmit' => 'return confirm("ဖျက်သိမ်းရန် သေချာပြီလား?");', 'style' => 'display: inline', '')) !!}
                                            @endif
                                                <button data-provide="tooltip" data-toggle="tooltip" title="Delete" 
                                                    type="submit" class="btn btn-danger btn-sm btn-block mt-3" style="width:100%">
                                                    @lang('Cancel')
                                                </button>
                                            {!! Form::close() !!}
                                        @endif
                                    </div>
                                @else
                                    <a href="#" class="btn btn-danger mt-3">@lang('No Records')!</a>
                                @endif
                            </div>                           
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="text-center">
                    {{ $courses->appends([
                        'sort_by' => $request->sort_by,
                        'course_category' => $request->course_category,
                        'progress' => $request->progress,
                    ])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
