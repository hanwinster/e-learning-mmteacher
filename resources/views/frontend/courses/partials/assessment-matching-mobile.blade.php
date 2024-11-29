<div class="col-12 mt-3">
    <div class='drop-content'>
        <div class='job'>
            @for ($i = 0; $i < sizeof($assessment->answers); $i++)
                <div class='job-name line'>{!! $assessment->answers[$i] !!}</div>
            @endfor
        </div>
    </div>
</div>
<div class="col-12 mt-3">
    <div class="row drag-content-2">
        <div class="col-4">
            @if (!isset($post->answers))
                @foreach ($rightSorted as $key => $value)
                    <div class="emp2">
                        {!! strip_tags($value) !!}
                    </div>
                @endforeach
            @else
                <!-- <div class="emp2"></div> -->
            @endif
        </div>
        <div class="col-8">
            <div class='drop-content'>
                <div class='job'>
                    @foreach ($assessment->right_answers as $key => $value)
                        @if (isset($post->answers[$key]))
                            {{-- && $post->answers[$key] == strip_tags($value)) --}}
                            <div id="emp2_{{ $key }}" class="job-emp ui-droppable">
                                <div class="emp2 ui-draggable ui-draggable-handle dropped">
                                    {!! str_limit($post->answers[$key], 100, '...') !!}
                                </div>
                            </div>
                            <input id="match_answers_{{ $key }}" name="answers[]" value="{{ strip_tags($value) }}"
                                type="hidden">
                        @else
                            <div id="emp2_{{ $key }}" class='job-emp'></div>
                            <input id="match_answers_{{ $key }}" name="answers[]" value="" type="hidden">
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


