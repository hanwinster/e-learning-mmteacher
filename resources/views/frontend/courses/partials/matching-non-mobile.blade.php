
<div class="col-12">
    <div class="row drag-content-2">
        <div class="col-4">
            @php
                shuffle($arr);
            @endphp
            @foreach ($arr as $key => $answer)
                <div class="emp2">
                    {!! $answer['second'] !!}
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="col-md-6 mt-3">
    <div class='drop-content'>
        <div class='job'>
            @foreach ($question->matching_answer->answer as $key => $answer)
                <div class='job-name line'>{!! $answer['first'] !!}</div>
            @endforeach
        </div>
    </div>
</div>
<div class="col-md-6 mt-3">
    <div class='drop-content'>
        <div class='job'>
            @foreach ($question->matching_answer->answer as $key => $answer)
                <div class='job-emp'></div>
            @endforeach
        </div>
    </div>
</div>