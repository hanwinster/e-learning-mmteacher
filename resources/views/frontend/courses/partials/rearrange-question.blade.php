@foreach ($currentQuiz->questions as $key => $question)
    <div>
        <div class="row mt-5">
            <div class="col-md-12">
                {!! $question->title !!}
            </div>
        </div>
        <div class="row mt-3">

            @if (isset($questionMedia[$key]) && $question->id == $questionMedia[$key]->model_id)
                <div class="col-md-12 mt-3">
                    <p class="mt-2 mb-2">
                        <img src="{{ asset($questionMedia[$key]->getUrl()) }}" />
                    </p>
                </div>
            @endif
            @if ($arr = $question->rearrange_answer->answer)
                @foreach ($question->rearrange_answer->answer as $key => $answer)
                    @php
                        shuffle($arr);
                    @endphp
                    <div class="col-md-12 mt-3">
                        <div class="d-flex">
                            <div class="mt-2 mr-3">{{ $key + 1 }}. &nbsp;</div>
                            <select name="" id="answer{{ $question->id . $key }}"
                                class="form-select rearrange-items">
                                <option value="">@lang('Select Answer')</option>
                                @php
                                    shuffle($arr);
                                @endphp
                                @foreach ($arr as $matchAnswer)
                                    <option value="{{ $matchAnswer }}" class="rearrange-item">
                                        {{-- title="{!! $matchAnswer !!}"> --}}
                                        {!! strip_tags($matchAnswer) !!}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3 ml-4">
                            <span id="{{ $key . $question->id }}"></span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="row mt-2 ml-1">
            <span id="question{{ $question->id }}"></span>
        </div>
        <div class="row mt-2 ml-1">
            {{-- <p class="primary-color d-none">@lang('Explanation : ')</p> --}}
            <span id="description{{ $question->id }}"></span>
        </div>
    </div>
@endforeach

<div class="row mt-5">
    <button class="btn btn-primary btn-sm" id="check-answer" onclick="checkAnswer({{ $currentQuiz }})"
        disabled="true">@lang('Check Answer')</button>
</div>

@section('script')
    @parent
    <script>
        $(document).on('change', '.rearrange-items', function(e) {
            check_all_selected();

        })

        function check_aswer_all_question() {
            let answerAllQuestion = true;
            document.querySelectorAll('.match-items').forEach(function(item, index) {
                if (item.value == '') {
                    answerAllQuestion = false;
                }
            })
            return answerAllQuestion;
        }

        function check_all_selected() {
            // let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
            if (check_aswer_all_question()) {
                $('#check-answer').prop('disabled', false);
            } else {
                $('#check-answer').prop('disabled', true);
                //$('#'+markComId).prop('disabled',true);
                $('.quiz-mark-complete').prop('disabled', true);
                $('.quiz-mark-complete-next').prop('disabled', true);
                $(".primary-color").addClass("d-none");
            }
        }
        var translations = {
            rightAnswerText: "@lang('The right answer is')",
            right: "@lang('Your answer is correct!')",
            wrong: "@lang('Your answer is wrong!')",
        };

        function checkAnswer(quiz) {
            if (check_aswer_all_question()) {
                let url = "{{ route('quiz.check-answer') }}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        quiz: quiz.id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(response) {
                        // console.log(response);
                        //let markComId = $("#learner-take-quiz").find('.quiz-mark-complete').val();
                        for (let i = 0; i < response.question.length; i++) {
                            let keys = Object.keys(response.question[i].rearrange_answer.answer);
                            for (let j = 0; j < keys.length; j++) {
                                let element = '#' + keys[j] + response.question[i].id;
                                let answerElement = '#answer' + response.question[i].id + keys[j];
                                if (response.question[i].rearrange_answer.answer[keys[j]] === $(answerElement)
                                    .val()) {
                                    $(element).css('color', 'darkcyan');
                                    $(element).html(translations
                                    .right); // + response.question[i].rearrange_answer.answer[keys[j]]);
                                } else {
                                    $(element).css('color', 'darkred');
                                    $(element).html(translations.wrong + translations.rightAnswerText + ' ' +
                                        response.question[i].rearrange_answer.answer[keys[j]]);
                                }
                                //$(element).html('The right answer is ' + response.question[i].rearrange_answer.answer[keys[j]]);
                            }
                            $('#description' + response.question[i].id).html(response.question[i].description);
                            $(".primary-color").removeClass("d-none");
                        }
                        $('.quiz-mark-complete').prop('disabled', false);
                        $('.quiz-mark-complete-next').prop('disabled', false);
                    }
                });
            }
        }
    </script>
@endsection
