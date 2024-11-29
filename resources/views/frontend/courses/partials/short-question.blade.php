@foreach($currentQuiz->questions as $key => $question)
    <div class="row mt-5">
        <div class="col-md-12">
            {!! $question->title !!}
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            @if(isset($questionMedia[$key]) && ($question->id == $questionMedia[$key]->model_id))
                    <p class="mt-2 mb-2">
                        <img src="{{ asset($questionMedia[$key]->getUrl()) }}" />
                    </p>
            @endif
            <div class="col-md-6 mt-3">
            <textarea name="" id="answer{{$question->id}}" rows="2" class="form-control" placeholder="Write down you answer"></textarea>
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
           // let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
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
        function checkAnswer(quiz) {         
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
                        let element = '#question' + response.question[i].id;
                        let answer = $('#answer' + response.question[i].id).val().toLowerCase();
                        if(answer !== response.question[i].short_answer.answer.toLowerCase()) {
                            $(element).css('color', 'green');
                            $(element).html(translations.rightAnswerText + ' '+response.question[i].short_answer.answer);
                        } else {
                            $(element).css('color', 'green');
                            $(element).html(translations.right);
                        }
                        //$(element).html('The right answer is ' + response.question[i].short_answer.answer);
                        $('#description' + response.question[i].id).html(response.question[i].description);
                    }
                    $('.quiz-mark-complete').prop('disabled',false);
                    $('.quiz-mark-complete-next').prop('disabled',false);
                }
            });
        }
    </script>
@endsection
