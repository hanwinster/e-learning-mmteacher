<div class="card">
    <header class="card-header">
        <h4 class="card-title">
            @can('add_assignment')
            <a href="{{route('admin.assignment.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a>
            @endcan
        </h4>
    </header>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-vcenter dataTable no-footer" style="width: 100%;">
            <thead>
                <tr>
                    <th width="60">@lang('No#')</th>
                    <th>@lang('Assignment Title')</th>
                    <th>@lang('Assignment Instruction')</th>
                    <th>@lang('Attached File') </th>
                    <th>@lang('Created At')</th>
                    <th width="150" class="text-center">@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $key => $assignment)
                <tr>
                    <td>{{ $key +1 }}</td>
                    <td>{{ $assignment->title }}</td>
                    <td>{!! $assignment->description !!}</td>
                    <td>
                        @foreach($assignment->getMedia('assignment_attached_file') as $resource)
                        <a href="{{asset($resource->getUrl())}}"  class="">{{ $resource->file_name }}</a>
                        ({{ $resource->human_readable_size }})
                        @endforeach
                    </td>
                    <td>{{ $assignment->created_at ?? '' }} </td>
                    <td class="text-right table-options">
                        <div class="btn-group btn-small">
                            <a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.assignment.detail', [$assignment->id]) }}" data-provide="tooltip" title="{{__('View User\'s Assignments')}}"><i class="ti-eye"></i></a>
                            {{--<a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.assignment.show', [$assignment->id]) }}" data-provide="tooltip"
                                        title="Show"><i
                                        class="ti-eye"></i></a>--}}
                                @can('edit_assignment')
                                <a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.assignment.edit', [$assignment->id]) }}" data-provide="tooltip"
                                    title="Edit"><i class="ti-pencil"></i>  </a>
                                @endcan
                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                @can('delete_assignment')
                                {!! Form::open(array('route' => array('admin.assignment.destroy', $assignment->id), 'method' => 'delete'
                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                                <i class="ti-trash"></i>
                                {{ __('Delete') }}
                                </button>
                                {!! Form::close() !!}
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6"><div class="text-center">No records.</div></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>