@extends('frontend.layouts.default')

@section('title', str_limit(strip_tags($session->topic), 30) . ' - '. __('Courses'))

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
                    <div class="col-12 col-lg-10 course-content-area">
                        <div class ="row">
                            <div class="col-12 col-md-6">
                                <h5>{{ $session->topic }}&nbsp;&nbsp;</h5>
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
                        <p class="heading lead mt-5">
                            @lang('Session Date And Time')
                        </p>
                        <p>
                            {{ $session->start_date }}&nbsp;{{ $session->start_time }}
                        </p>
                        <p class="heading lead mt-5">
                            @lang('Session Agenda')
                        </p>
                        <p>
                            {{ $session->agenda }}
                        </p>
                        <p class="heading lead mt-5">
                            @lang('Session Duration')
                        </p>
                        <p>
                            {{ $session->duration }}&nbsp;@lang('minutes')
                        </p>
                        @php
                            $stillCanReg = \App\Repositories\LiveSessionRepository::stillCanRegister($session->start_date,$session->start_time);
                            $findVal = $session->lecture_id === null ? 'session_'.$session->id : 'lsess_'.$session->id; 
                        @endphp
                        @if(isset($liveSessionUser) && $liveSessionUser->id)
                            <p class="heading lead mt-3">
                                <i class="fas fa-info-circle"></i>&nbsp;@lang('You have already registered for this session')
                            </p>
                            <p>@lang('Join URL')</span>&nbsp;:&nbsp;
                                <a href="{{$session->join_url}}" target="_blank">{{$session->join_url}}</a>
                            </p>
                            <p>@lang('Passcode')</span>&nbsp;:&nbsp;
                                <span>{{$session->passcode}}</span>
                            </p>
                        @else
                            
                            @if($stillCanReg) 
                                <p class="heading lead mt-5">                             
                                    @lang('If you are interested in joining the session, please register by clicking the register button below!')
                                </p>
                                <form action="{{ route('courses.register-session') }}" method="POST" >                                          
                                    @csrf
                                    <div class="col-12 mt-5">
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
                                        <input type="hidden" name="name" value="{{ auth()->user()->name }}" />
                                        <input type="hidden" name="email" value="{{ auth()->user()->email }}" />
                                        <input type="hidden" name="meeting_id" value="{{ $session->meeting_id }}" />
                                        <input type="hidden" name="session_id" value="{{ $session->id }}" />
                                        <input type="hidden" name="find_val" value="{{$findVal}}">
                                        <input type="hidden" name="session_id" value="{{$session->id}}">
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                        <button name="register-session" value="1" class="btn btn-primary btn-md">
                                            @lang('Register')
                                        </button>
                                    </div>                                  
                                </form>
                            @else
                                <p class="heading lead mt-5">                             
                                    <i class="fas fa-info-circle"></i>&nbsp;@lang('The registration period for this session is over')
                                </p>
                            @endif
                        @endif
                        <div class="text-right mt-1">
                            
                            @php             
                                $findVal = $session->lecture_id === null ? 'session_'.$session->id : 'lsess_'.$session->id;                   
                                $isNextSectionAssess = \App\Repositories\CourseLearnerRepository::isNextSectionAssessment($findVal, $completed);
                                $isReadyToAssess =  \App\Repositories\CourseLearnerRepository::isReadyToAssess($completed);
                                $iscompleted = \App\Repositories\CourseLearnerRepository::isThisPartCompleted($findVal, $completed);
                            @endphp
                                                                                                 
                            @if($iscompleted)
                                <a href="{{ isset($previousSection) && $previousSection ? $previousSection : '#'  }}"
                                        class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                        &lt;&nbsp;@lang('Previous') 
                                </a>
                                @if($isNextSectionAssess)
                                    <a href="{{ $isReadyToAssess ? $nextSection: '#' }}"
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ $isReadyToAssess ? '' : 'disabled' }}">
                                            @lang('Next')&nbsp;>
                                    </a>                            
                                @else
                                    <a href="{{ isset($nextSection) && $nextSection ? $nextSection: '#' }}"
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) ? '' : 'disabled' }}">
                                            @lang('Next')&nbsp;>
                                    </a>  
                                @endif   
                                <button class="btn btn-primary disabled btn-sm p-2 mb-2" 
                                        >@lang('Completed')&nbsp;<i class="fas fa-check"></i>
                                </button>
                            @else
                                @if(!$stillCanReg) 
                                    {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-session-prev-completion' , 'class' => 'd-inline'  )) !!}
                                        <input type="hidden" name="find_val" value="{{$findVal}}">
                                        <input type="hidden" name="session_id" value="{{$session->id}}">
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                        <button type="submit" name="previous"  value="{{ isset($previousSection) ? $previousSection : '#' }}" 
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                            &lt;&nbsp;@lang('Previous') 
                                        </button>
                                    </form>
                                    {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-session-next-completion' , 'class' => 'd-inline'  )) !!}
                                        <input type="hidden" name="find_val" value="{{$findVal}}">
                                        <input type="hidden" name="session_id" value="{{$session->id}}">
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                       
                                            <button type="submit" name="next"  value="{{ $nextSection ? $nextSection: '#' }}" 
                                                class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) ? '' : 'disabled' }}">
                                                @lang('Next')&nbsp;>
                                            </button>
                                        
                                    </form>
                                @endif
                                <!-- {!! \Form::open(array('method' => 'POST', 'route' => 'courses.update-session-completion' , 'class' => 'd-inline'  )) !!}
                                        <input type="hidden" name="find_val" value="{{$findVal}}">
                                        <input type="hidden" name="session_id" value="{{$session->id}}">
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                        <button id="session-mark-complete" class="btn btn-primary btn-sm p-2 mb-2" 
                                            type="submit">@lang('Mark Complete')
                                        </button>
                                </form>  -->
                            @endif
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

