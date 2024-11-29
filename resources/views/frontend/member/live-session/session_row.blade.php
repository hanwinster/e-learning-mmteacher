@forelse($sessions as $key => $session)
    @if($session->lecture_id == $lectureId)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $session->topic }}
                @if(isset($session->start_url))
                <br/> 
                    Start URL : <a href="{{$session->start_url}}" target="_blank">@lang('Start Link')</a>
                @endif
                
                @if(isset($session->join_url))
                <br/> 
                    Join URL : <a href="{{$session->join_url}}" target="_blank">@lang('Join Link')</a>
                @endif
            </td>
            <td>{{ $session->start_date }}</td>
            <td>{{ $session->start_time }}</td> 
            <td>{{ $session->created_at ?? '' }} </td>
            <td class="text-right table-options">
                @php
                    $canEditSession = App\Repositories\LiveSessionRepository::canEdit($session);
                @endphp
                <div class="btn-group btn-small">
                    
                        @if($canEdit && $canEditSession)
                            <a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.course.live-session.view', [$session->id] ) }}" 
                                data-provide="tooltip" title="View Registrations"><i class="fas fa-eye"></i> </a>
                            @php 
                                $current = \Carbon\Carbon::now()->toDateString();
                                $sessionDate = changeDateFormatToCarbon($session->start_date);
                                $diffTs =  \Carbon\Carbon::parse($sessionDate)->timestamp - \Carbon\Carbon::parse($current)->timestamp;
                                //echo $diffTs;
                            @endphp
                            @if($diffTs > 0)
                                <a class="btn pr-2 pl-2 btn-outline text-info" href="{{ route('member.course.live-session.edit', [$session->id] ) }}" 
                                    data-provide="tooltip" title="Edit"><i class="fas fa-edit"></i>&nbsp;{{ __('Edit') }}
                                </a>
                            @else 
                                <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Cannot edit as the meeting time is already over')">
                                        <a class="btn pr-2 pl-2 btn-outline  text-secondary disabled">
                                            <i class="fas fa-edit"></i> {{ __('Edit') }} 
                                        </a>
                                </span>
                            @endif

                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" 
                                    aria-haspopup="true" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course))
                                    <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Cannot edit or delete as the course already had course takers')">
                                        <a class="btn pr-2 pl-2 btn-outline  text-danger disabled">
                                            <i class="fas fa-trash"></i> {{ __('Delete') }} 
                                        </a>
                                    </span>
                                @else
                                    {!! Form::open(array('route' => array('member.course.live-session.destroy', $session->id), 'method' => 'delete',
                                            'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                        <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" title="Delete" type="submit" 
                                            class="dropdown-item text-danger"> <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                        </button>
                                        <!-- <a class="btn pr-2 pl-2 btn-outline text-success" href="{{ route('member.course.live-sessions.list', [$session->id]) }}" 
                                                data-provide="tooltip" title="Show"><i class="fas fa-eye"></i>&nbsp;{{__('View Session Participants') }}</a> -->
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
