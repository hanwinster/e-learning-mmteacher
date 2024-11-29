<div class="card">
    <header class="card-header bg-white">
        <h4 class="card-title">
            @if(!\App\Repositories\CoursePermissionRepository::canAdd($course))
                <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                        title="@lang('Cannot create new as the course owner locked it')">
                        <a class="btn btn-secondary disabled text-white pull-right">
                    {{__('New')}}
                </a></span>
            @else
            
                <a href="{{route('member.lecture.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a>
            @endif
             
        </h4>
    </header>
     <div class="card-body table-responsive">
        <table class="table table-bordered no-footer table-top-border">
        <thead>
            <tr>
                <th>@lang('No#')</th>
                <th>@lang('Lecture Title')</th>
                <th>@lang('Description')</th>
                <!-- <th>@lang('Resource Link')</th> -->
                <th>@lang('Attached File/Embedded Video')</th>
                <th>@lang('Uploaded By')</th>
                <th>@lang('Created At')</th>
                <th width="150" class="text-center">@lang('Actions')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lectures as $key => $lecture)
            <tr>
                <td width="10">{{ $key + 1 }}</td>
                <td>{!! $lecture->lecture_title !!}</td>
                <td>
                    <span class="tooltip-info" data-toggle="tooltip" data-placement="right" 
                        title="{!! strip_tags($lecture->description) !!}">
                        {!! str_limit(strip_tags($lecture->description),200,'...') !!}
                    </span>                   
                </td>
                <!-- <td>
                    @if(isset($lecture->resource_link) && !empty($lecture->resource_link))
                        <a href="{{ $lecture->resource_link }}" target="_blank">{{ $lecture->resource_link }}</a>
                    @else
                        - 
                    @endif
                </td> -->
                <td>
                    @php 
                        $attachments =  $lecture->getMedia('lecture_attached_file');
                    @endphp
                    @if($lecture->resource_type == 'embed_video')
                        <a href="{{$lecture->video_link}}" target="_blank">{{$lecture->video_link}}</a>
                    @elseif($lecture->resource_type == 'attach_file' && count($attachments))
                        @foreach($attachments as $resource)
                            <a href="{{asset($resource->getUrl())}}" target="_blank" class="">{{ $resource->file_name }} </a>
                                    ({{ $resource->human_readable_size }})                         
                        @endforeach
                    @endif
                </td>               
                <td>{{ $lecture->user->name ?? '' }}</td>
                <td>{{ $lecture->created_at ?? '' }} </td>
                <td class="text-right table-options">
                    <div class="btn-group btn-small"> 
                    {{-- @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course)) --}}
                        <!-- <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                            title="@lang('Cannot edit or delete as the course already had course takers')">
                            <a class="btn pr-2 pl-2 btn-outline disabled">
                                <i class="fas fa-edit"></i> {{-- __('Edit') --}} 
                            </a>
                        </span> -->
                    {{--@else--}}
                        @if ($canEdit)
                            <a class="btn pr-2 pl-2 btn-outline text-info" href="{{ route('member.lecture.edit', [$lecture->id]) }}" 
                                data-provide="tooltip" title="Edit">
                                <i class="fas fa-edit"></i> {{ __('Edit') }} 
                            </a>
                            <button type="button" class="btn btn-small dropdown-toggle dropdown-toggle-split pr-3 pl-2" 
                                data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course))
                                    <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Cannot delete as the course already had course takers')">
                                        <span class="btn pr-2 pl-1 btn-outline disabled text-danger">
                                            <i class="fas fa-trash"></i>&nbsp;{{ __('Delete') }}
                                        </span>
                                    </span>
                                   
                                @else 
                                    {!! Form::open(array('route' => array('member.lecture.destroy', $lecture->id), 'method' => 'delete'
                                    , 'onsubmit' => 'return confirm("Are you sure you want to delete the lecture and all the sections attached to it?");', 'style' => 'display: inline', '')) !!}
                                    <button data-provide="tooltip" data-toggle="tooltip" title="Delete" 
                                        type="submit" class="btn pr-2 pl-1 btn-outline text-danger dropdown-delete"><i class="fas fa-trash"></i>
                                        &nbsp;{{ __('Delete') }}
                                    </button>
                                    {!! Form::close() !!}                               
                                @endif
                                @if(!\App\Repositories\CoursePermissionRepository::canAdd($course))
                                    <span class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Cannot create new as the course owner locked it')">
                                        <span class="btn pr-2 pl-1 btn-outline disabled">
                                            <i class="fas fa-plus"></i> {{ __('New Quiz') }}
                                        </span>
                                    </span> 
                                @else                                  
                                    <a class="btn pr-2 pl-1 btn-outline text-success" data-provide="tooltip" title="New Quiz" class="dropdown-item"
                                        href="{{ route('member.quiz.create', [$course->id]).'?lecture_id='. $lecture->id}}" >
                                    <i class="fas fa-plus"></i> {{ __('New Quiz') }} </a>   
                                @endif
                            </div>
                        @endif
                    {{--@endif--}}
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="7"><div class="text-center">{{ __('No records.') }}</div></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>