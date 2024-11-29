@foreach($currentQuiz->questions as $key => $question)
    <div>
        <div class="row mt-5">
            <div class="col-md-12">
                {!! $question->title !!}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                @if(isset($questionMedia[$key]) && ($question->id == $questionMedia[$key]->model_id))
                    <p class="mt-2 mb-2">
                        <img src="{{ asset($questionMedia[$key]->getUrl()) }}" />
                    </p>
                @endif
                
                @if($blankAnswers && isset($blankAnswers[$key]))
                    <div class="container">
                        {{-- @foreach($blankAnswers[$key] as $idx => $val)    --}}
                            @php $val = $blankAnswers[$key]; @endphp
                            <div class="row">                                                
                                <div class="col-12 col-sm-6 mt-2">
                                    <div class="row drag-content-{{$key}}">
                                        <div class="col-12">
                                                @foreach($val->paragraph as $para)
                                                    @php  
                                                        $keys = array_keys($para);
                                                        $value = array_values($para);
                                                    @endphp
                                                    @if(strpos($keys[0], 'blank_') !== false)
                                                        <div class='emp'>{{ $value[0] }}</div>
                                                    @endif
                                                @endforeach
                                                @if(isset($val->optional_keywords))
                                                    @php $optionals = explode(",", $val->optional_keywords); @endphp
                                                    @foreach($optionals as $opt)
                                                        <div class='emp'>{{ $opt }}</div>
                                                    @endforeach
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 mt-2">
                                        <div class='drop-content'>
                                            <div class='job'>                                                
                                                @foreach($val->paragraph as $para)
                                                    @php  
                                                        $keys = array_keys($para);
                                                        $value = array_values($para);
                                                    @endphp
                                                    @if(strpos($keys[0], 'sentence_') !== false)                                              
                                                        <div class='job-name'>{{ $value[0] }}</div>                                                      
                                                    @endif
                                                    @if(strpos($keys[0], 'blank_') !== false) 
                                                        <div class="job-emp job-emp-val-{{$key}}"
                                                         value="{{ $key }}"></div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                         
                        {{-- @endforeach --}}
                    </div>
                @else  
                    <input type="text" class="form-control" id="answer{{$question->id}}" placeholder="Type Your Answer"/>
                @endif
            </div>
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
@if($blankAnswers)
    <button class="btn btn-primary btn-sm" id="check-answer" disabled="true" onclick="checkAnswers()">@lang('Check Answer')</button>
@else 
    <button class="btn btn-primary btn-sm" id="check-answer" disabled="true" onclick="checkAnswer({{$currentQuiz}})">@lang('Check Answer')</button>
@endif
</div>

