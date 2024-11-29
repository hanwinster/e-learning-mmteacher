@php
    $isShorter = strpos($_SERVER['REQUEST_URI'], "/login") ||
    strpos($_SERVER['REQUEST_URI'], "/password_reset_option") ||
    strpos($_SERVER['REQUEST_URI'], "/password/reset") ||
    strpos($_SERVER['REQUEST_URI'], "/password/reset") ||
    strpos( $_SERVER['REQUEST_URI'], "/verify/get_otp")||
    strpos( $_SERVER['REQUEST_URI'], "/verify/post_otp") ? true : false;
    $isShorter = false;
    $isRoot = ( $_SERVER['REQUEST_URI'] === '/en/e-learning' || $_SERVER['REQUEST_URI'] === '/my-MM/e-learning') ? true : false;
    $isLearning = strpos($_SERVER['REQUEST_URI'], "e-learning/courses/learning") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/lecture") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/quiz") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/quiz-long-answer") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/assignment") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/live-session") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/summary") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/learning-activity") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/assessment") || 
                  strpos($_SERVER['REQUEST_URI'], "e-learning/courses/evaluation") ? true : false;
    $isEn = App::getLocale() == 'en' ? true : false;
    $isLogged = auth()->check() ? auth()->user()->id : null;
@endphp

<!-- video Section-->
@if($isRoot == true)
<a id="video-footer">
    <span class="boxclose" id="box-close" onclick="hideVideoBar()"></span>
    <video id="i-video" width="250" height="140" controls autoplay muted preload="auto">
        <source src="{{ asset('assets/videos/About-Mm-Teacher-Platform_2.mp4') }}" type="video/mp4">
    </video>
</a>
@endif
<!-- Copyright Section-->
<div id="copyright-footer" class="container-fluid copyright py-4 text-center text-white">
    <div class="container">
        <small>@lang('Copyright') &copy;  @php echo date('Y'); @endphp&nbsp;
            {{env('APP_NAME')}}. {{__('All Rights Reserved') }}
        </small>
    </div>
</div>

<!-- Back to top button -->
<button type="button" class="btn btn-primary btn-floating btn-md" id="btn-back-to-top">
  <i class="fas fa-angle-up"></i>
</button>

