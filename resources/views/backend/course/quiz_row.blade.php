@forelse($quizzes as $key => $quiz)
<tr bgcolor="#fcfdfe">
    <td>{{ $key + 1 }}</td>
    <td>{{ $quiz->title }}</td>
    <td>{{ $quiz->getQuizType() }}</td>                                                        
    <td>{{ $quiz->created_at ?? '' }} </td>                                            
    <td class="text-right table-options">
        <div class="btn-group btn-small">
            @can('edit_quiz')
            <a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.quiz.edit', [$quiz->id]) }}" data-provide="tooltip"
                title="Edit"><i class="ti-pencil"></i>  </a>
            @endcan
            @if(auth()->user()->can('delete_quiz') || auth()->user()->can('add_question'))
            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu">
                @can('delete_quiz')
                {!! Form::open(array('route' => array('admin.quiz.destroy', $quiz->id), 'method' => 'delete'
                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                <i class="ti-trash"></i>
                {{ __('Delete') }}
                </button>
                {!! Form::close() !!}
                @endcan
                @can('add_question')
                <a class="btn pr-2 pl-2 ml-1 btn-outline dropdown-item" href="{{route('admin.question.create', $quiz->id)}}" data-provide="tooltip" title="New Quiz"><i class="ti-plus"></i> {{ __('New Question') }} </a>
                @endcan
            </div>
            @endif
        </div>
    </td>
</tr>
@if(isset($quiz) && $quiz->questions()->count() != 0)
    <tr>
        <td colspan="6" class="bg-white px-10">
            <table style="width: 100%;" class="question_table">
                <tr>
                    <th>@lang('No#')</th>
                    <th>@lang('Question Title')</th>
                    <th>@lang('Answers')</th>
                    <th>@lang('Description')</th>
                    <th>@lang('Image')</th>
                    <th>@lang('Actions')</th>
                </tr>                                                        
            @foreach($quiz->questions as $key => $question)
                <tr>
                    <td width="10">({{ $key + 1 }})</td>
                    <td width="150">{!! $question->title ?? '' !!}</td>
                    <td width="300" border="0">
                        @if($quiz->type == \App\Models\Quiz::TRUE_FALSE)
                            <div>{{$question->true_false_answer->answer}}</div>
                        @elseif($quiz->type == \App\Models\Quiz::SHORT_QUESTION)
                            <div>{{$question->short_answer->answer}}</div>
                        @elseif($quiz->type == \App\Models\Quiz::BLANK)
                            <div>{{$question->blank_answer->answer}}</div>
                        @elseif($quiz->type == \App\Models\Quiz::MULTIPLE_CHOICE)
                            @forelse($question->multiple_answers as $key=>$value)
                                <div style="@if($value->is_right_answer) color:red; @endif">{{$value->name}}. {{$value->answer}}</div>
                            @empty
                            @endforelse
                        @elseif($quiz->type == \App\Models\Quiz::REARRANGE)
                            @php
                                $num = 1;
                            @endphp
                            @foreach($question->rearrange_answer->answer as $answer)
                                <div>{{$num}}. {{ $answer }}</div>
                                @php
                                    $num++;
                                @endphp
                            @endforeach
                        @elseif($quiz->type == \App\Models\Quiz::MATCHING)
                            @forelse($question->matching_answer->answer as $value)
                            <div class="row">
                                <div class="col-md-5">{{$value['name_first']}}. {{$value['first']}}</div>
                                <div class="col-md-2"> = </div>
                                <div class="col-md-5">{{$value['name_second']}}. {{$value['second']}}</div>
                            </div>
                            @empty
                            @endforelse
                        @else
                        @endif
                     </td>
                    <td width="150">{!!$question->description ?? '' !!}</td>
                    <td>
                        @foreach($question->getMedia('question_attached_file') as $resource)
                            <a href="{{asset($resource->getUrl())}}"  class="">{{ $resource->file_name }}</a>
                            ({{ $resource->human_readable_size }})
                        @endforeach
                    </td>
                    <td class="text-right">
                        <div class="btn-group btn-small">
                                @can('edit_question')
                                    <a class="btn btn-default btn-sm border-0" href="{{ route('admin.question.edit', [$question->id]) }}" data-provide="tooltip"
                                    title="Edit"><i class="ti-pencil"></i></a>
                                @endcan
                                @can('delete_question')
                                    {!! Form::open(array('route' => array('admin.question.destroy', $question->id), 'method' => 'delete'
                                    , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                    <button data-provide="tooltip" style="cursor: pointer" data-toggle="tooltip" title="Delete" type="submit" class="btn btn-default btn-sm text-danger border-0">
                                    <i class="ti-trash"></i>
                                    </button>
                                    {!! Form::close() !!}
                                @endcan
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        </td>
    </tr>
    @endif
@empty
    <tr>
        <td colspan="6"><div class="text-center">No records.</div></td>
    </tr>
@endforelse
            