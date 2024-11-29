<div class="col-12 col-md-5 col-lg-4">

    <h5 class="primary-color mt-3">
        @if(auth()->check())
            @if(\App\Repositories\CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course))
                @php 
                    $lastVisited = \App\Repositories\CourseLearnerRepository::goToLastSection(auth()->user()->id, $course);
                @endphp
                <a href="{{ \App\Repositories\CourseLearnerRepository::goToLastSection(auth()->user()->id, $course) }}" 
                    class="btn btn-primary btn-md w-100">{{__('Continue') }}</a>
            @else
                <a href="{{ route('courses.take-course', $course) }}" class="btn btn-primary btn-md w-100">{{__('Take This Course') }}</a>
            @endif
        @else
            <a id="take-course-guest" class="btn btn-primary btn-md w-100">
                {{__('Take This Course') }}
            </a>
        @endif
    </h5>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" 
                    aria-expanded="true" aria-controls="collapseOne">
                    {{__('Lectures') }}
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul>
                        @foreach($lectures as $lecture)
                            <li>
                                <a class="tooltip-info" data-toggle="tooltip" data-placement="top" title="{{ strip_tags($lecture->lecture_title) }}">
                                    {{ str_limit(strip_tags($lecture->lecture_title), config('cms.lecture_title_right_navi_ch_limit'), '...') }}
                                </a>
                            </li>      
                            <ul class="ml-3">                 
                                @foreach($course->quizzes()->where('lecture_id', $lecture->id)->get() as $quiz)
                                    <li>
                                        <a class="tooltip-info" data-toggle="tooltip" data-placement="top" title="{{ strip_tags($quiz->title) }}">
                                            {{ str_limit(strip_tags($quiz->title), config('cms.quiz_title_right_navi_ch_limit'), '...') }}<br>
                                            <small class="text-dark">{{ count($quiz->questions) }}&nbsp;{{ count($quiz->questions) > 1 ? ' Questions' : 'Question' }}</small>
                                        </a>
                                    </li>                              
                                @endforeach
                            </ul>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
       
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" 
                    aria-expanded="false" aria-controls="collapseTwo">
                    {{__('Course Quizzes') }}
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    @if( $course->quizzes->count() == 0 )
                        <span>{{__('No Quiz Found') }}</span>
                    @else
                    <ul>
                        @foreach($course->quizzes()->where('course_id', $course->id)->get() as $quiz)
                            @if($quiz->lecture_id == null)
                                <li>
                                    <a class="tooltip-info" data-toggle="tooltip" data-placement="top" title="{{ strip_tags($quiz->title) }}">
                                        {{ str_limit(strip_tags($quiz->title), config('cms.quiz_title_right_navi_ch_limit'), '...') }}<br>
                                        {{ count($quiz->questions) }}&nbsp;{{ count($quiz->questions) > 1 ? ' Questions' : 'Question' }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" 
                    aria-expanded="false" aria-controls="collapseThree">
                    {{__('Evaluations') }}
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    @if($course->course_type_id == 1)
                        <p class="text-primary">{{ __('There will be evaluation session before certification!')}}</p>
                    @else
                        <p class="text-primary">{{ __('There will be evaluation session where you can provide feedback for this course!')}}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" 
                    aria-expanded="false" aria-controls="collapseFour">
                    {{__('Live Sessions') }}
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="alert alert-success alert-dismissible fade show text-center">
                        
                        @if(isset($liveSessions) && count($liveSessions) > 0)
                            <p class="info-text">{{__('Planned Live Session(s) will be on') }}</p>
                            <ul>
                                @foreach($liveSessions as $key => $data) 
                                <li>{{ $data->topic }}&nbsp;&nbsp;-&nbsp;&nbsp;{{ $data->start_date }}&nbsp;{{ $data->start_time }}</li>

                                @endforeach
                            </ul>
                        @else
                            {{__('There is no live session planned yet!') }}
                        @endif
                        @if(auth()->check())
                            <!-- @if(\App\Repositories\CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course))
                                <button class="btn btn-primary btn-sm" id="submitButton" type="submit">{{__('Send Request to Join') }}</button>
                            @else
                                @lang('Take the course first to join the live sessions!')
                            @endif -->
                        @else 
                            <div class="container-fluid pt-3">
                                <h6>{{__('Please login to take the course and join the live sessions!') }}</h6>
                                <a class="btn btn-primary btn-sm" href="{{ route('login') }}">{{__('Login') }}</a>
                            </div>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <!-- <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" 
                        aria-expanded="false" aria-controls="collapseSix">
                        {{__('Assessment & Certificate') }}
                </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample"> -->

                    <!-- <div class="accordion-body">
                        <a onclick="showCert()">{{__('View Certificte')}}</a>
                    </div> -->
                <!-- <div class="accordion-body">
                    @if($course->getCourseType($course->course_type_id)->name == 'Certified')
                        <p class="info-text">
                            @lang('Certificate of course completion will be provided after completing the course')
                        </p>
                    @else
                        <p class="info-text">
                            @lang('No Certificate will be provided after completing the course')
                        </p>
                    @endif
                </div>    
            </div> -->
    </div>
   

    @if($relatedResources && isset($relatedResources->data))
        <div class="justify-content-center mt-3">
            <h5 class="primary-color mt-3 mb-3">
                @lang('Related Resources')
            </h5>
            @foreach ($relatedResources->data as $idx => $resource)
                @if($idx < 3)
                <div class="col-12 mb-3">
                    <div class="portfolio-item mx-auto {{ $course->lang == 'my-MM' ? 'my-MM' : '' }}">
                        <div class="card">
                            <img class="card-img-top" src="{{ $resource->cover_image->medium }}" 
                                alt="{{ $resource->title }}">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                        title="{{ strip_tags($resource->title) }}">
                                            {{ str_limit(strip_tags($resource->title), 28, '...') }}
                                    </span>
                                </h4>
                                <p class="card-text">
                                    <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                                title="{{ strip_tags($resource->description) }}">
                                            {{ str_limit(strip_tags($resource->description), 88, '...') }}
                                    </span>
                                </p>
                                <a href="{{ $resource->permalink }}" class="btn btn-primary btn-md">{{ __('Learn More') }}</a>                               
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @endif
</div>