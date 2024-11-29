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
                @include('frontend.layouts.form_alert')
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
                <div class="row">
                    <div class="col-12 col-lg-1"></div>
                    <div class="col-12 col-lg-10 course-content-area">
                            <div class ="row mb-4">
                                <div class="col-12 col-md-6">
                                    <h5 class="mt-2">
                                        @lang('Please answer all the questions')
                                    </h5>
                                </div>
                                <div class="col-12 col-md-6">
                                    <span class="tooltip-info form-check-label" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Overall Progress Percentage. The calculation is based 
                                            on the completeness of lectures, quizzes and assessments!')">
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
                                @if (isset($post) && isset($post->id))
                                    {!! \Form::open(array('method' => 'PUT', 'route' => array('courses.update-assessment', $post->id) )) !!}
                                @else
                                    {!! \Form::open(array('method' => 'POST', 'route' => 'courses.create-assessment' )) !!}
                                @endif
                                <input type="hidden" name="assessmentQA_id" value="{{$assessment->id}}" />
                                <input type="hidden" name="course_id" value="{{$course->id}}" />
                                <div class ="row"> 
                                    <div class="col-12">
                                       <div class="form-group form-eva-align px-3 pb-3 pt-3 pe-4">
                                            <label for="question".{{$assessment->id}} class="d-block"> 
                                                {{-- $assessment->order --}} &nbsp; 
                                                {!! $assessment->question !!}
                                                @if ( $assessment->type == 'long_answer' )
                                                    <small class="text-warning lead">
                                                    @if(isset($post->answers[0]))
                                                        @if( $assessment->passing_option == 'after_providing_answer' ) 
                                                            @lang('Submitted and passed')
                                                        @elseif( $assessment->passing_option == 'after_sending_feedback' ) 
                                                            @if($post && $post->comment) 
                                                                <p class="text-dark">
                                                                    @lang('Feedback from the course owner: ')
                                                                    <span class="text-primary fst-italic">"{{ $post->comment }}"</span>
                                                                </p>
                                                            @else 
                                                                @lang('Waiting for the feedback from the course owner')  
                                                            @endif
                                                            
                                                        @else 
                                                            @if($post && $post->pass_option == 'pass') 
                                                                <div>
                                                                    <p class="text-dark">@lang('Feedback from the course owner: ')
                                                                        <span class="text-primary fst-italic">"{{ $post->comment }}"</span>
                                                                    </p>
                                                                </div>
                                                            @elseif($post && $post->pass_option == 'retake') 
                                                                <div>
                                                                    <p class="text-dark">@lang('Feedback from the course owner: ')
                                                                        <span class="text-primary fst-italic">"{{ $post->comment }}"</span>
                                                                    </p>
                                                                </div>
                                                            @else 
                                                                <i class="fa fa-info-circle"></i>&nbsp;
                                                                @lang('Waiting for the feedback from the course owner and the answer is marked as satisfactory')  
                                                            @endif  
                                                        @endif
                                                    @else 
                                                        @if( $assessment->passing_option == 'after_providing_answer' )
                                                            @lang('Hint: ')&nbsp;@lang('This section will be passed after providing the answer.')
                                                        @elseif( $assessment->passing_option == 'after_sending_feedback' )
                                                            @lang('Hint: ')&nbsp;@lang('This section will be passed after the course owner provides feedback.')
                                                        @else 
                                                            @lang('Hint: ')&nbsp;@lang('This section will be passed after the course owner provides feedback and allow to proceed.')
                                                        @endif
                                                    @endif
                                                    </small>
                                                @else 
                                                    <small class="text-primary">
                                                        {{__('Hint: Number of right answers for this question:') }}&nbsp;{{ count($assessment->right_answers) }}
                                                    </small>
                                                @endif
                                            </label><br/>
                                            @php $idToPass = auth()->user()->id.$assessment->id; @endphp
                                            @if ( $assessment->type == 'multiple_choice' ) 
                                                @php 
                                                    $alphabets = ['A','B','C','D','E','F','G','H','I','J'];                                                                                           
                                                @endphp
                                                @for ( $i = 0; $i < sizeOf($assessment->answers); $i++ )
                                                    @php 
                                                        $nameA = "answers[$i]";  
                                                                                                                                                                                                              
                                                    @endphp
                                                    @if( !empty($assessment->answers[$i]) )
                                                        <div class="form-check form-check-block">                                                 
                                                            <input type="checkbox" name="answers[{{$i}}]"
                                                                value="{{ $alphabets[$i] }}" class="mt-1 mr-2" id="answer_{{$i}}"
                                                                {{ old( $nameA, ( isset($post->answers) && in_array($alphabets[$i],$post->answers) )  ? 'checked' : '' ) }}>
                                                            <label class="d-inline" for="answer_{{$i}}"> 
                                                                {!! strip_tags($assessment->answers[$i]) !!}
                                                            </label> {{-- $alphabets[$i] --}}
                                                        </div>
                                                        
                                                    @endif
                                                @endfor  
                                                
                                                <input type="hidden" name="assessment_type" value="multiple_choice" />
                                            @elseif ( $assessment->type == 'true_false' )  
                                                <div class="form-check form-check-block">
                                                    @php 
                                                        $nameA = "answers[0]";                                                                                                                                                        
                                                    @endphp                                                 
                                                    <input type="radio" name="answers[0]"
                                                            value="true" class="mt-1 mr-2" id="answer_true"
                                                            {{ old( $nameA, isset($post->answers[0]) && 
                                                                ($post->answers[0] == true || $post->answers[0] == "true")  ? 
                                                                'checked' : '' ) }}>&nbsp;&nbsp;
                                                    <label for="answer_true"> {{ __('True') }}</label> 
                                                    <input type="radio" name="answers[0]"
                                                            value="false" class="mt-1 mr-2" id="answer_false"
                                                            {{ old( $nameA, isset($post->answers[0]) && 
                                                                ($post->answers[0] == false || $post->answers[0] == "false" ) ? 'checked' : '' ) }}>&nbsp;&nbsp;
                                                    <label for="answer_false"> {{ __('False') }}</label>
                                                    <input type="radio" name="answers[0]"
                                                            value="none" class="mt-1 mr-2" id="answer_none"
                                                            {{ old( $nameA, isset($post->answers[0]) && 
                                                                $post->answers[0] == 'none'  ? 'checked' : '' ) }}>&nbsp;&nbsp;
                                                    <label for="answer_false"> {{ __('None of the above') }}</label>
                                                </div> 
                                                     
                                                <input type="hidden" name="assessment_type" value="true_false" />          
                                            @elseif ( $assessment->type == 'rearrange' )  
                                                @php 
                                                    $sorted =   $assessment->answers; 
                                                    sort($sorted);                                                                                      
                                                @endphp
                                                @for ( $i = 0; $i < sizeof($assessment->answers); $i++ )
                                                    @php 
                                                        $nameA = "answers[$i]";                                                                                                                                                        
                                                    @endphp
                                                    @if( !empty($assessment->answers[$i]) )
                                                        <div class="form-check form-check-block"> 
                                                        <select class="form-select" id="answer_{{$i}}" name="answers[{{$i}}]" 
                                                                aria-label="select">
                                                            @foreach($sorted as $key => $value)
                                                                <option value="{{strip_tags($value)}}" {{ isset($post->answers[$i]) && 
                                                                        $post->answers[$i] == strip_tags($value)  ? 'selected' : ''}}>
                                                                    {{ strip_tags($value) }}
                                                                </option>
                                                            @endforeach
                                                        </select>                                               
                                                        </div>
                                                    @endif
                                                @endfor 
                                                
                                                <input type="hidden" name="assessment_type" value="rearrange" /> 
                                            @elseif ( $assessment->type == 'matching' )
                                                @php 
                                                    $rightSorted =   $assessment->right_answers; 
                                                    sort($rightSorted);                                                                                      
                                                @endphp
                                                @for ( $i = 0; $i < sizeof($assessment->answers); $i++ )
                                                    @php 
                                                        $nameA = "answers[$i]";                                                                                                                                                        
                                                    @endphp
                                                    @if( !empty($assessment->answers[$i]) )
                                                        <div class="form-check-block"> 
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    
                                                                    <div>{!! $assessment->answers[$i] !!} </div>
                                                                    
                                                                </div>
                                                                <div class="col-6">
                                                                <select class="form-select" id="answer_{{$i}}" name="answers[{{$i}}]" 
                                                                    aria-label="select"> 
                                                                    @foreach($rightSorted as $key => $value)
                                                                        <option value="{{strip_tags($value)}}" {{ isset($post->answers[$i]) && 
                                                                                $post->answers[$i] == strip_tags($value)  ? 'selected' : ''}}
                                                                                title="{!! $value !!}">
                                                                                {!! str_limit( strip_tags($value) ,70, '...') !!} 
                                                                        </option>
                                                                    @endforeach
                                                            </select> 
                                                                </div>
                                                            </div>                                                                                                 
                                                        </div>
                                                    @endif
                                                @endfor 
                                                {{-- <div class="row mt-3"> --}}
                                                    {{--@if($agent->isMobile())
                                                        @include('frontend.courses.partials.assessment-matching-mobile')   
                                                    @else
                                                        @include('frontend.courses.partials.assessment-matching-non-mobile')
                                                    @endif --}}                                                   
                                                {{-- </div> --}}
                                                
                                                <input type="hidden" name="assessment_type" value="matching" /> 
                                            @elseif ( $assessment->type == 'long_answer' )
                                                @if($post && $post->answers[0]) 
                                                   <p class="text-dark">@lang('Your Submitted Answer'):</p>                         
                                                @endif
                                                <textarea name="answers[]" id="longans-assess" rows="10" class="form-control" 
                                                    placeholder="@lang('Write down you answer')" 
                                                    {{isset($post->answers[0]) && $post->pass_option !== 'retake' ? 'readonly' : ''}}
                                                >{{ isset($post->answers[0]) ? $post->answers[0] : ''}}</textarea> 
                                                {{-- <p class="text-primary mt-2">
                                                    @lang('Answer should have at least 100 words')
                                                </p>    --}}
                                                <input type="hidden" name="assessment_type" value="long_answer" />                    
                                            @endif
                                            @if($errors->any())
                                                @foreach ($errors->all() as $error)
                                                    <div class="required text-danger">{{$error}}</div>
                                                @endforeach
                                            @endif 
                                            @if ( \Session::has('error') )                                    
                                                <p class="text-primary mt-3">                                       
                                                    @if(isset($post->attempts) && $post->attempts >= 1 && (int)$post->overall_score < $course->acceptable_score_for_assessment) 
                                                        &nbsp;&nbsp;                                                       
                                                        <a class="btn btn-primary btn-sm" onclick="showRightAnswer({{$idToPass}})">
                                                            @lang('Show Right Answers')
                                                        </a>          
                                                    @endif
                                                </p>
                                                <div id="{{auth()->user()->id}}{{$assessment->id}}" class="d-none">
                                                    <h6 class="mt-5 mb-3 text-danger">
                                                        @if($assessment->type == 'long_answer')
                                                            {{__('The suggested answer is :')}}
                                                        @else 
                                                            {{__('The right answers are :')}}
                                                        @endif
                                                    </h6>
                                                    @foreach($allQandA as $key=>$qa)
                                                        <p>
                                                            {{-- {{$key+1}}.&nbsp; --}}
                                                            {{ strip_tags($qa->question) }}&nbsp;-&nbsp;
                                                                <!-- @if($qa->type == 'multiple_choice')
                                                                    @foreach($qa->right_answers as $idx=>$ra)
                                                                        {{$ra}}&nbsp;
                                                                    @endforeach
                                                                @endif
                                                                @if($qa->type == 'true_false')
                                                                    @foreach($qa->right_answers as $idx=>$ra)
                                                                        {{$ra}}&nbsp;
                                                                    @endforeach
                                                                @endif
                                                                @if($qa->type == 'rearrange')
                                                                    @foreach($qa->right_answers as $idx=>$ra)
                                                                        {{$ra}}&nbsp;
                                                                    @endforeach
                                                                @endif
                                                                @if($qa->type == 'matching')
                                                                    @foreach($qa->right_answers as $idx=>$ra)
                                                                        {{$ra}}&nbsp;
                                                                    @endforeach
                                                                @endif   -->
                                                                @foreach($qa->right_answers as $idx=>$ra)
                                                                    {{ strip_tags($ra) }}&nbsp;
                                                                @endforeach
                                                                <!-- @if($qa->type == 'long_answer')
                                                                                                                        
                                                                @endif                                                -->
                                                        </p>
                                                    @endforeach
                                                </div> 
                                            @endif
                                        </div>  
                                                                           
                                    </div>                                
                                </div>                    
                                <div class="text-right mr-5 mt-3">
                                    @php                                    
                                      $assessmentSubmitted = (isset($post) && $post->status == 2) ? true : false;                                    
                                      $evaluationLink =   $assessmentSubmitted ? \App\Repositories\CourseRepository::getEvaluationRoute($course) : null;                                
                                    @endphp
                                    
                                    @if($assessmentSubmitted && $percentage == 100)
                                        <a href="{{ isset($previousSection) ? $previousSection : '#'  }}"                                         
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                            &lt;&nbsp;@lang('Previous') 
                                         </a>
                                        @if($isLastQuestion)  
                                            <a href="{{ $evaluationLink ? $evaluationLink: '#' }}"
                                                class="btn btn-outline-primary btn-sm p-2 mb-2 {{ $evaluationLink ? '' : 'disabled' }}">
                                                @lang('Next')&nbsp;>
                                            </a>
                                        @else
                                            <a href="{{ isset($nextSection) ? $nextSection: '#' }}"                                            
                                                class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) ? '' : 'disabled' }}">
                                                @lang('Next')&nbsp;>
                                            </a>  
                                        @endif
                                    @else
                                        <button value="{{ isset($previousSection) ? $previousSection : '#'  }}"
                                            type="submit" name="submit_assess" id="submit-assess-prev"
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($previousSection) ? '' : 'disabled' }}">
                                            &lt;&nbsp;@lang('Previous') 
                                        </button>
                                        <button value="{{ isset($nextSection) ? $nextSection: '#' }}"
                                            type="submit" name="submit_assess" id="submit-assess-next"
                                            class="btn btn-outline-primary btn-sm p-2 mb-2 {{ isset($nextSection) ? '' : 'disabled' }}">
                                            @lang('Next')&nbsp;>
                                        </button>                                       
                                    @endif
                                    
                                    @if($isSubmitted && $gotAcceptableScore) 
                                        <button class="btn btn-primary disabled btn-sm p-2 mb-2" 
                                            >@lang('Submitted')&nbsp;<i class="fas fa-check"></i>
                                        </button>
                                    @else
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                        <!-- <button name="submit_assess" value="1" class="btn btn-primary btn-sm p-2 mb-2" 
                                            type="submit">@lang('Save')
                                        </button> -->
                                        @if($isSubmitted) {{-- for not having acceptable score --}}                                    
                                            <button name="submit_assess" value="2" class="btn btn-primary btn-sm p-2 mb-2" 
                                                type="submit">@lang('Submit')
                                            </button>                                                          
                                        @else
                                            @if($isLastQuestion && $isReadyToSubmit) 
                                                <button name="submit_assess" value="2" class="btn btn-primary btn-sm p-2 mb-2" 
                                                    type="submit">@lang('Submit')
                                                </button>  
                                            @else 
                                                <button class="btn btn-primary btn-sm p-2 mb-2 disabled" 
                                                    type="submit">@lang('Submit')
                                                </button>                                       
                                            @endif
                                        @endif                                       
                                    @endif
                                                    
                                </div>
                            </form>                              
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
    <script type="text/javascript">
        //var boxElements = document.getElementsByClassName('job-emp');
       
        // for (var i = 0; i < boxElements.length; i++) {
        //    boxElements[i].addEventListener('touchend', function(e) { // to support double click
              
        //            e.preventDefault();  
        //            let text = $(this).find('.emp2').text();     
        //            if(this.children.length > 0) {
        //                $(this).find('.emp2').removeClass('ui-draggable-dragging');
        //                $(this).find('.emp2').removeClass('dragged');
        //                $(this).find('.emp2').removeClass('dropped');
        //                $(this).find('.emp2').css({"top": ""});
        //                $(this).find('.emp2').css({"left": ""});
                           
        //                if(text.indexOf(String.fromCharCode(10060)) >= 0 ) {
        //                    $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10060), "") ); 
        //                } else {
        //                    $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10004), "") ); 
        //                } 
        //                var emptyCol4 = null;
        //                var self = this;
                       
        //                var emptyChildren = $(".drag-content-2").children("div.col-4").first(function () {
        //                    return !($(this).text());
        //                }); //.find("div.col-4").filter(":first"); 
        //                //console.log(emptyChildren); 
        //                emptyChildren.append(this.children); // replace to col-4 instead of parent class
        //                //$('.drag-content-2').append(this.children);
        //            }
        //       // } 
        //        //lastTap = currentTime;
        //    });
        // }
       
        // changing mouse events into touch events
        function touchHandler(event) {
           var touch = event.changedTouches[0];

           var simulatedEvent = document.createEvent("MouseEvent");
               simulatedEvent.initMouseEvent({
                   touchstart: "mousedown",
                   touchmove: "mousemove",
                   touchend: "mouseup"
               }[event.type], true, true, window, 1,
                   touch.screenX, touch.screenY,
                   touch.clientX, touch.clientY, false,
                   false, false, false, 0, null);

           touch.target.dispatchEvent(simulatedEvent);
       }

       function init() {
           document.addEventListener("touchstart", touchHandler, true);
           document.addEventListener("touchmove", touchHandler, true);
           document.addEventListener("touchend", touchHandler, true);
          // document.addEventListener("touchcancel", touchHandler, true);
       }
      init(); 

        function checkAllBlanksAreFilled() {
            let allHasChildren = true;
            $('.job-emp').each(function(i, obj) {
                if(obj.children && obj.children.length > 0 ) {
                    
                } else {
                    allHasChildren = false;
                       // console.log('empty');
                }
            });
            // if(allHasChildren) { 
            //     $('#check-answer').prop('disabled', false);
            // }             
        }
        $('.emp2').draggable({
            revert: "invalid", 
            containment: "window",
            cursor: "move",
            start: function(event, ui) {
                $(this).addClass('dragged');
            },
            stop: function(event, ui) {
                    $(this).removeClass('dragged');
            }
        });

        $('.job-emp').droppable({
            accept: '.emp2',
            activeClass: 'active',
            drop: function(event, ui) {              
                    ui.draggable.addClass('dropped');
                    ui.draggable.detach().appendTo($(this));
                    checkAllBlanksAreFilled();
                    // inserting the value to hidden inputs
                    let text = $(this).find('.emp2').text();
                    text = $.trim(text);
                    let idString = $(this).attr('id');   
                    let id =  idString.split("_");
                   // console.log(text, id[1]);//match_answers_
                    let hiddenInput = '#match_answers_'+id[1];
                    $(hiddenInput).val(text);
            }
        });

        $('.job-emp').dblclick(function() { //alert('hi');
            console.log(this.children);
            let text = $(this).find('.emp2').text();            
            if(this.children.length > 0) {
                $(this).find('.emp2').removeClass('ui-draggable-dragging');
                $(this).find('.emp2').removeClass('dragged');
                $(this).find('.emp2').removeClass('dropped');
                $(this).find('.emp2').css({"top": ""});
                $(this).find('.emp2').css({"left": ""}); 
                    
                if(text.indexOf(String.fromCharCode(10060)) >= 0 ) {
                    $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10060), "") ); 
                 } else {
                    $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10004), "") ); 
                } 
                var emptyCol4 = null;
                var self = this;
                
                var emptyChildren = $(".drag-content-2").children("div.col-4").first(function () {
                    return !($(this).text());
                }); //.find("div.col-4").filter(":first"); 
                console.log(emptyChildren); 
                emptyChildren.append(this.children); // replace to col-4 instead of parent class
                //$('.drag-content-2').append(this.children);
            }
        });
        
       // $(document).ready(function () {
            
           
            $('#longans-assess' ).keyup(function () { //console.log('keyup  ', quiz.questions[i].id);
               // let counter = checkAllInput();  //console.log("counter ", countWords('#lanswer' + quiz.questions[i].id));  
                let wordCount = countWords('#longans-assess');
                    //if (counter === quiz.questions.length) {
                if(wordCount > 100 ) {
                    // $('#submit-assess-prev').removeClass('d-none');
                    // $('#submit-assess-next').removeClass('d-none');
                } else {
                    // $('#submit-assess-prev').addClass('d-none');
                    // $('#submit-assess-next').addClass('d-none');
                }
            });

        //});
        
        function countWords(id) {
            var text = $(id).val();        
            var numWords = 0;          
            for (var i = 0; i < text.length; i++) {
                var currentCharacter = text[i];                 
                if (currentCharacter == " ") {
                        numWords += 1;
                }
                }              
                numWords += 1;
                return numWords;
        }

        function showRightAnswer(id) {
            event.preventDefault();
            $('#'+id).removeClass('d-none');
        }

    </script>
@endsection
