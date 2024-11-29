<!-- @foreach($currentQuiz->questions as $key => $question) -->
@php $question = $currentQuiz->questions[0]; @endphp
    <div class="row mt-5">
        <div class="col-md-12">
            {!! $question->title !!}
        </div>
    </div>
   
    <form action="{{ route('courses.submit-long-answer-quiz') }}" method="POST">  
        @csrf
        <div class="row mt-3">
            <div class="col-md-12">
                    @if(isset($questionMedia[0]) && ($question->id == $questionMedia[0]->model_id))
                        <p class="mt-2 mb-2">
                            <img src="{{ asset($questionMedia[0]->getUrl()) }}" />
                        </p>
                    @endif
                    <div class="col-12 mt-3">
                        @if($longAnswerUser)
                            <h6>@lang('Submitted Answer')</h6>
                        @endif
                        <textarea name="answers[]" id="lanswer{{$question->id}}" rows="10" class="form-control" 
                            placeholder="Write down you answer" {{isset($longAnswerUser) && 
                                $longAnswerUser->status !== 'retake' ? 'readonly' : ''}}
                        >{{$longAnswerUser ? $longAnswerUser->submitted_answer[0] : ''}}</textarea>
                        @if(!$longAnswerUser || $longAnswerUser->status === 'retake')
                            {{-- <p class="mt-3">@lang('Answer should have at least 100 words')</p>  --}}
                        @endif
                        @if($longAnswerUser) 
                            <h6 class="mt-4">@lang('Suggested Answer')</h6>
                            <div class="mt-2 text-primary">
                                {!! $question->long_answer->answer !!}
                            </div>
                        @endif
                    </div>
        </div>
        <div class="row mt-2 ml-1">
            <div class="col-md-12">
                <span id="question{{$question->id}}"></span>
            </div>
            <div class="row mt-2 ml-1">
                <span id="description{{$question->id}}"></span>
            </div>
        </div>
<!-- @endforeach -->
@php 
    $findVal = $currentQuiz->lecture_id === null ? 'quiz_'.$currentQuiz->id : 'lq_'.$currentQuiz->id; 
@endphp
    <div class="row mt-5">
        <input type="hidden" name="find_val" value="{{$findVal}}">
        <input type="hidden" name="course_id" value="{{$course->id}}">
        <input type="hidden" name="quiz_id" value="{{$currentQuiz->id}}">
        <input type="hidden" name="question_id" value="{{$question->id}}">
        @if($longAnswerUser && $longAnswerUser->status !== 'pass')
            <button class="btn btn-primary btn-sm" id="submit-long-answer" 
                disabled="true">@lang('Submit Answer')</button> <!-- onclick="submitAnswer({{ $currentQuiz }})" -->
        @elseif($longAnswerUser)
            <button class="btn btn-primary btn-sm" 
                disabled="true">@lang('Submitted and passed')</button>
        @else 
            <button class="btn btn-primary btn-sm" id="submit-long-answer" 
                disabled="true">@lang('Submit Answer')</button>                     
        @endif
        
    </div>
</form>
@if($longAnswerUser)
    <div class="row mt-5">
        <h6>@lang('Status')</h6>
            @if( $longAnswer->passing_option == 'after_providing_answer' ) 
                @lang('Submitted and passed')
            @elseif( $longAnswer->passing_option == 'after_sending_feedback' ) 
                @if($longAnswerUser && $longAnswerUser->comment) 
                    <p class="text-dark">
                        @lang('Feedback from the course owner: ')
                        <span class="text-primary fst-italic">"{{ $longAnswerUser->comment }}"</span>
                    </p>
                @else 
                    @lang('Waiting for the feedback from the course owner')  
                @endif
                  
            @else 
                @if($longAnswerUser && $longAnswerUser->status == 'pass') 
                    <div>
                        <p class="text-dark">@lang('Feedback from the course owner: ')
                            <span class="text-primary fst-italic">"{{ $longAnswerUser->comment }}"</span>
                        </p>
                    </div>
                @elseif($longAnswerUser && $longAnswerUser->status == 'retake') 
                    <div>
                        <p class="text-dark">@lang('Feedback from the course owner: ')
                            <span class="text-primary fst-italic">"{{ $longAnswerUser->comment }}"</span>
                        </p>
                    </div>                   
                @else 
                
                    @lang('Waiting for the feedback from the course owner and the answer is marked as satisfactory')  
                @endif  
            @endif
    </div>
