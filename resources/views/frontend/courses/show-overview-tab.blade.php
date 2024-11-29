<div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
    <div class="course-overview mt-3">
        @if($course->description)
            <h5 class="primary-color">{{__('Course Description') }}</h5>
            <p class="">
                {!! $course->description !!}
            </p>
        @endif
        @if($course->objective)
            <h5 class="primary-color">{{__('Course Objectives') }}</h5>
            <p class="">
                {!! $course->objective !!}
            </p>
        @endif
        @if($course->learning_outcome)
            <h5 class="primary-color">{{__('Learning Outcomes') }}</h5>
            <p class="">
                {!! $course->learning_outcome !!}
            </p>
        @endif
        @if($course->url_link)
            <h5 class="primary-color">{{__('Resource Link') }}</h5>
            <p class="">
                <a href="{{ $course->url_link }}" >{{$course->url_link}}</a>
            </p>
        @endif
        <h5 class="primary-color mt-3">{{__('Assessment and Certification') }}</h5>
        @if($course->getCourseType($course->course_type_id)->name === 'Certified')
            <p class="info-text">
                {{__('In order to get the certification, you need to finish all the course work, quizzes and final assessment.') }}
            </p>
        @else 
            <p class="info-text">
                {{__('No certificate will be provided for this course.') }}
            </p>
        @endif
        <!-- <div class="container-fluid mt-3">  
            <div class="row mt-3">
                <div class="col-12"> -->
        <h5 class="primary-color mt-3">{{__('Rating & Reviews') }}</h5>
        @if (auth()->check() && $course->allow_feedback) 
            <div class="review-history mt-2">
                @foreach($ratingReviews as $idx => $rr) 
                <div class="pb-2">
                    <div class="card border shadow-6">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-10">

                                </div>
                                <div class="col-2">
                                    <div class="rating">
                                        @for($i= 1; $i <= 5; $i++)
                                            @if($i <= $rr->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <span class="no-rating"><i class="far fa-star"></i></span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                <p>{{ $rr->remark }}</p>
                                <p class="small-2 text-lighter mb-0">
                                    <span class="text-muted">
                                        {{ __('By') }}&nbsp;<em>{{ $rr->user->name ?? '' }}</em>&nbsp;
                                        {{ __('at') }}&nbsp;{{ $rr->created_at }}
                                    </span>
                                </p>  
                                </div>                               
                            </div>                                           
                        </div>
                    </div>
                </div>              
                @endforeach
            </div>      
        @endif

        
                    @if (auth()->check())                      
                        @if (!$amICourseOwner)
                            @if ($course->allow_feedback)
                                @if(\App\Repositories\CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course))
                                    <h5 class="primary-color mt-4">{{__('Leave Your Reviews Here') }}</h5>
                                    <form action="{{ route('member.rating-review.store', $course->id) }}" method="post">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <div class="course-rating"></div>
                                            <input type="hidden" name="rating" id="rating" required value="">
                                            <input type="hidden" name="course_id" required value="{{ $course->id }}">
                                            <input type="hidden" name="user_id" required value="{{ auth()->user()->id }}">
                                            {!! $errors->first('rating', '<div class="invalid-feedback">:message</div>') !!}
                                            <br/> 
                                        </div>
                                        <div class="form-group">
                                            <textarea name="remark" class="form-control{{ $errors->has('remark') ? ' is-invalid' : '' }}" 
                                                rows="4"></textarea>
                                            {!! $errors->first('remark', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group mt-3">
                                            <button class="btn btn-primary btn-md">{{ __('Submit Review') }}</button>
                                        </div>
                                    </form>
                                @else
                                    <h6 class="info-text"><i class="fas fa-exclamation-circle"></i> <!-- fa-2x -->
                                        &nbsp;{{ __('You need to take this course to rate and leave a review.') }}
                                    </h6>
                                @endif                               
                            @else
                                <h6 class="info-text"><i class="fas fa-exclamation-circle"></i>&nbsp;{{ __('Currently feedbacks are not allowed for this course.') }}</h6>
                            @endif
                        @else 
                            <h6 class="info-text"><i class="fas fa-exclamation-circle"></i>&nbsp;{{ __('You are the owner of this course and so cannot rate and review it!') }}</h6>                      
                        @endif                       
                    @else       
                        <h6>{{__('Please login and take the course to rate and review.') }}</h6>
                        <a class="btn btn-primary btn-sm" href="{{ route('login') }}">{{__('Login') }}</a>     
                    @endif
                <!-- </div>      
            </div>
        </div> -->
    </div>
    
</div>