@section('script')
    @parent
    <script>
        let quiz = @json($currentQuiz);
        let blankAnswers = @json($blankAnswers);
        console.log(blankAnswers);

        function checkAnswers() {
           
            let answersOnly = [];
            for(let i=0; i<blankAnswers.length; i++) {
               // let temp = [];
                for(let j=0; j< blankAnswers[i].paragraph.length; j++) {
                   // console.log(blankAnswers[i].paragraph[j]);
                    for (const key in blankAnswers[i].paragraph[j]) {
                        //console.log(`${key}: ${blankAnswers[i].paragraph[j][key]}`);
                        if(key.includes('blank_')) {
                            answersOnly.push(blankAnswers[i].paragraph[j][key]);
                        }
                    } 
                    //temp.push(blankAnswers[i].paragraph[j])
                }
               // answersOnly.push(temp);
            }
           
            var allAreCorrect = true;
            $('.job-emp').each(function(i, obj) {
                if(obj.children && obj.children.length > 0 ) {
                    //console.log(obj.children[0].outerText); 
                    let userDropValue = obj.children[0].outerText;
                    if(obj.children[0].outerText.indexOf(String.fromCharCode(10060)) >= 0 ) {                     
                        $(this).find('.emp').html( $(this).find('.emp').html().replace(String.fromCharCode(10060), "") ); 
                        userDropValue = userDropValue.replace(String.fromCharCode(10060), '');
                    } 
                    if(obj.children[0].outerText.indexOf(String.fromCharCode(10004)) >= 0 ) {                       
                        $(this).find('.emp').html( $(this).find('.emp').html().replace(String.fromCharCode(10004), "") );
                        userDropValue = userDropValue.replace(String.fromCharCode(10004), ''); 
                    }                                  
                   // console.log(userDropValue);
                    
                    if($.trim(userDropValue) != $.trim(answersOnly[i])) {
                        allAreCorrect = false;                  
                        obj.children[0].append(' '+String.fromCharCode(10060));
                    } else {                   
                        obj.children[0].append(' '+String.fromCharCode(10004));
                    }
                } else {
                    allAreCorrect = false;             
                }
            });
            //console.log("all are correct ", allAreCorrect);
            if(allAreCorrect) {
                $('.quiz-mark-complete').prop('disabled',false);
                $('.quiz-mark-complete-next').prop('disabled',false);
            }
        }

        function touchHandler(event) {
            //event.preventDefault();
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
            document.addEventListener("touchstart", touchHandler);
            document.addEventListener("touchmove", touchHandler);
            document.addEventListener("touchend", touchHandler);
            document.addEventListener("touchcancel", touchHandler);
        }
        init();

        $(document).ready(function () {           
            //console.log(blankAnswers);
            $('#check-answer').prop('disabled', true);
            $('.quiz-mark-complete').prop('disabled',true);
            $('.quiz-mark-complete-next').prop('disabled',true);
            $('.emp').draggable({
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
                accept: '.emp',
                activeClass: 'active',
                drop: function(event, ui) {
                    ui.draggable.addClass('dropped');
                    ui.draggable.detach().appendTo($(this));
                    checkAllBlanksAreFilled();
                }
            });
            $('.job-emp').dblclick(function() { 
               // console.log($(this).find('.job-emp')); //attr('class').split(' ')[1])
               // console.log($(this).attr('class').split(' ')[1]);
                var anotherClass = $(this).attr('class').split(' ')[1];
                var value = anotherClass.split('job-emp-val-')[1];
               //console.log('value is ',value);
                let text = $(this).find('.emp').text();            
                if(this.children.length > 0) {
                    $(this).find('.emp').removeClass('ui-draggable-dragging');
                    $(this).find('.emp').removeClass('dragged');
                    $(this).find('.emp').removeClass('dropped');
                    $(this).find('.emp').css({"top": ""});
                    $(this).find('.emp').css({"left": ""});                     
                    if(text.indexOf(String.fromCharCode(10060)) >= 0 ) {
                        $(this).find('.emp').html( $(this).find('.emp').html().replace(String.fromCharCode(10060), "") ); 
                    } else {
                        $(this).find('.emp').html( $(this).find('.emp').html().replace(String.fromCharCode(10004), "") ); 
                    } 
                    var emptyCol4 = null;
                    var self = this;      
                    var dragContentClass = ".drag-content-"+value;           
                    var emptyChildren = $(dragContentClass).children("div.col-12").first(function () {
                        return !($(this).text());
                    }); 
                    emptyChildren.append(this.children);
                    // $(this).find('.emp').removeClass('dropped');
                    // $(this).find('.emp').css({"top": ""});
                    // $(this).find('.emp').css({"left": ""});
                    
                    // if(text.indexOf(String.fromCharCode(10060)) >= 0 ) {
                    //     $(this).find('.emp').html( $(this).find('.emp').html().replace(String.fromCharCode(10060), "") ); 
                    // } else {
                    //     $(this).find('.emp').html( $(this).find('.emp').html().replace(String.fromCharCode(10004), "") ); 
                    // }              
                    // $('.drag-content').append(this.children);
                }
            });
            function checkAllBlanksAreFilled() {
                let allHasChildren = true;
                $('.job-emp').each(function(i, obj) {
                    if(obj.children && obj.children.length > 0 ) {
                       // console.log(obj.children);
                    } else {
                        allHasChildren = false;
                        console.log('empty');
                    }
                });
                if(allHasChildren) { 
                    $('#check-answer').prop('disabled', false);
                }             
            }
            
            // $('.job-emp').draggable({
            //     revert: "invalid", 
            //     containment: "window",
            //     cursor: "move",
            //     start: function(event, ui) {
            //         $(this).addClass('dragged');
            //     },
            //     stop: function(event, ui) {
            //         $(this).removeClass('dragged');
            //     }
            // });
            // $('.emp').droppable({
            //     accept: '.job-emp',
            //     activeClass: 'active',
            //     drop: function(event, ui){
            //         ui.draggable.addClass('dropped');
            //         ui.draggable.detach().appendTo($(this));
            //     }
            // });
            for (let i = 0; i < quiz.questions.length; i++) {
                $('#answer' + quiz.questions[i].id).keyup(function () {
                    let counter = checkAllInput();
                    if (counter === quiz.questions.length) {
                        $('#check-answer').prop('disabled', false);
                    } else {
                        $('#check-answer').prop('disabled', true);
                        $('.quiz-mark-complete').prop('disabled',true);
                        $('.quiz-mark-complete-next').prop('disabled',true);
                    }
                });
            }

            function checkAllInput() {
                let counter = 0;
                for (let i = 0; i < quiz.questions.length; i++) {
                    if($('#answer' + quiz.questions[i].id).val() !== '') {
                        counter++;
                    }
                }
                return counter;
            }
        });
        var translations = {
                            rightAnswerText: "@lang('Suggested answer is ')",
                            right: "@lang('Your answer is correct!')",
                            wrong: "@lang('Your answer is wrong!')",                          
            };
        
    </script>
@endsection
