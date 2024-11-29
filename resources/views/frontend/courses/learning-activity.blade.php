@extends('frontend.layouts.default')

@section('title', str_limit(strip_tags($learningActivity->title), 30) . ' - '. __('Courses'))

@section('content')
<section class="page-section learning-area">
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
                                <h5>{!! $learningActivity->title !!}&nbsp;&nbsp;</h5>
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
                        
                        @if($learningActivityMedias && count($learningActivityMedias) > 0)                                    
                            @include('frontend.courses.partials.include-viewers', ['type' => 'learning_activity']) 
                        @else
                            @if($currentSection->description)
                                <div class="col-12 mt-3">
                                    <!-- <h5 class="primary-color">{{__('Description') }}</h5> -->
                                    <p>{!! $currentSection->description !!}</p>
                                </div>
                            @endif   
                        @endif
                        <div class="text-right mt-1">                         
                            @php 
                                $findVal = $currentSection->lecture_id === null ? 'learning_'.$currentSection->id : 'lla_'.$currentSection->id; 
                                $isNextSectionAssess = \App\Repositories\CourseLearnerRepository::isNextSectionAssessment($findVal, $completed);
                                $isReadyToAssess =  \App\Repositories\CourseLearnerRepository::isReadyToAssess($completed);
                            @endphp
                                                                       
                            @php                              
                                $iscompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted($findVal, $completed);
                            @endphp
                            @if($iscompleted)
                                <a href="{{ isset($previousSection) ? $previousSection : '#'  }}"
                                        class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                        &lt;&nbsp;@lang('Previous') 
                                </a> 
                                
                                @if($isNextSectionAssess)
                                <a href="{{ $isReadyToAssess ? $nextSection: '#' }}"
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ $isReadyToAssess ? '' : 'disabled' }}">
                                            @lang('Next')&nbsp;>
                                    </a>                            
                                @else
                                    <a href="{{ isset($nextSection) ? $nextSection: '#' }}"
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) ? '' : 'disabled' }}">
                                            @lang('Next')&nbsp;>
                                    </a> 
                                @endif  
                                <button class="btn btn-primary disabled btn-sm p-2 mb-2" 
                                    >@lang('Completed')&nbsp;<i class="fas fa-check"></i>
                                </button>
                            @else
                                {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-learning-activity-prev-completion', 'class' => 'd-inline' )) !!}
                                    <input type="hidden" name="find_val" value="{{$findVal}}">
                                    <input type="hidden" name="learning_activity_id" value="{{$currentSection->id}}">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                    <button type="submit" name="previous"  value="{{ isset($previousSection) ? $previousSection : '#' }}" 
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                            &lt;&nbsp;@lang('Previous') 
                                    </button>
                                </form> 
                                {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-learning-activity-next-completion', 'class' => 'd-inline' )) !!}
                                    <input type="hidden" name="find_val" value="{{$findVal}}">
                                    <input type="hidden" name="learning_activity_id" value="{{$currentSection->id}}">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                    
                                        <button type="submit" name="next"  value="{{ $nextSection ? $nextSection: '#' }}" 
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ $nextSection ? '' : 'disabled' }}">
                                            @lang('Next')&nbsp;>
                                        </button>                                       
                                    
                                </form> 
                                
                            @endif
                        </div>       
                        @if($learningActivityMedias && count($learningActivityMedias) > 0) 
                            @if($currentSection->description)
                                <div class="col-12 mt-3">
                                    <!-- <h5 class="primary-color">{{__('Description') }}</h5> --> 
                                    <p>{!! $currentSection->description !!}</p>
                                </div>
                            @endif  
                        @endif           
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

