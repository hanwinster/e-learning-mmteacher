<div id="true-false-question">
    @foreach($currentQuiz->questions as $key => $question)
        <div>
            <div class="row mt-5">
                <div class="col-md-12">
                    {!! $question->title !!}
                </div>
            </div>
            <div class="row mt-3">
                @if(isset($questionMedia[$key]) && ($question->id == $questionMedia[$key]->model_id))
                    <p class="mt-2 mb-2">
                        <img src="{{ asset($questionMedia[$key]->getUrl()) }}" />
                    </p>
                @endif
                <input type="radio" name="question{{ $question->id }}" value="true" class="mt-2 ml-2 mr-1">@lang('True')
                <input type="radio" name="question{{ $question->id }}" value="false" class="mt-2 ml-5 mr-1">@lang('False')
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
        <button class="btn btn-primary btn-sm" id="check-answer" disabled="true"
            onclick="checkAnswer({{ $currentQuiz }})"
        >@lang('Check Answer')</button>
    </div>
</div>

@section('script')
    @parent
    <script>
        $(document).ready(function () { 
            let quiz = @json($currentQuiz); 
            
            for (let i = 0; i < quiz.questions.length; i++) {
                let selector = 'input[name=' + "question" + quiz.questions[i].id + ']';
                $(selector).on('change', function () { 
                    let counter = checkAllInput();
                    if (counter === quiz.questions.length) {
                        $('#check-answer').prop('disabled', false);
                    } else {
                        $('#check-answer').prop('disabled', true);
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
            event.preventDefault();
            // $.ajaxSetup({
            //     'X-CSRF-TOKEN': "{{ @csrf_token() }}"
            // });
            
            let url = "{{ route('quiz.check-answer') }}";
            $.ajax({
                type:"POST",
                url: url,
                data: {
                    quiz: quiz.id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function (response) { 
                    // let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
                    // let markComIdN = $("#learner-take-quiz").find('.quiz-mark-complete-next').val();
                   
                    for (let i = 0; i < response.question.length; i++) {
                        let element = '#question' + response.question[i].id;
                        if($('input[name="question' + response.question[i].id +'"]:checked').val() === response.question[i].true_false_answer.answer) {
                            $(element).css('color', 'darkcyan');
                            $(element).html( translations.right);
                        } else {
                            $(element).css('color', 'darkred');
                            $(element).html(translations.wrong + translations.rightAnswerText + ' '+ response.question[i].true_false_answer.answer);
                        }
                        //$(element).html(translations.rightAnswer + response.question[i].true_false_answer.answer);
                        $('#description' + response.question[i].id).html(response.question[i].description);
                    }
                    // $('#'+markComId).prop('disabled',false);
                    // $('#'+markComIdN).prop('disabled',false);
                    $('.quiz-mark-complete').prop('disabled',false);
                    $('.quiz-mark-complete-next').prop('disabled',false);
                }
            });
        }
    </script>
@endsection
