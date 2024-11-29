@forelse($summaries as $key => $summary)
    @if($summary->lecture_id == $lectureId)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{!! strip_tags($summary->title) !!}</td>
            <td>@php
                if($summary->lecture_id) {
                    $lecture = App\Repositories\LectureRepository::findById($summary->lecture_id);
                }
                @endphp
                @if(!$summary->lecture_id)
                    {!! strip_tags($course->title) !!}
                @else
                    {!! strip_tags($lecture->lecture_title) !!}
                @endif
            </td>
            <td>{{ $summary->created_at ?? '' }} </td>
            <td class="text-right table-options">
                <div class="btn-group btn-small">
                    
                        @if($canEdit)
                            <a class="btn pr-2 pl-2 btn-outline text-info" href="{{ route('member.summary.edit', [$summary->id]) }}" 
                                data-provide="tooltip" title="Edit"><i class="fas fa-edit"></i>&nbsp;{{ __('Edit') }}</a>

                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                            @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course))
                                <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Cannot delete as the course already had course takers')">
                                    <a class="btn pr-2 pl-2 btn-outline disabled">
                                        <i class="fas fa-trash text-danger disabled"></i>&nbsp;{{ __('Delete') }}
                                    </a>
                                </span>
                            @else
                                {!! Form::open(array('route' => array('member.summary.destroy', $summary->id), 'method' => 'delete'
                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" title="Delete" 
                                    type="submit" class="dropdown-item text-danger dropdown-delete"> <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                </button>
                                
                                {!! Form::close() !!}
                            @endif
                            </div>
                        @endif
                </div>
            </td>
        </tr>
    
    @endif

@empty
<tr>
    <td colspan="6">
        <div class="text-center">@lang('No records.')</div>
    </td>
</tr>
@endforelse