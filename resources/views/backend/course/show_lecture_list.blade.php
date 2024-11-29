<div class="card">
    <header class="card-header">
        <h4 class="card-title">
            @can('add_lecture')
            <a href="{{route('admin.lecture.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a>
            @endcan
        </h4>
    </header>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-vcenter dataTable no-footer" style="width: 100%;">
            <thead>
                <tr>
                    <th>@lang('No#')</th>
                    <th>@lang('Lecture Title')</th>
                    <th>@lang('Resource Link')</th>
                    <th>@lang('Attached File')</th>
                    <th>@lang('Description')</th>
                    <th>@lang('Uploaded By')</th>
                    <th>@lang('Created At')</th>
                    <th width="150" class="text-center">@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lectures as $key => $lecture)
                <tr>
                    <td width="10">{{ $key +1 }}</td>
                    <td width="200">{{ $lecture->lecture_title }}</td>
                    <td width="200">{{ $lecture->resource_link }}</td>
                    <td width="100">
                        @foreach($lecture->getMedia('lecture_attached_file') as $resource)
                            <a href="{{asset($resource->getUrl())}}" target="_blank"  class="">{{ $resource->file_name }}</a>
                            ({{ $resource->human_readable_size }})
                            {{--@if($resource->mime_type == 'application/pdf')
                                PDF  
                                ({{ $resource->human_readable_size }})
                            @elseif($resource->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
                                PPT  
                                ({{ $resource->human_readable_size }})
                            @elseif($resource->mime_type == 'audio/mpeg')
                                MP3
                                ({{ $resource->human_readable_size }})
                            @elseif($resource->mime_type == 'video/mp4')
                                MP4
                                ({{ $resource->human_readable_size }})
                            @else
                                Other
                            @endif --}}
                        @endforeach
                    </td>
                    <td width="250">{!! $lecture->description !!}</td>
                    <td>{{ $lecture->user->name ?? '' }}</td>
                    <td>{{ $lecture->created_at ?? '' }} </td>
                    <td class="text-right table-options">
                        <div class="btn-group btn-small">
                                {{--<a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.lecture.show', $course->id, $lecture->id) }}" data-provide="tooltip"
                                        title="Show"><i
                                        class="ti-eye"></i></a>--}}
                                @can('edit_lecture')
                                <a class="btn pr-2 pl-2 btn-outline" href="{{ route('admin.lecture.edit', [$lecture->id]) }}" data-provide="tooltip"
                                    title="Edit"><i class="ti-pencil"></i>  </a>
                                @endcan
                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                @can('delete_lecture')
                                {!! Form::open(array('route' => array('admin.lecture.destroy', $lecture->id), 'method' => 'delete'
                                , 'onsubmit' => 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
                                <button data-provide="tooltip" style="cursor: pointer; width: 100%;" data-toggle="tooltip" title="Delete" type="submit" class="dropdown-item text-danger">
                                <i class="ti-trash"></i>
                                {{ __('Delete') }}
                                </button>
                                {!! Form::close() !!}
                                @endcan
                                <a class="btn pr-2 pl-2 ml-1 btn-outline dropdown-item" href="{{route('admin.quiz.create', $course->id) . '?lecture_id=' . $lecture->id}}" data-provide="tooltip" title="New Quiz"><i class="ti-plus"></i> {{ __('New Quiz') }} </a>
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