<div class="card">
    <div class="card-header bg-white">
        <h4 class="card-title">
            <a href="{{route('member.assignment.create', $course->id)}}" class="btn btn-primary btn-md pull-right">{{__('New')}}</a>
        </h4>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered no-footer table-top-border">
            <tbody>
                <tr>
                    <td>
                        <table class="table table-bordered no-footer quiz_table">
                            <thead>
                                @include('frontend.member.assignment.assignment_header', ['heading' => $course->title])
                            </thead>
                            <tbody>
                                @include('frontend.member.assignment.assignment_row', ['assignments' => $assignments_for_only_course, 'lectureId' => null ])
                            </tbody>
                        </table>
                    </td>
                </tr>
                @php
                    $lectures = \App\Models\Lecture::where('course_id', $course->id)->get();
                @endphp

                @foreach($lectures as $lecture)
                    <tr>
                        <td>
                            <table class="table table-bordered no-footer quiz_table">
                                <thead>
                                    @include('frontend.member.assignment.assignment_header', ['heading' => $lecture->lecture_title])
                                </thead>
                                <tbody>
                                    @include('frontend.member.assignment.assignment_row', ['assignments' => $assignments, 'lectureId' => $lecture->id ] )
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <!-- <table class="table table-bordered no-footer table-top-border">
            <thead>
                <tr>
                    <th width="60">{{__('No')}}#</th>
                    <th>{{__('Assignment Title ')}}</th>
                    <th>{{__('Assignment For')}}</th>
                    <th>{{__('Attached File') }}</th>
                    <th>{{__('Created At')}}</th>
                    <th width="150" class="text-center">{{__('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $key => $assignment)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $assignment->title }}</td>
                    <td>@php
                            if($assignment->lecture_id) {
                                $lecture = App\Repositories\LectureRepository::findById($assignment->lecture_id);
                            }
                        @endphp
                        @if(!$assignment->lecture_id)
                            {{ $course->title }}
                        @else
                            {{ $lecture->lecture_title }}
                        @endif
                    </td>
                    <td>
                        @foreach($assignment->getMedia('assignment_attached_file') as $resource)
                            <a href="{{asset($resource->getUrl())}}" target="_blank"  class="">{{ $resource->file_name }}</a>
                            ({{ $resource->human_readable_size }})
                        @endforeach
                    </td>
                    <td>{{ $assignment->created_at ?? '' }} </td>
                    <td class="text-right table-options">
                        @php
                            $canEditAssignment = App\Repositories\AssignmentRepository::canEdit($assignment);
                        @endphp
                        <div class="btn-group btn-small">
                            
                        @if($canEdit && $canEditAssignment)
                                {{--<a class="btn pr-2 pl-2 btn-outline" href="{{ route('member.assignment.show', [$assignment->id]) }}" data-provide="tooltip"
                                    title="Show"><i class="fas fa-eye"></i>  </a>--}}

                            <a class="btn pr-2 pl-2 btn-outline text-info" href="{{ route('member.assignment.edit', [$assignment->id]) }}" data-provide="tooltip"
                                    title="Edit"><i class="fas fa-edit"></i>&nbsp;{{ __('Edit') }}</a>

                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                {!! Form::open(array('route' => array('member.assignment.destroy', $assignment->id), 'method' => 'delete'
                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}                           
                                <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" title="Delete" type="submit" 
                                    class="dropdown-item text-danger"> <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                </button>
                                <a class="btn pr-2 pl-2 btn-outline text-success" href="{{ route('member.assignment.detail', [$assignment->id]) }}" 
                                    data-provide="tooltip" title="Show"><i class="fas fa-eye"></i>&nbsp;{{__('View Submitted Assignments') }}</a> 
                                {!! Form::close() !!}
                            </div>
                        @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6"><div class="text-center">@lang('No records.')</div></td>
                    </tr>
                @endforelse
            </tbody>
        </table> -->

</div>