@endif
@section('script')
    @parent
    <script>
        $(document).ready(function () {
            var quiz = @json($currentQuiz);
            var findVal = @json($findVal);
            var courseId = @json($course->id);
           // let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
            for (let i = 0; i < quiz.questions.length; i++) {
                $('#lanswer' + quiz.questions[i].id).keyup(function () { //console.log('keyup  ', quiz.questions[i].id);
                    let counter = checkAllInput();  //console.log("counter ", countWords('#lanswer' + quiz.questions[i].id));  
                    let wordCount = countWords('#lanswer' + quiz.questions[i].id);
                    //if (counter === quiz.questions.length) {
                    //TODO: need to bring the limit of words logic after having word limit in BE
                    if(wordCount >= 1 && counter === quiz.questions.length) {
                        $('#submit-long-answer').prop('disabled', false);
                    } else {
                        $('#submit-long-answer').prop('disabled', true);
                        $('.quiz-mark-complete').prop('disabled',true);
                        $('.quiz-mark-complete-next').prop('disabled',true);
                    }
                });
            }

            function checkAllInput() { //console.log('checkAllInput', quiz.questions);
                let counter = 0;
                for (let i = 0; i < quiz.questions.length; i++) {
                    if($('#lanswer' + quiz.questions[i].id).val() !== '') {
                        counter++;
                    }
                }
                return counter;
            }

            function countWords(id) {
 
                // Get the input text value
                var text = $(id).val(); //document.getElementById(id).value;
               // console.log(text);
               if(text) return 1;
                // Initialize the word counter
                var numWords = 0;

                // Loop through the text
                // and count spaces in it
                for (var i = 0; i < text.length; i++) {
                    var currentCharacter = text[i];
                    // Check if the character is a space
                    if (currentCharacter == " ") {
                        numWords += 1;
                    }
                }
                // Add 1 to make the count equal to
                // the number of words
                // (count of words = count of spaces + 1)
                numWords += 1;
                return numWords;
            }

        });
        var translations = {
                            rightAnswerText: "@lang('Suggested answer is ')",
                            right: "@lang('Your answer is correct!')",
                            wrong: "@lang('Your answer is wrong!')",                          
            };
        
        //console.log(findVal);
        function submitAnswer(quiz) {         //not in use and can be deleted later
            let url = "{{ route('courses.submit-long-answer-quiz') }}";
            var answers = [];
            for (let i = 0; i < quiz.questions.length; i++) {
                let val = $('#lanswer' + quiz.questions[i].id).val();
                let idVal = quiz.questions[i].id;
                if( val !== '') {
                    answers.push({ [idVal]: val });
                }
            }
            $.ajax({ 
                type:"POST",
                url: url,
                data: {
                    quiz_id: quiz['id'],
                    find_val: findVal,
                    course_id: courseId,
                    answers: answers,
                    _token: $('meta[name="csrf-token"]').attr('content') 
                },

                success: function (response) { //console.log(response);
                    //let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
                    for (let i = 0; i < response.question.length; i++) {
                        let element = '#question' + response.question[i].id;
                        let answer = $('#lanswer' + response.question[i].id).val().toLowerCase();
                        if(answer !== response.question[i].long_answer.answer.toLowerCase()) {
                            $(element).css('color', 'green');
                            $(element).html(translations.rightAnswerText + ' '+response.question[i].long_answer.answer);
                        } else {
                            $(element).css('color', 'green');
                            $(element).html(translations.right);
                        }
                        //$(element).html('The right answer is ' + response.question[i].long_answer.answer);
                        $('#description' + response.question[i].id).html(response.question[i].description);
                    }
                    $('.quiz-mark-complete').prop('disabled',false);
                    $('.quiz-mark-complete-next').prop('disabled',false);
                }
            });
        }
    </script>
@endsection