<!------------------- Contact Modal Start -------------------------->
<div id="contactModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Contact Us') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contactForm"> 
                    <div class="container">                  
                        <!-- Contact Section Form-->
                        <div class="row justify-content-center">
                            @include('layouts.form_alert') 
                            <div class="col-lg-4 col-xl-5">
                                @php 
                                    $contact = \App\Repositories\PageRepository::getPageContentsBySlug('contact-us');
                                @endphp
                                @if (isset($contact->body) && $isEn)
                                    {!! Blade::compileString($contact->body) !!}
                                @else 
                                {!! Blade::compileString($contact->body_mm) !!}
                                @endif
                            </div>
                            <div class="col-lg-8 col-xl-7">
                                <!-- data-sb-form-api-token="API_TOKEN" -->
                                {{ csrf_field() }}
                                <!-- Name input-->
                                <div class="form-group mb-2">
                                    <label for="name">{{__('Name') }}<span class="text-red">*</span></label>
                                    <input class="form-control {{ isset($errors) && $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="cname" 
                                        value="{{ old('name') }}" placeholder="{{__('Enter your name...') }}" />                                  
                                    <div id="cname-invalid" class="d-none text-red">
                                        {{__('Name is required.') }}
                                    </div>
                                </div>
                                <!-- Email address input-->
                                <div class="form-group mb-2">
                                    <label for="email">{{__('Email Address') }}<span class="text-red">*</span></label>
                                    <input class="form-control {{ isset($errors) && $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="cemail" 
                                        value="{{ old('email') }}" placeholder="name@example.com" data-sb-validations="required,email" />                                   
                                    <div id="cemail-invalid" class="d-none text-red" data-sb-feedback="email:required">
                                        {{__('An email is required.') }}
                                    </div>
                                    <!-- <div class="invalid-feedback" data-sb-feedback="email:email">
                                        {{__('Email is not valid.') }}
                                    </div> -->
                                </div>                           
                                <!-- Subject input-->
                                <div class="form-group mb-2">
                                    <label for="name">{{__('Subject ') }}<span class="text-red">*</span></label>
                                    <input class="form-control {{ isset($errors) && $errors->has('subject') ? ' is-invalid' : '' }}" name="subject" id="csubject" 
                                        value="{{ old('name') }}" placeholder="{{__('Enter subject...') }}" />                                  
                                    <div id="csubject-invalid" class="d-none text-red">
                                        {{__('Subject is required.') }}
                                    </div>
                                </div>
                                <!-- Message input-->
                                <div class="form-group mb-2">
                                    <label for="message">{{__('Message') }}<span class="text-red">*</span></label>
                                    <textarea class="form-control t-area" id="cmessage" type="text" name="cmessage"
                                        placeholder="{{__('Enter your message here...') }}">{{ old('name') }}</textarea>                                 
                                    <div id="cmessage-invalid" class="d-none text-red" data-sb-feedback="message:required">
                                        {{__('A message is required.') }}
                                    </div>
                                </div>
                                <!-- Phone number input-->
                                <div class="form-group mb-2">
                                    <label for="phone">{{__('Mobile Number') }}</label>
                                    <input class="form-control" type="text" class="form-control {{ isset($errors) && $errors->has('phone_no') ? ' is-invalid' : '' }}" 
                                        name="phone_no" id="cphone_no" value="{{ old('phone_no') }}" placeholder="(123) 456-7890" />                                
                                    <div class="invalid-feedback">
                                        {{__('A mobile number is not valid.') }}
                                    </div>
                                </div>
                                <!-- Organization input-->
                                <div class="form-group mb-2">
                                    <label for="organization">{{__('Organization/Company') }}</label>
                                    <input class="form-control {{ isset($errors) && $errors->has('organization') ? ' is-invalid' : '' }}" name="organization" id="corganization" 
                                        value="{{ old('organization') }}" placeholder="{{__('Enter organization...') }}" />                                  
                                    <div class="invalid-feedback">
                                        {{__('Organization is invalid.') }}
                                    </div>
                                </div>
                                <!-- State/Region  input-->
                                <div class="form-group mb-2">
                                    <label for="region_state">@lang('Regions/States')</label> 
                                    {!! Form::select('region_state', \App\Models\Contact::REGIONS_STATES,
                                        old('region_state'), ['class' => $errors->has('region_state') ? 'form-select is-invalid' : 'form-select'], ['id' => 'cregion_state']) !!}
                                    {!! $errors->first('region_state', '
                                    <div class="invalid-feedback">:message</div>') !!}
                                </div>
                                 <!-- Recptcha input-->
                                <div class="form-group mb-2">
                                    <div class="g-recaptcha" data-sitekey="6LfZSNUfAAAAAJuknM5Sth-jB_ffE-Zvu7h4goLk"></div>
                                    {!! isset($errors) ? $errors->first('g-recaptcha-response', '
                                                        <div class="invalid-feedback">:message</div>') : '' !!}
                                </div>
                                <div id="cform-result" class="alert alert-success alert-dismissible fade hide" role="alert">                                   
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                        <button class="btn btn-primary btn-md" id="submitContact">
                            {{__('Send') }}
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-md" data-bs-dismiss="modal">
                            @lang('Close')
                        </button>
                </div>               
            </div>
        </div>
    </div>
</div>
<!------------------- Contact Modal End -------------------------->

<!-------------------Video Viewer Start -------------------------->
<div id="videoModal" class="modal fade" data-easein="perspectiveRightIn" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('About E-learning') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <video id="modal-video" width="100%" height="480" controls autoplay muted>
                    <source src="{{ asset('assets/videos/About-Mm-Teacher-Platform_2.mp4') }}" type="video/mp4">
                </video> 
                <div class="modal-footer"> 
                    <a href="{{ route('user-manuals', [1]) }}" class="text-danger">
                        <i class="fas fa-file-pdf fa-lg"></i>&nbsp;
                        <span>{{__("User Guides")}}</span>
                    </a>                      
                    <button type="button" class="btn btn-outline-dark btn-xs" data-bs-dismiss="modal">
                        @lang('Close')
                    </button>
                </div>               
            </div>
        </div>
    </div>
</div>
<!------------------- Video Viewer End -------------------------->

@if($isLearning)
    @php 
        $discussion = $course->allow_discussion ? $course->discussion : null; //dd($discussion);exit;
        $messages = $discussion ? \App\Repositories\DiscussionMessageRepository::getMessagesByDiscussionId($discussion->id) : [];                                      
    @endphp

@if($discussion)
    <!-------------------- Chat Window ------------------------------>
    <div class="floating-chat">
        <i class="fa fa-comments" aria-hidden="true"></i>
        <div class="chat">
            <div class="header">
                <span class="title">
                    @lang('Chat')
                </span>
                <button>
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
                            
            </div>
            <ul id="msg-div" class="messages">     
                @php 
                    $isAllLearnersAllowed = $discussion->allow_learners;
                    $isCourseTakersAllowed = $discussion->allow_takers;                    
                @endphp
                @if(count($messages) > 0)
                    @foreach($messages as $idx => $cm)
                        @if( $cm['user_id'] == auth()->user()->id)                    
                            <li class="">                       
                                <span class="self">
                                    {{ $cm['message'] }}
                                </span>
                            </li>
                        @else 
                            @php 
                            $user =  \App\Repositories\UserRepository::getUserById($cm['user_id']);
                            $otherUserAvatar = $user ? $user->getThumbnailPath() : "/assets/img/avatar.png";             
                            @endphp
                            <li class="">
                                <img class="avatar-img" src="{{ $otherUserAvatar }}" alt="{{ $cm['username'] }}"> 
                                <span class="other">
                                    {{ $cm['message'] }}
                                </span>
                            </li>
                        @endif
                    @endforeach
                @else 
                    {{ __('No discussion for this board yet!') }}
                @endif
            </ul>
            
                <div class="footer">
                    <form id="msg-form" class="form-control" style="padding:0; border-radius:0">
                        <input type="hidden" name="userId" id="uid" value="{{auth()->user()->id}}" />
                        <input type="hidden" name="userName" id="uname" value="{{auth()->user()->username}}" />
                        <input type="hidden" name="discussionId" id="disId" value="{{$discussion->id}}" />
                        <div class="input-group mb-0" style="border-radius: 0; ">               
                            <input type="text" name="message" class="form-control" id="msg-input" 
                                placeholder="@lang('Type here...')" style="width:80%;border-radius:0;"/>
                            <div class="input-group-append" style="border-radius: 0;background-color: #fff;">
                                <button class="input-group-text text-chat text-box" type="submit" 
                                        style="border-radius: 0;background-color: #fff;">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>              
                    </form> 
                </div>
        </div>
    </div>
    <!-------------------- Chat Window Ends------------------------------>
    @endif
@endif 

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- Bootstrap core JS-->
@if(!$isLearning)
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@else 
    <script src="{{ asset('assets/js/page.min.js') }}"></script>  
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
@endif
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

@if($isRoot)
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>      
@endif
<!-- <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script> -->
<!-- Core theme JS--> 
<!-- Scripts -->
@section('script')
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/page.min.js') }}"></script> -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
@if (config('cms.sharing_enabled'))

@endif
@show

<script>
    var isRoot = {!! json_encode($isRoot, JSON_HEX_TAG) !!};
    var isElearning = @json($isLearning); 
    var isLogged = @json($isLogged);
                       
    function hideVideoBar() {
        var vid = document.getElementById("i-video");
        vid.pause();
        vid.currentTime = 0;
        $('#video-footer').hide(); //small video
    }
    
    $(document).ready(function() {
        initializeTabs();
        $('.tooltip-info').tooltip();
       // $("#user-dropdown").click();

       if(isElearning && isLogged) { 
            var authUserId = isLogged;
            var element = $('.floating-chat');       
            const messageForm = document.getElementById("msg-form");
            
            const messageInput = document.getElementById("msg-input");
            const discussionId = document.getElementById("disId");
            const userid = document.getElementById("uid");
            const userName = document.getElementById("uname");
            var messagesElement = document.getElementById("msg-div");
            var appUrl = {!! json_encode(env('APP_URL')) !!};
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
                            url: appUrl+'/api/add-message-chat-window',
                            data: {                         
                                discussion_id: discussionId.value,
                                message: messageInput.value,
                                user_id: userid.value,
                                username: userName.value,
                            }
                        };
                        axios(options);                
                    });
                    window.Echo.channel('chat')
                        .listen('.message', (e) => { console.log('message added is ', e);                    
                            messageInput.value = '';
                            let tempDate = new Date(e.createdAt);
                            let createdAt = tempDate.getDate()+'-'+tempDate.getMonth()+'-'+tempDate.getFullYear()+' '
                                            +tempDate.getHours()+':'+tempDate.getMinutes();
                                            
                            if(authUserId == e.userId) {
                                    //console.log("no participnats before or my msg view");
                                messagesElement.innerHTML +=
                                    '<li>' + 
                                        '<span class="self">'+
                                            e.message +
                                        '</span>'+
                                    '</li>';
                            } else {
                                    //console.log("have participants before");
                                    messagesElement.innerHTML +=        
                                    '<li>'+ 
                                        '<img class="avatar-img" src="'+e.avatar+'" alt="">' +
                                        '<span class="other">'
                                            + e.message +
                                        '</span>'+
                                    '</li>';
                            }    
                    });
            } 

            setTimeout(function() {
                    element.addClass('enter');
            }, 1000);

            element.click(openElement);
            
            

            function openElement() {
                var messages = element.find('.messages');
                var textInput = element.find('.text-box');
                element.find('>i').hide();
                element.addClass('expand');
                element.find('.chat').addClass('enter');
                var strLength = textInput.val().length * 2;
                textInput.keydown(onMetaAndEnter).prop("disabled", false).focus();
                element.off('click', openElement);
                element.find('.header button').click(closeElement);
                element.find('#sendMessage').click(sendNewMessage);
                messages.scrollTop(messages.prop("scrollHeight"));
            }

            function closeElement() {
                element.find('.chat').removeClass('enter').hide();
                element.find('>i').show();
                element.removeClass('expand');
                element.find('.header button').off('click', closeElement);
                element.find('#sendMessage').off('click', sendNewMessage);
                element.find('.text-box').off('keydown', onMetaAndEnter).prop("disabled", true).blur();
                setTimeout(function() {
                    element.find('.chat').removeClass('enter').show()
                    element.click(openElement);
                }, 500);
            }

            function sendNewMessage() {
                // var userInput = $('.text-box');
                // var newMessage = userInput.html().replace(/\<div\>|\<br.*?\>/ig, '\n').replace(/\<\/div\>/g, '').trim().replace(/\n/g, '<br>');

                // if (!newMessage) return;

                // var messagesContainer = $('.messages');

                // messagesContainer.append([
                //     '<li class="self">',
                //         newMessage,
                //     '</li>'
                // ].join(''));

                // clean out old message
                userInput.html('');
                // focus on input
                userInput.focus();

                messagesContainer.finish().animate({
                    scrollTop: messagesContainer.prop("scrollHeight")
                }, 250);
            }

            function onMetaAndEnter(event) {
                if ((event.metaKey || event.ctrlKey) && event.keyCode == 13) {
                    sendNewMessage();
                }
            }
        }

        if(isRoot) {
            $('#course-slider').slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 3,
                autoplaySpeed: 100,
                centerMode: false,
                infinite: false,
                // the magic
                responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                            slidesToShow: 3,
                            infinite: true
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                            slidesToShow: 2,
                            infinite: true
                    }
                }, 
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        dots: true
                    }
                }, 
                {
                    breakpoint: 300,
                    settings: "unslick" // destroys slick

                }
            ]
        });
        }
        
        $(window).scroll(function (event) {
            event.preventDefault();
            let scrollFromTopDistance = $(window).scrollTop(); 
            if(scrollFromTopDistance >= 80) {             
                $('#navbar-header').addClass('navbar-header-onscroll');
                $('.fe-logo').addClass('fe-logo-onscroll'); 
                $('.fe-logo-text').addClass('d-none');          
                $('.nav-link').addClass('nav-link-onscroll');
            } else {
                $('#navbar-header').removeClass('navbar-header-onscroll');
                $('.fe-logo').removeClass('fe-logo-onscroll');
                $('.fe-logo-text').removeClass('d-none');
                $('.nav-link').removeClass('nav-link-onscroll');
            }
        });
        $(document).on('click', '#contact-menu', function() {
            $('#contactModal').modal('show');
        });
        $('#submitContact').on('click', function(e) { 
            var gresponse = grecaptcha.getResponse();
            console.log('clicked submitContact button', gresponse, ' -- hmm'); 
            let isAllValid = true;
            isAllValid = checkValidationContactForm("#cname");
            isAllValid = checkValidationContactForm("#cemail");
            isAllValid = checkValidationContactForm("#csubject");
            isAllValid = checkValidationContactForm("#cmessage");
    
            if ( !isAllValid ) {
                return false;
            } else {
                $.ajax({
                  type: 'POST',
                  url: '{{ route('contact-us.post') }}',
                  data: {
                      _token: $('input[name=_token]').val(),
                      name: $("#cname").val(),
                      email: $('#cemail').val(),
                      phone_no: $('#cphone_no').val(),
                      organization: $('#corganization').val(),
                      region_state: $('#cregion_state').val(),
                      subject: $('#csubject').val(),
                      message: $('#cmessage').val(),
                      recaptcha_token: 1 //$('#g-recaptcha-response-1').val()
                  },
                  success: function(data) { console.log('success data after reveiwing ',data);
                    restoreTheContactFormValidations("#cname");
                    restoreTheContactFormValidations("#cemail");
                    restoreTheContactFormValidations("#csubject");
                    restoreTheContactFormValidations("#cmessage");
                    $('#cform-result').removeClass('hide');
                    $('#cform-result').addClass('show');
                    $('#cform-result').html(data.data);
                  },
                  error: function(data) {
                    $('#cform-result').removeClass('alert-success alert-dismissible fade hide');
                    $('#cform-result').addClass('alert-danger alert-dismissible fade show');
                    $('#cform-result').html(data.data); 
                  }
                });
            }
            
        });

        $('#i-video').on('click', function(e) {
            e.preventDefault(); //alert('clicked');
            $('#videoModal').modal('show');
            var vid = document.getElementById("i-video");
           // $('#video-footer').hide(); //small video
            vid.pause();
            vid.currentTime = 0;
        });
        
        $("#videoModal").on("hidden.bs.modal", function () { //close the video in the modal upon modal close
            var v = document.getElementById("modal-video");
            v.pause();
            v.currentTime = 0;
        });

       //enableAutoplay();
      
    });

    function enableAutoplay() {  // from w3c but not working
        setTimeout(function () { // can remove muted but infrindge with Chrome's policy
            var vid = document.getElementById("i-video");
            vid.muted = false;
        }, 18000);        
       // video.muted = false;
    }
    
    function openNav() {
        document.getElementById("course-side-bar").style.width = "25%";
        document.getElementById("course-main-content").style.marginLeft = "25%";
    }

    function closeNav() {
        document.getElementById("course-side-bar").style.width = "0";
        document.getElementById("course-main-content").style.marginLeft= "0";
    }

    function checkValidationContactForm(idName) {
        if (!$(idName).val())  {
            $(idName).addClass('is-invalid');
            $(idName+"-invalid").removeClass('d-none');
            return false;
        }
        return true;
    }

    function restoreTheContactFormValidations(idName) {
        $(idName).removeClass('is-invalid');
        $(idName+"-invalid").addClass('d-none');
    }

    function colorSwitcher(themeClass) {
        $("#app-root").removeClass(); //remove All
        $('#app-root').addClass(themeClass);
        $("#color-switcher a").removeClass('selected');  
        $("."+themeClass).addClass('selected');
        if(themeClass === 'cousera') {
            $('#copyright-footer').toggleClass(themeClass);
        } else { //console.log('it is not coursera');
            //if($('#copyright-footer').hasClass(themeClass)) {
                $('#copyright-footer').removeClass(themeClass); //console.log('removed ')
           // }
        }
    }

    function initializeTabs() { 
        var tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 1; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
    }
    
    function openVerticalTab(evt, tabName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the link that opened the tab
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    
    let backToTopBtn = document.getElementById("btn-back-to-top");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
        ) {
            backToTopBtn.style.display = "block";
        } else {
            backToTopBtn.style.display = "none";
        }
    }
    // When the user clicks on the button, scroll to the top of the document
    backToTopBtn.addEventListener("click", backToTop); 

    function backToTop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>