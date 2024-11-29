@foreach($currentQuiz->questions as $key => $question)
    <div>
        <div class="row mt-2">
            <div class="col-md-12">
                {!! $question->title !!}
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-12">
                @if(isset($questionMedia[$key]) && ($question->id == $questionMedia[$key]->model_id))
                    <p class="mt-2 mb-2">
                        <img src="{{ asset($questionMedia[$key]->getUrl()) }}" />
                    </p>
                @endif
                @foreach($question->multiple_answers as $idx => $answer)
                    <div>
                        <input type="checkbox" name="question{{$question->id}}"
                               value="{{strip_tags($answer->answer)}}"
                               class="mt-1 mr-2" id="question_{{$answer->id}}">
                        <label for="question_{{$answer->id}}">{!! $answer->answer !!}</label>
                        
                    </div>
                @endforeach
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
    <button class="btn btn-primary btn-sm" id="check-answer" onclick="checkAnswer({{ $currentQuiz }})"
        disabled="true"
    >@lang('Check Answer')</button>
</div>

@section('script')
    @parent
    <script>

        $(document).ready(function () {
            let quiz = @json($currentQuiz);
            //let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
            for (let i = 0; i < quiz.questions.length; i++) {
                let selector = 'input[name=' + "question" + quiz.questions[i].id + ']';
                $(selector).on('change', function () {
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
                    let selector = 'input[name=' + "question" + quiz.questions[i].id + ']';
                    if($(selector + ":checked").val() !== undefined) {
                        counter++;
                    }
                }
                return counter;
            }
        });
        var translations = {
                            rightAnswerText: "@lang('The right answer is')",
                            right: "@lang('Your answer is correct!')",
                            wrong: "@lang('Your answer is wrong!')",                          
            };
        function checkAnswer(quiz) {
            let url = "{{ route('quiz.check-answer') }}";
            let checkUserAnswer = function (userAnswers, actualAnswers) {
                let isRight = true;
                console.log(userAnswers, actualAnswers);
                if(userAnswers.length !== actualAnswers.length) {
                    return false;
                }
                for(let i = 0; i < userAnswers.length; i ++) {
                    if(userAnswers[i] == actualAnswers[i]) {
                        isRight = isRight & true;
                    } else {
                        isRight = isRight & false;
                    }
                    console.log('is right ? ',i, isRight);
                }
                
                return isRight;
            };
            $.ajax({
                type:"POST",
                url: url,
                data: {
                    quiz: quiz.id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function (response) { console.log(response);
                   // let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
                    for (let i = 0; i < response.question.length; i++) {
                        let element = '#question' + response.question[i].id;
                        let userAnswers = [];
                        $('input[name="question' + response.question[i].id +'"]:checked').each(function () {
                            userAnswers.push($(this).val());
                        });

                        let actualAnswers = response.question[i].multiple_answers
                            .filter(answer => answer.is_right_answer)
                            .map(answer => answer.answer);

                        let answerIsTrue = checkUserAnswer(userAnswers, actualAnswers);

                        if(answerIsTrue) {
                            $(element).css('color', 'darkcyan');
                            $(element).html(translations.right);
                        } else {
                            $(element).css('color', 'darkred');
                            $(element).html(translations.wrong + translations.rightAnswerText + ' '+actualAnswers);
                        }
                        //$(element).html('The right answer is ' + actualAnswers);
                        $('#description' + response.question[i].id).html(response.question[i].description);
                    }
                    $('.quiz-mark-complete').prop('disabled',false);
                    $('.quiz-mark-complete-next').prop('disabled',false);
                }
            });
        }
    </script>
@endsection
