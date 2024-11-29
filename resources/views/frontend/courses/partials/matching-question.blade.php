<!-- <form action="#"> -->
    @foreach($currentQuiz->questions as $key => $question)
        <div>
            <div class="row mt-5">
                <div class="col-md-12">
                    {!! $question->title !!}
                </div>
            </div>
            <div class="row mt-3">
                @if(isset($questionMedia[$key]) && ($question->id == $questionMedia[$key]->model_id))
                    <div class="col-12">
                        <p class="mt-2 mb-2">
                            <img src="{{ asset($questionMedia[$key]->getUrl()) }}" />
                        </p>
                    </div>
                @endif
                
                @if($arr = $question->matching_answer->answer)
                    {{-- this one supports d&d with matching
                        @if($agent->isMobile())
                        @include('frontend.courses.partials.matching-mobile')   
                    @else
                        @include('frontend.courses.partials.matching-non-mobile')
                    @endif   --}}
                    @foreach($question->matching_answer->answer as $key => $answer)
                    <div class="col-md-6 mt-3">
                        <div>
                            {!! $answer['first'] !!}
                        </div>
                    </div>
                    @php
                       // shuffle($arr);
                    @endphp
                    <div class="col-md-6 mt-3">
                        <select name="question{{$question->id}}" required id="answer{{$question->id . $key}}" 
                        class="form-select required match-items">
                            <option value="" class="match-item">@lang('Select Answer')</option>
                            @php 
                                shuffle($arr);                                   
                            @endphp
                            @foreach($arr as $matchAnswer)
                                
                                    <option value="{{$matchAnswer['second']}}" class="match-item" title="{!! strip_tags($matchAnswer['second']) !!}">
                                        
                                        {!! str_limit( strip_tags($matchAnswer['second']) ,70, '...') !!} 
                                    </option>
                                
                            @endforeach
                        </select>
                        <span id="{{ $key . $question->id }}" class="mt-3"></span>
                    </div>
                @endforeach
                @endif
            </div>
            <div class="row mt-2 ml-1">
                <span id="question{{$question->id}}"></span>
            </div>
            <div class="row mt-2 ml-1">
                <span id="description{{$question->id}}"></span>
            </div>
        </div>
    @endforeach
    <div class="row mt-5">
        <input type="submit" class="btn btn-primary btn-sm" id="check-answer" 
            onclick="checkAnswer({{$currentQuiz}})" value="{{ __('Check Answer') }}" disabled="true">
    </div>
<!-- </form> -->

