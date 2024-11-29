@extends('frontend.layouts.default')

@section('title', str_limit(strip_tags($course->title), 30) . ' - '. __('Courses'))

@section('content')
<section class="page-section learning-area" >
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
                    <div id="learner-take-quiz" class="col-12 col-lg-10 course-content-area quiz">
                            <div class ="row">
                                <div class="col-12 col-md-6">
                                    <h5 class="mt-2 mb-3">                                     
                                        @if($currentQuiz->type == 'assignment')
                                            {!! $currentQuiz->title !!}
                                            &nbsp;&nbsp;<a href="{{ asset($assignmentMedia->getUrl()) }}" class="mb-3">
                                                <i class="ml-3 fa fa-download"></i>
                                            </a>
                                        @else
                                            {!! $currentQuiz->title !!}
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
                            <div class ="row">                            
                                <div class="col-12">
                                    @include('frontend.courses.partials.include-viewers', ['type' => 'quiz']) 
                                </div>   
                            </div>                    
                            <div class="text-right mt-3">
                               
                                @php 
                                    $findVal = $currentQuiz->lecture_id === null ? 'quiz_'.$currentQuiz->id : 'lq_'.$currentQuiz->id; 
                                    $isNextSectionAssess = \App\Repositories\CourseLearnerRepository::isNextSectionAssessment($findVal, $completed);
                                    $isReadyToAssess =  \App\Repositories\CourseLearnerRepository::isReadyToAssess($completed);                              
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
                                    @if($currentQuiz->type == 'assignment')
                                        <h5 class="primary-color mt-4">
                                            @lang('Comment From Instructor')
                                        </h5>
                                        <p>
                                            {!! isset($assignmentInfo->comment) ? $assignmentInfo->comment : __('No comment from the instructor yet!') !!}
                                        </p> 
                                    @endif
                                @else
                                    @if($currentQuiz->type != 'assignment')                             
                                        {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-quiz-prev-completion','class' => 'd-inline' )) !!}
                                            <input type="hidden" name="find_val" value="{{$findVal}}">
                                            <input type="hidden" name="quiz_id" value="{{$currentQuiz->id}}">
                                            <input type="hidden" name="course_id" value="{{$course->id}}">
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                            
                                            <button id="{{ $findVal }}_{{ auth()->user()->id }}" type="submit" name="previous"  
                                                    value="{{ isset($previousSection) ? $previousSection : '#'  }}"  {{$longAnswerUser ? 'disabled': ''  }}
                                                    class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) && $previousSection ? '' : 'disabled' }} 
                                                    {{ $longAnswerUser ? '' : 'quiz-mark-complete'}}">
                                                    &lt;&nbsp;@lang('Previous') 
                                            </button>
                                        </form>
                                        {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-quiz-next-completion','class' => 'd-inline' )) !!}
                                            <input type="hidden" name="find_val" value="{{$findVal}}">
                                            <input type="hidden" name="quiz_id" value="{{$currentQuiz->id}}">
                                            <input type="hidden" name="course_id" value="{{$course->id}}">
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                            
                                                <button id="next-{{ $findVal }}_{{ auth()->user()->id }}" type="submit" name="next"  
                                                    value="{{ isset($nextSection) ? $nextSection: '#' }}" {{$longAnswerUser ? 'disabled': ''  }}
                                                    class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) && $nextSection ? '' : 'disabled' }} 
                                                    {{ $longAnswerUser ? '' : 'quiz-mark-complete-next' }}">
                                                    @lang('Next')&nbsp;>
                                                </button>
                                           
                                        </form>                                
                                    @else <!-- just for assignments only -->
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
                                        <p class="text-dark fw-bold">@lang('This section will be completed after submitting the assignment!')
                                    @endif
                                @endif
                                                     
                            </div>     
                            <!-- @if($currentQuiz->description)
                                <div class="col-12 mt-3">
                                    <h5 class="primary-color">{{__('Description') }}</h5>
                                    <p>{!! $currentQuiz->description !!}</p>
                                </div>
                            @endif -->
                            @if($currentQuiz->type == 'assignment')
                                <div class="col-12 mt-3">
                                    <form action="{{ route('courses.submit-quiz-assignment', $assignment) }}" method="POST" enctype="multipart/form-data">                                          
                                            @csrf
                                            <div class="col-12 mt-3">
                                                <h5 class="primary-color mb-3">{{__('Assignment Submission') }}</h5>
                                                <input type="file" name="assignment_file" id="assignment_file"><br>
                                                {!! $errors->first('assignment_file', '<span class="help-block" style="color:red">:message</span>') !!}
                                            
                                                <div class="mt-2">
                                                    <input type="hidden" name="find_val" value="{{$findVal}}">
                                                    <input type="hidden" name="assignment_id" value="{{$assignment->id}}">
                                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                                    <input type="hidden" name="quiz_id" value="{{$currentQuiz->id}}">
                                                    <input type="submit" class="btn btn-primary btn-sm" value="@lang('Submit')">
                                                </div>
                                            </div>                   
                                    </form>
                                </div>  
                            @endif
                            @if($currentQuiz->type == 'assignment' && $currentQuiz->questions[0]->description)       
                                <div class="col-12 mt-5">
                                    <h5 class="primary-color">{{__('Assignment Instruction') }}</h5>
                                    <p>{!! $currentQuiz->questions[0]->description !!}</p>
                                </div>                         
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    @parent
    <script>
        $(document).ready(function () { 
            $('.quiz-mark-complete').prop('disabled',true); 
            $('.quiz-mark-complete-next').prop('disabled',true);
        });
    </script>
@endsection