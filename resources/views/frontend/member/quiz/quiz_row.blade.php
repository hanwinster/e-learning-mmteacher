
@forelse($quizzes as $key => $quiz)
<tr class="question-row">
    <td>{{ $key + 1 }}</td>
    <td>{{ strip_tags($quiz->title) }}</td>
    <td>{{ $quiz->getQuizType() }}</td>
    <td>{{ $quiz->created_at ?  date('d-m-Y', strtotime($quiz->created_at)) : '' }} </td>
    <td>{{ $quiz->updated_at ?  date('d-m-Y', strtotime($quiz->updated_at)) : '' }} </td>
    <td class="text-right table-options">
        <div class="btn-group btn-small">   
                                                                       
            @if ($canEdit)
                <a class="btn pr-2 pl-2 btn-outline text-info" href="{{ route('member.quiz.edit', [$quiz->id]) }}" 
                            data-provide="tooltip" title="Edit"><i class="fas fa-edit"></i> {{ __('Edit') }} </a>
                <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" 
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu">
                    @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course))
                    <span class="tooltip-info" data-toggle="tooltip" data-placement="right" 
                        title="@lang('Cannot delete as the course already had course takers')">
                        <span class="btn pr-2 pl-1 btn-outline disabled text-danger">
                            <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                        </span>
                    </span>
                    
                    @else
                        @if(isset($quiz) && $quiz->questions()->count() != 0)
                            {!! Form::open(array('route' => array('member.quiz.destroy', $quiz->id), 'method' => 'delete'
                                        , 'onsubmit' => 'return confirm("Are you sure you want to delete the quiz and the questions attached?");', 'style' => 'display: inline', '')) !!}
                                        <button data-provide="tooltip" style="cursor: pointer" data-toggle="tooltip" title="Delete" 
                                                type="submit" class="btn pr-2 pl-1 btn-outline text-danger c-pointer">
                                            <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                        </button>
                                {!! Form::close() !!}             
                            @else
                                {!! Form::open(array('route' => array('member.quiz.destroy', $quiz->id), 'method' => 'delete'
                                        , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                        <button data-provide="tooltip" style="cursor: pointer" data-toggle="tooltip" title="Delete" 
                                                type="submit" class="btn pr-2 pl-1 btn-outline text-danger c-pointer">
                                            <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                        </button>
                                {!! Form::close() !!}
                            @endif 
                            
                            </div>
                    @endif 
                    @if(( $quiz->type === 'long_question' || $quiz->type === 'assignment' ) && $quiz->questions()->count() > 0)
                        <span class="tooltip-info" data-toggle="tooltip" data-placement="right" 
                                title="@lang('Allow only one question for long question & assignment')">
                                <span class="btn pr-2 pl-1 btn-outline disabled">
                                    <i class="fas fa-plus"></i> {{ __('New Question') }}
                                </span>
                        </span>
                    @else
                        @if(!\App\Repositories\CoursePermissionRepository::canAdd($course))
                            <span class="tooltip-info" data-toggle="tooltip" data-placement="right" 
                                    title="@lang('Cannot add new as the course owner locked it')">
                                    <span class="btn pr-2 pl-1 btn-outline disabled">
                                        <i class="fas fa-plus"></i> {{ __('New Question') }}
                                    </span>
                            </span>
                        @else 
                            <a class="btn pr-2 pl-1 btn-outline text-success" href="{{ route('member.question.create', [$quiz->id]) }}" 
                                    data-provide="tooltip" title="New Quiz">
                            <i class="fas fa-plus"></i> {{ __('New Question') }} </a>        
                        @endif
                    @endif                       
            @endif
           
        </div>
    </td>
</tr>
@if(isset($quiz) && $quiz->questions()->count() != 0)
<tr>
    <td colspan="6" class="bg-white">
        <div class="table-responsive">
            <table class="question_table">
                <tbody>
                    <tr>
                        <th>@lang('No#')</th>
                        <th>
                            @if($quiz->type == \App\Models\Quiz::ASSIGNMENT)
                                @lang('Assignment Title')
                            @else
                                @lang('Question Title')
                            @endif
                        </th>
                        @if($quiz->type == \App\Models\Quiz::ASSIGNMENT)
                            <th colspan="2">
                                @lang('Attached File')
                        @else
                            <th>
                                @lang('Answers')
                        @endif
                           
                        </th>
                        <th>@lang('Description')</th>
                        @if($quiz->type != \App\Models\Quiz::ASSIGNMENT)
                            <th>@lang('Image')</th>
                        @endif
                        <th class="text-center">@lang('Actions')</th>
                    </tr>
                    
                    @foreach($quiz->questions as $key => $question)
                        <tr>
                            <td width="10">({{ $key + 1 }})</td>
                            <td>{!! $question->title ? strip_tags($question->title) : '' !!}</td>
                            @if($quiz->type == \App\Models\Quiz::ASSIGNMENT)
                                <td colspan="2">
                            @else
                                <td>
                            @endif
                                <div>                                   
                                @if($quiz->type == \App\Models\Quiz::TRUE_FALSE)
                                    <div>{!! isset($question->true_false_answer->answer) ? $question->true_false_answer->answer : null !!}</div>
                                @elseif($quiz->type == \App\Models\Quiz::SHORT_QUESTION)
                                    <div>{!! isset($question->short_answer->answer) ? $question->short_answer->answer : null !!}</div>
                                @elseif($quiz->type == \App\Models\Quiz::LONG_QUESTION)
                                    <div>{!! isset($question->long_answer->answer) ? $question->long_answer->answer : null !!}</div>
                                @elseif($quiz->type == \App\Models\Quiz::BLANK)
                                    <div>{!! isset($question->blank_answer->answer) ? $question->blank_answer->answer : null !!}</div>
                                @elseif($quiz->type == \App\Models\Quiz::MULTIPLE_CHOICE)
                                    @if(isset($question->multiple_answers))
                                        @forelse($question->multiple_answers as $key=>$value)
                                            <div style="@if($value->is_right_answer) color:red; @endif">
                                                {!! $value->name !!}. {!! $value->answer !!}
                                            </div>
                                        @empty
                                        @endforelse
                                    @endif
                                @elseif($quiz->type == \App\Models\Quiz::REARRANGE)
                                    @foreach($question->rearrange_answer->answer as $key => $answer)
                                        <div>{{$key + 1}}. {!! $answer !!}</div>
                                    @endforeach
                                @elseif($quiz->type == \App\Models\Quiz::MATCHING)
                                    @forelse($question->matching_answer->answer as $value)
                                        <div class="row">
                                            <div class="col-md-5">{!! $value['name_first'] !!}. {!! $value['first'] !!}</div>
                                            <div class="col-md-2"> = </div>
                                            <div class="col-md-5">{!! $value['name_second'] !!}. {!! $value['second'] !!}</div>
                                        </div>
                                    @empty
                                    @endforelse
                                @elseif($quiz->type == \App\Models\Quiz::ASSIGNMENT)
                                    @if(isset($question->assignment))
                                        <div>
                                            @foreach($question->getMedia('assignment_attached_file') as $resource)
                                                <a href="{{asset($resource->getUrl())}}"  class="text-info">
                                                    <i class="fas fa-file"></i> {{ $resource->file_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                @endif                         
                                </div>
                            </td>
                            <td width="150">{!! $question->description ?? '' !!}</td>
                            @if($quiz->type != \App\Models\Quiz::ASSIGNMENT)
                                <td>
                                    @foreach($question->getMedia('question_attached_file') as $resource)
                                        <a href="{{asset($resource->getUrl())}}"  class="">{{ $resource->file_name }}</a>
                                        ({{ $resource->human_readable_size }})
                                    @endforeach
                                </td>
                            @endif
                            <td class="text-center table-options">
                                <div class="btn-group btn-small">
                                
                                    @if ($canEdit)
                                        @if($quiz->type == \App\Models\Quiz::ASSIGNMENT)
                                            <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.assignment.detail', [$question->assignment->id]) }}" data-provide="tooltip"
                                                title="@lang('View Submitted Assignments')"><i class="fas fa-eye"></i></a>
                                        @endif
                                        
                                        @if($quiz->type == \App\Models\Quiz::LONG_QUESTION && isset($question->long_answer) )
                                        
                                            <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.long_answer.detail', [$question->long_answer->id, $course->id]) }}" data-provide="tooltip"
                                                title="@lang('View Submitted Long Answers')"><i class="fas fa-eye"></i></a>
                                        @endif
                                        <a class="btn pr-2 pl-2 btn-outline text-info" href="{{ route('member.question.edit', [$question->id]) }}" data-provide="tooltip"
                                            title="Edit"><i class="fas fa-edit"></i></a>
                                        {{-- @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course))
                                            <span class="tooltip-info" data-toggle="tooltip" data-placement="right" 
                                                title="@lang('Cannot delete as the course already had course takers')">
                                                <span class="btn pr-2 pl-1 btn-outline disabled text-danger">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            </span>
                                        @else --}}
                                            {!! Form::open(array('route' => array('member.question.destroy', $question->id), 'method' => 'delete'
                                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                                <button data-provide="tooltip" style="cursor: pointer" data-toggle="tooltip" title="Delete" type="submit" 
                                                    class="btn pr-2 pl-2 btn-outline text-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            {!! Form::close() !!}
                                        {{-- @endif --}}
                                    @endif
                               
                                </div>
                            </td>
                        </tr>
                     @endforeach
                </tbody>
            </table>
        </div>
    </td>
</tr>
@endif
@empty
    <tr>
        <td colspan="6"><div class="text-center">@lang('No records.')</div></td>
    </tr>
@endforelse

            