@section('script')
    @parent
    <script>
       // let quiz = @json($currentQuiz);
      //  let questions = quiz['questions']; //$question->matching_answer->answer
        $(document).on('change', '.match-items', function(e){
            check_all_selected();
        })
        function check_aswer_all_question() {
            let answerAllQuestion = true;
            document.querySelectorAll('.match-items').forEach(function(item, index){
                if(item.value == ''){
                    answerAllQuestion = false;
                }
            })
            return answerAllQuestion;
        }
        function check_all_selected() {
            //let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
            if (check_aswer_all_question()) {
                $('#check-answer').prop('disabled', false);
            } else {
                $('#check-answer').prop('disabled', true);
               // $('#'+markComId).prop('disabled',true);
               $('.quiz-mark-complete').prop('disabled',true);
               $('.quiz-mark-complete-next').prop('disabled',true);
            }
        }
        
        $(document).ready(function () {
            
            // for (let i = 0; i < quiz.questions.length; i++) {
            //     for (let j = 0, answer = Object.keys(quiz.questions[i].matching_answer.answer); j < answer.length; j++) {
            //         let selector = '#answer' + quiz.questions[i].id + answer[j];
            //         $(selector).on('change', function () {
            //             let checkInput = checkAllInput();
            //             console.log(checkInput);
            //             if (counter === quiz.questions.length) {
            //                 $('#check-answer').prop('disabled', false);
            //             } else {
            //                 $('#check-answer').prop('disabled', true);
            //             }
            //         });
            //     }
            // }
            // function checkAllInput() {
            //     for (let i = 0; i < quiz.questions.length; i++) {
            //         for (let j = 0, answer = Object.keys(quiz.questions[i].matching_answer.answer); j < answer.length; j++) {
            //             let selector = '#answer' + quiz.questions[i].id + answer[j];
            //             console.log($(selector).val() === '');
            //             // if($(selector).val() !== undefined || $(selector).val() !== '') {
            //             //
            //             // }
            //         }
            //     }
            // }
        });
        var translations = {
                            rightAnswerText: "@lang('The right answer is')",
                            right: "@lang('Your answer is correct!')",
                            wrong: "@lang('Your answer is wrong!')",                          
            };
        function checkAnswer(quiz) {
            event.preventDefault();
            if(check_aswer_all_question()){
                let url = "{{ route('quiz.check-answer') }}";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {
                        quiz: quiz.id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        //let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
                        for (let i = 0; i < response.question.length; i++) {
                            let keys = Object.keys(response.question[i].matching_answer.answer);
                            for (let j = 0; j < keys.length; j++) {
                                let element = '#' + keys[j] + response.question[i].id;
                                let answerElement = '#answer' + response.question[i].id + keys[j];
                                if(response.question[i].matching_answer.answer[keys[j]].second === $(answerElement).val()) {
                                    $(element).css('color', 'darkcyan');
                                    $(element).html(translations.right); // + response.question[i].matching_answer.answer[keys[j]].second);
                                } else {
                                    $(element).css('color', 'darkred');
                                    $(element).html(translations.wrong + translations.rightAnswerText + ' '+response.question[i].matching_answer.answer[keys[j]].second);
                                }
                                //$(element).html('The right answer is ' + response.question[i].matching_answer.answer[keys[j]].second);
                            }
                            $('#description' + response.question[i].id).html(response.question[i].description);
                        }
                        //$('#'+markComId).prop('disabled',false);
                        $('.quiz-mark-complete').prop('disabled',false);
                        $('.quiz-mark-complete-next').prop('disabled',false);
                    }
                });
            }
        }
       // console.log(questions);
    //     $(document).on('change', '.match-items', function(e) {
    //         check_all_selected();
    //     })
    //     $('#check-answer-matching').prop('disabled', true);
    //     $('.quiz-mark-complete').prop('disabled',true);
    //     $('.quiz-mark-complete-next').prop('disabled',true);

    //     /* support for mobile */
    //     // find the element that you want to drag.
    //     var boxElements = document.getElementsByClassName('job-emp');
       
    //     for (var i = 0; i < boxElements.length; i++) {
    //         boxElements[i].addEventListener('touchend', function(e) { // to support double click
               
    //                 e.preventDefault();  
    //                 let text = $(this).find('.emp2').text();     
    //                 if(this.children.length > 0) {
    //                     $(this).find('.emp2').removeClass('ui-draggable-dragging');
    //                     $(this).find('.emp2').removeClass('dragged');
    //                     $(this).find('.emp2').removeClass('dropped');
    //                     $(this).find('.emp2').css({"top": ""});
    //                     $(this).find('.emp2').css({"left": ""});
                            
    //                     if(text.indexOf(String.fromCharCode(10060)) >= 0 ) {
    //                         $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10060), "") ); 
    //                     } else {
    //                         $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10004), "") ); 
    //                     } 
    //                     var emptyCol4 = null;
    //                     var self = this;
                        
    //                     var emptyChildren = $(".drag-content-2").children("div.col-4").first(function () {
    //                         return !($(this).text());
    //                     }); //.find("div.col-4").filter(":first"); 
    //                     //console.log(emptyChildren); 
    //                     emptyChildren.append(this.children); // replace to col-4 instead of parent class
    //                     //$('.drag-content-2').append(this.children);
    //                 }
    //            // } 
    //             //lastTap = currentTime;
    //         });
    //     }
        
    //     // changing mouse events into touch events
    //     function touchHandler(event) {
    //         var touch = event.changedTouches[0];

    //         var simulatedEvent = document.createEvent("MouseEvent");
    //             simulatedEvent.initMouseEvent({
    //                 touchstart: "mousedown",
    //                 touchmove: "mousemove",
    //                 touchend: "mouseup"
    //             }[event.type], true, true, window, 1,
    //                 touch.screenX, touch.screenY,
    //                 touch.clientX, touch.clientY, false,
    //                 false, false, false, 0, null);

    //         touch.target.dispatchEvent(simulatedEvent);
    //     }

    //     function init() {
    //         document.addEventListener("touchstart", touchHandler, true);
    //         document.addEventListener("touchmove", touchHandler, true);
    //         document.addEventListener("touchend", touchHandler, true);
    //        // document.addEventListener("touchcancel", touchHandler, true);
    //     }
    //    init(); 
    //     /* end of support for mobile */
       
    //     $('.emp2').draggable({
    //             revert: "invalid", 
    //             containment: "window",
    //             cursor: "move",
    //             start: function(event, ui) {
    //                 $(this).addClass('dragged');
    //             },
    //             stop: function(event, ui) {
    //                 $(this).removeClass('dragged');
    //             }
    //     });

    //     $('.job-emp').droppable({
    //             accept: '.emp2',
    //             activeClass: 'active',
    //             drop: function(event, ui) {
    //                 ui.draggable.addClass('dropped');
    //                 ui.draggable.detach().appendTo($(this));
    //                 checkAllBlanksAreFilled();
    //             }
    //     });

    //     $('.job-emp').dblclick(function() { 
    //        // console.log(this.children);
    //         let text = $(this).find('.emp2').text();            
    //         if(this.children.length > 0) {
    //             $(this).find('.emp2').removeClass('ui-draggable-dragging');
    //             $(this).find('.emp2').removeClass('dragged');
    //             $(this).find('.emp2').removeClass('dropped');
    //             $(this).find('.emp2').css({"top": ""});
    //             $(this).find('.emp2').css({"left": ""});
                    
    //             if(text.indexOf(String.fromCharCode(10060)) >= 0 ) {
    //                 $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10060), "") ); 
    //              } else {
    //                 $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10004), "") ); 
    //             } 
    //             var emptyCol4 = null;
    //             var self = this;
                
    //             var emptyChildren = $(".drag-content-2").children("div.col-4").first(function () {
    //                 return !($(this).text());
    //             }); //.find("div.col-4").filter(":first"); 
    //             //console.log(emptyChildren); 
    //             emptyChildren.append(this.children); // replace to col-4 instead of parent class
    //             //$('.drag-content-2').append(this.children);
    //         }
    //     });
    //     $('.job-name').each(function(i, obj) {
    //         //console.log(obj);
    //     });
        
    //     function checkAnswers() {   
    //         let answersOnly = [];
    //         let matchingAnswers = questions
    //         for(let i=0; i<questions.length; i++) {
    //            //let temp = [];
    //             for(let j=0; j< questions[i].matching_answer.answer.length; j++) {
    //               // console.log(questions[i].matching_answer.answer[j]);
    //                 for (const key in questions[i].matching_answer.answer[j]) {
    //                    //console.log(`${key}: ${blankAnswers[i].paragraph[j][key]}`);
    //                    if(key === 'second') {
    //                        answersOnly.push(questions[i].matching_answer.answer[j][key]);
    //                    }
    //                 } 
    //                //temp.push(blankAnswers[i].paragraph[j])
    //             }
    //            // answersOnly.push(temp);
    //         }
    //         // console.log(answersOnly);
    //         var allAreCorrect = true;
    //         $('.job-emp').each(function(i, obj) {
    //            if(obj.children && obj.children.length > 0 ) {
    //            // console.log(obj.children[0].outerText);
    //             //    let userDropValue = obj.children[0].outerText;
    //             //    if(userDropValue != answersOnly[i]) {
    //             //        allAreCorrect = false;                  
    //             //        obj.children[0].append(' '+String.fromCharCode(10060));
    //             //    } else {                   
    //             //        obj.children[0].append(' '+String.fromCharCode(10004));
    //             //    }
    //             let userDropValue = obj.children[0].outerText;
    //                 if(obj.children[0].outerText.indexOf(String.fromCharCode(10060)) >= 0 ) {                     
    //                     $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10060), "") ); 
    //                     userDropValue = userDropValue.replace(String.fromCharCode(10060), '');
    //                 } 
    //                 if(obj.children[0].outerText.indexOf(String.fromCharCode(10004)) >= 0 ) {                       
    //                     $(this).find('.emp2').html( $(this).find('.emp2').html().replace(String.fromCharCode(10004), "") );
    //                     userDropValue = userDropValue.replace(String.fromCharCode(10004), ''); 
    //                 }                                  
    //                 console.log(userDropValue);
                    
    //                 if($.trim(userDropValue) != $.trim(answersOnly[i])) {
    //                     allAreCorrect = false;                  
    //                     obj.children[0].append(' '+String.fromCharCode(10060));
    //                 } else {                   
    //                     obj.children[0].append(' '+String.fromCharCode(10004));
    //                 }
    //            } else {
    //                allAreCorrect = false;             
    //            }
    //         });
    //         //console.log("all are correct ", allAreCorrect);
    //         if(allAreCorrect) {
    //             $('.quiz-mark-complete').prop('disabled',false);
    //             $('.quiz-mark-complete-next').prop('disabled',false);
    //         }
    //     }

    //     function checkAllBlanksAreFilled() {
    //         var allHasChildren = true;
    //         $('.job-emp').each(function(i, obj) {
    //             console.log(obj.childern);
    //             if(obj.children && obj.children.length > 0 ) {
    //                 //alert(allHasChildren);
    //             } else {
    //                 allHasChildren = false;
    //                 //alert(allHasChildren);
    //             }
    //         });
    //         if(allHasChildren) { 
    //             $('#check-answer-matching').prop('disabled', false);
    //         }             
    //     }

        

    </script>
@endsection
