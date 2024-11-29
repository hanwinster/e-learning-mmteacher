<div class="row">
    <div class="col-12 mt-4">
        @php $slug = strip_tags($course->slug); @endphp
        <div class="row">
            <div class="col-4">
                <a href="{{ route('courses.show', $slug) }}" class="pull-right" style="text-decoration: underline;">
                    @lang('Preview Course')
                </a>
            </div>
            <div class="col-8 text-right">
                <span class="text-right">
                    <span class="text-danger">@lang('Created By')</span> - 
                    {{ \App\Repositories\UserRepository::getUserNameById($course->user_id) }},&nbsp;
                    <span class="text-danger">   @lang('Last Modified By')</span> - 
                    {{ \App\Repositories\UserRepository::getUserNameById($course->last_modified_by) }}
                </span>
            </div>
        </div>
        <table class="table no-footer" style="border: none;">
            <tr>
                <td>@lang('Title')</td>
                <td>{{ strip_tags($course->title) }}</td>
            </tr>
            <tr>
                <td>@lang('Cover Image')</td>
                <td>
                    @if ($img_url = $course->getThumbnailPath())
                    <!-- {{$course->getThumbnailPath() }} -->
                    <!-- @php
                    $images = $course->getMedia('course_cover_image');
                    @endphp
                    @foreach($images as $image) -->
                        <img src="{{ asset($course->getThumbnailPath()) }}" class="thumb-img">
                    <!-- @endforeach -->
                    @else
                    -
                    @endif
                </td>
            </tr>
            <tr>
                <td>@lang('Resource File')</td>
                <td>
                    @foreach($course->getMedia('course_resource_file') as $resource)
                    <a href="{{asset($resource->getUrl())}}" class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>@lang('Course Categories')</td>
                <td>
                    @foreach($course->course_categories as $idx=>$category)
                        {{ __($categories[$category]) }} 
                        @if($idx !== count($course->course_categories) -1 ) , @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>@lang('Course Level')</td>
                <td>{{ ($course->course_level_id && isset($course->course_level_id, $levels))? $levels[$course->course_level_id] : '' }}</td>
            </tr>
            <tr> 
                <td>@lang('Course Type')</td>
                <td>{{ ($course->course_type_id && isset($course->course_type_id, $types))? $types[$course->course_type_id] : '' }}</td>
            </tr> 
            <tr>
                <td>@lang('Objective')</td>
                <td>{!! $course->objective !!}</td>
            </tr>
            <tr>
                <td>@lang('Description')</td>
                <td>{!! $course->description !!}</td>
            </tr>
            <tr>
                <td>@lang('Learning Outcome')</td>
                <td>{!! $course->learning_outcome !!}</td>
            </tr>
            <tr>
                <td>@lang('Will display a featured video in course detail?')</td>
                <td>@if($course->is_display_video == 1)
                        @lang('Yes')
                    @else
                        @lang('No')
                    @endif</td>
            </tr>
            <tr>
                <td>@lang('Video Link')</td>
                <td>
                    @if(!empty($course->video_link))
                        <a href="{{ $course->video_link }}"> {{ $course->video_link }} </a>
                    @else -
                    @endif
                </td>
            </tr>
            <tr>
                <td>@lang('Url Link')</td>
                <td>
                    @if(!empty($course->url_link))
                        <a href="{{ $course->url_link }}"> {{ $course->url_link }} </a>
                    @else -
                    @endif
                </td>
            </tr>
            <tr>
                <td>@lang('Downloadable Option')</td>
                <td>
                    {{ \App\Models\Course::DOWNLOADABLE_OPTIONS[$course->downloadable_option] }}
                </td>
            </tr>
            <tr>
                <td>@lang('Estimated Duration') ?</td>
                <td>
                    {{ $course->estimated_duration }}&nbsp;
                    {{ $course->estimated_duration_unit }}
                </td>
            </tr>
            <tr>
                <td>@lang('Grace Period(in days) To Notify Course Takers Who did not finish within estimated duration') ?</td>
                <td>
                    {{ $course->grace_period_to_notify }}
                </td>
            </tr>
            <tr>
                <td>@lang('Language')</td>
                <td>
                    {{ __( \App\Models\Course::LANGUAGES[$course->lang] ) }}
                </td>
            </tr>
            <tr>
                <td>@lang('Allow Feedback') ?</td>
                <td>
                    @if($course->allow_feedback) @lang('Yes') @else @lang('No') @endif
                </td>
            </tr>
            <tr>
                <td>@lang('Allow Discussion') ?</td>
                <td>
                    @if($course->allow_discussion)  @lang('Yes') @else @lang('No') @endif
                </td>
            </tr>
            <tr>
                <td>@lang('Is Published') ?</td>
                <td>
                    @if($course->is_published)  @lang('Yes') @else @lang('No') @endif
                </td>
            </tr>
            <tr>
                <td>@lang('Allow Edit') ?</td>
                <td>
                    @if($course->allow_edit)  @lang('Yes') @else @lang('No') @endif
                </td>
            </tr>
            
           
            <tr>
                <td>@lang('Is Locked') ?</td>
                <td>
                    @if($course->is_locked)  @lang('Yes') @else @lang('No') @endif
                </td>
            </tr>
            <tr>
                <td>@lang('Created At')</td>
                <td>
                    {{ $course->created_at ?? '' }}
                </td>
            </tr>
            <tr>
                <td>@lang('Accessible Right')</td>
                <td>
                    @foreach($course->privacies as $privacy)
                        {{ ($privacy->user_type && array_key_exists($privacy->user_type, $userTypes))? $userTypes[$privacy->user_type] : '' }}
                        @if(!$loop->last)
                            ,
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>@lang('Course Collaborators')</td>
                <td>
                    @if($course->collaborators && count($course->collaborators))
                        @foreach($course->collaborators as $cc)
                            {{ \App\Repositories\UserRepository::getUserNameById($cc) }}@if(!$loop->last) , @endif
                        @endforeach
                    @else 
                        @lang("N/A")
                    @endif
                </td>
            </tr>
            <tr>
                <td>@lang('Keywords for Related Resources')</td>
                <td>
                    @if($course->related_resources)
                        {{$course->related_resources}}
                    @else 
                        @lang("N/A")
                    @endif
                </td>
            </tr>
        </table>
        <div class="px-3">
            @if($canEdit)
                <a href="{{route('member.course.edit', $course->id)}}" class="btn btn-primary btn-sm">@lang('Edit')</a>
            @endif
        </div>
    </div>
</div>