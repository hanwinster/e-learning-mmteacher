@extends('frontend.layouts.default')

@section('title', str_limit(strip_tags($course->title), 50) . ' - '. __('Courses'))

@php
    if (get_course_cover_image($course)) {
        $img_url = get_course_cover_image($course);
    } else {
        $img_url = 'assets/img/og-img.jpg';
    }
@endphp
@section('og_image', asset($img_url))
@section('meta_description',str_limit(strip_tags($course->description), 200))
@section('og_description', str_limit(strip_tags($course->description), 160))

@section('content')
<section class="page-section" id="category-courses">
    <div class="container mt-3">
        @if(\Illuminate\Support\Facades\Session::get('message'))
        <div class="alert alert-warning alert-dismissible fade show learning-area" role="alert">
            {{ \Illuminate\Support\Facades\Session::get('message') }}
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ str_limit(strip_tags($course->title), config('cms.breadcrumb_title_ch_limit'), '...') }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- end of breadcrumb -->

    <div class="container pt-5">
        <div class="row">
            <div class="col-12 col-md-7 col-lg-8 col-lg-">
                <h4 class="course-title primary-color">
                    {!! $course->title !!}
                </h4>
                
                <h6 class="course-property-bar mb-2">
                    <i class="fas fa-award"></i>&nbsp;
                    @lang($course->getCourseType($course->course_type_id)->name)&nbsp;&nbsp;&nbsp;
                    <i class="fas fa-clock"></i>&nbsp;
                    {{$course->estimated_duration}}&nbsp;
                    @lang($course->estimated_duration_unit)&nbsp;&nbsp;&nbsp;
                    <i class="fas fa-users"></i>&nbsp;
                    {{$course->learners->count()}}&nbsp;@lang('enrollment(s)')&nbsp;&nbsp;&nbsp;
                    <i class="fas fa-layer-group"></i>&nbsp;
                    {{$course->lectures->count()}}&nbsp;@lang('lecture(s)')&nbsp;&nbsp;&nbsp;
                    <i class="fas fa-question-circle"></i>&nbsp;
                    {{$course->quizzes->count()}}&nbsp;@lang('quizz(es)')&nbsp;&nbsp;&nbsp;
                    <!-- <i class="fas fa-tasks"></i>&nbsp;
                    {{$course->assessmentQuestionAnswers->count()}}&nbsp;@lang('assessment(s)')&nbsp;&nbsp;&nbsp; -->
                    <i class="fas fa-eye"></i>&nbsp;
                    {{$course->view_count}}&nbsp;@lang('view(s)')&nbsp;&nbsp;&nbsp;
                </h6>
                
                <div class="row">
                    @if (config('cms.sharing_enabled') && auth()->user())
                        <div class="col-12 col-sm-6 col-md-2">                   
                            <div class="addthis_inline_share_toolbox d-inline"></div>&nbsp;&nbsp;  
                        </div>                 
                    @endif
                    
                    <div class="col-12 col-sm-6 col-md-4">
                    @if ($finalRating > 0) 
                        <div class="d-inline rating">
                            @for($i=0; $i < $finalRating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            &nbsp;({{$ratingCount}})
                        </div>
                    @else
                        <div class="d-inline no-rating">                 
                            @for($i=0; $i < 5; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                            &nbsp;@lang('No Rating Yet')
                        </div>
                    @endif
                    </div>
                </div>                                         
                <!-- <div class="addthis_sharing_toolbox addthis-smartlayers addthis-smartlayers-desktop" 
                        data-url="https://mmteacherplatform.net/en/e-learning/courses/ms-sql-server-testing" 
                        data-title="{{ $course->title}}" data-description="{{ $course->title}}" 
                        data-media="https://mmteacherplatform.net/storage/7978/mysql.png"></div> -->
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
                <!-- Tabs -->
                <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#overview" 
                        type="button" role="tab" aria-controls="overview" aria-selected="false">{{__('Overview') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="discussion-tab" data-bs-toggle="tab" data-bs-target="#discussion" 
                            type="button" role="tab" aria-controls="discussion" 
                            aria-selected="true">{{__('Discussions') }}</button>
                    </li>
                    
                   
                    <!-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rating-tab" data-bs-toggle="tab" data-bs-target="#rating" 
                            type="button" role="tab" aria-controls="rating" 
                            aria-selected="false">{{__('Rating And Reviews') }}</button>
                    </li> -->
                </ul>

                <div class="tab-content" id="myTabContent">
                    @include('frontend.courses.show-overview-tab')
                    @include('frontend.courses.show-discussion-tab')                                                       
                </div>
                <!-- End of Tabs -->
            </div> <!-- end of main div -->
            @include('frontend.courses.partials.course-right-navi')
        </div>
    </div>

     <!------------------- Login Modal Start -------------------------->
     <div id="loginRegModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Login') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="login-form-dialog" class="" method="POST" action="{{ route('auth.login-via-dialog') }}" > <!--data-sb-form-api-token="API_TOKEN" -->
                            @csrf
                            <!-- Email address input-->
                            <div class="form-floating mb-3">
                                <input class="form-control {{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}" 
                                    id="user-email" type="text" placeholder="{{__('Username or Email Address') }}" 
                                    name="email" value="{{ old('username') ?: old('email') }}" /> <!-- data-sb-validations="required" -->
                                <label for="user-email">{{__('Username or Email Address') }}<span class="text-red">*</span></label>
                                <!-- <div class="invalid-feedback" data-sb-feedback="user-email:required">
                                    {{ __('Username or email address is required.') }}
                                </div> -->
                                @if ($errors->has('username') || $errors->has('email'))
                                    <div class="invalid-feedback" data-sb-feedback="user-email:required">
                                        {{ $errors->first('username') ?: $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                            <!-- Phone number input-->
                            <div class="form-floating mb-3">
                                <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" 
                                        name="password" type="password" placeholder="{{ __('Password') }}" /> <!-- data-sb-validations="required"  -->
                                <label for="password">{{ __('Password') }}<span class="text-red">*</span></label>
                                <!-- <div class="invalid-feedback" data-sb-feedback="password:required">
                                    {{ __('Password is required.') }}
                                </div> -->
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback" data-sb-feedback="password:required">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>                     
                            <!-- an error submitting the form-->
                            <div class="d-none" id="submitErrorMessage">
                                <div class="text-center text-danger mb-3">
                                    {{ __('Error occured while submitting the form. Please try again!') }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    
                                    <button class="btn btn-primary btn-lg " type="submit">
                                        {{ __('Login') }}
                                    </button>&nbsp;
                                    <a href="{{ route('auth.get.password_reset_option') }}">
                                        &nbsp;{{ __('Forgot Password') }}
                                    </a>
                                </div>
                                <div class="col-12">
                                    <p class="pt-2"> 
                                        
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="pt-2"> {{ __("Don't have an account yet?") }}
                                        <!-- <a id="show-register-form" class="nav-link d-inline cursor-pointer">&nbsp;
                                            {{ __('Register Here') }}
                                        </a> -->
                                        <a href="{{ route('register') }}" class="nav-link d-inline cursor-pointer">&nbsp;
                                            {{ __('Register Here') }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </form>

                        <!-- <form id="register-form-dialog" class="d-none" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            
                        </form> -->

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark btn-md" data-bs-dismiss="modal">
                                @lang('Close')
                            </button>
                        </div>               
                    </div>
                </div>
            </div>
        </div>
        <!------------------- Login Modal End -------------------------->
</section>

@endsection
@section('css')
@parent
<link rel="stylesheet" href="{{ asset('assets/js/jquery.star-rating-svg/star-rating-svg.css') }}">

@endsection

@section('script')
@parent
<script src="{{ asset('assets/js/jquery.star-rating-svg/jquery.star-rating-svg.js')}}"></script>
<script type="text/javascript">

/*     $("#input-id").rating(); */
    $(document).ready(function() {
        $(".course-rating").starRating({
            starSize: 25,
            totalStars: 5,
            useFullStars: true,
            disableAfterRate: false,
            callback: function(currentRating, $el) {
                $('#rating').val(currentRating);
            }
        });
        $(document).on('click', '#take-course-guest', function() {
            $('#loginRegModal').modal('show');
        });

        $(document).on('click', '#show-register-form', function() {
            $('#register-form-dialog').removeClass('d-none');
            $('#login-form-dialog').addClass('d-none');
        });

        $(document).on('click', '#show-login-form', function() {
            $('#register-form-dialog').addClass('d-none');
            $('#login-form-dialog').removeClass('d-none');
        });
       
    // console.log('is auth user ? ',isAuthenticatedUser);
        // setTimeout(function() { 
            
        // }, 2000);
        var authUserId = {!!json_encode($authUserId, JSON_HEX_TAG) !!};
        var appUrl = {!! json_encode(env('APP_URL')) !!};
        //console.log('authUserId id is ', authUserId);
        if(authUserId) {
            var messagesElement = document.getElementById("messages");
            //const username_input = document.getElementById("username");
            //console.log('messages id is ', messagesElement);
            const messageInput = document.getElementById("message-input");
            const messageForm = document.getElementById("message-form");
            const userid = document.getElementById("userid");
            const userName = document.getElementById("userName");
            const discussionId = document.getElementById("discussionId");
            const isParticipatedBefore = document.getElementById("isParticipatedBefore");
            const hasMessages = document.getElementById("hasMessages");
            $('#message-input').on('input', function(e) {
                e.preventDefault();
            // console.log('typing'); 
                //$('.typing-indicator').removeClass('d-none');
            });

            // $("#discussion-tab").on("click", function () {
            //     messagesElement = document.getElementById("messages");
            //     console.log('messages id after clicking on discussion tab is ', messagesElement);
            // });
            // $('#message-input').on('blur', function() {
                //console.log('blur'); 
                //$('.typing-indicator').addClass('d-none');
            // });
            var translations = {
                                enterMessage: "@lang('Please enter msg')",
                                right: "@lang('Your answer is corect!')",
                                wrong: "@lang('Your answer is wrong!')",                          
                            };
        if(messageForm) {
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                    //$('.typing-indicator').addClass('d-none');
                let has_errors = false;
                if (messageInput.value == '') {
                    alert(translations.enterMessage);
                    has_errors = true;
                }
                if (has_errors) {
                    return;
                }
                const options = {
                    method: 'post',
                    url: appUrl+'/api/add-message',
                    data: {
                            user_id: userid.value,
                            username: userName.value,
                            discussion_id: discussionId.value,
                            message: messageInput.value
                    }
                };
                axios(options);                
            });
        }    
            window.Echo.channel('chat')
                .listen('.message', (e) => {
                        //console.log('message ',e, messagesElement);
                        //messagesElement.innerHTML += '<div class="message"><strong>'+e.username+':</strong> '+e.message+'</div>';
                        messageInput.value = '';
                        let tempDate = new Date(e.createdAt);
                        let createdAt = tempDate.getDate()+'-'+tempDate.getMonth()+'-'+tempDate.getFullYear()+' '
                                        +tempDate.getHours()+':'+tempDate.getMinutes();
                                      //  console.log(isParticipatedBefore.value, hasMessages.value);
                        // if(!isParticipatedBefore.value ) { 
                        //     //console.log('about to reload'); //&& hasMessages.value
                        //     //window.location.reload();
                        //     messagesElement.innerHTML +=
                        //     '<li class="clearfix">'+
                        //         '<div class="message-data">'+
                        //             '<span class="message-data-time">'+createdAt+'</span>'+
                        //         '</div>'+
                        //         '<div class="message my-message">'+e.message+'</div>'+
                        //     '</li>';
                        // }
                        if(authUserId == e.userId || !isParticipatedBefore) {
                            //console.log("no participnats before or my msg view");
                            messagesElement.innerHTML +=
                            '<li class="clearfix">'+
                                '<div class="message-data">'+
                                    '<span class="message-data-time">'+createdAt+'</span>'+
                                '</div>'+
                                '<div class="message my-message">'+e.message+'</div>'+
                            '</li>';
                        } else {
                            //console.log("have participants before");
                            messagesElement.innerHTML +=        
                            '<li class="clearfix">'+
                                '<div class="message-data text-right">'+
                                    '<span class="message-data-time">'+e.username+'</span>'+
                                        '<img src="'+e.avatar +'" alt="'+e.username+'">'+
                                        '<small class="message-data-time d-block me-5">'+createdAt+'</small>'+
                                '</div>'+
                                '<div class="message other-message float-right"> '+
                                    e.message+
                                '</div>'+
                            '</li>';
                        }    
            });       
        }
        
    });
</script>
@endsection