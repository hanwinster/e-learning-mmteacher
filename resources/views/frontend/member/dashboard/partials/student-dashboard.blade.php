
<div class="row">
    <card title="{{__('My Taken Courses')}}" count-data=" {{ $totalCourses }}" grid="col-md-3" card-border-color="card-primary"></card>
    <!-- <card title="{{ __('My Comments') }}" count-data="{{ $totalFavourites }}" grid="col-md-3" card-border-color="card-success"></card> -->
    <card title="{{ __('Total Notifications') }}" count-data="{{ $totalNotifications }}" grid="col-md-3" card-border-color="card-warning"></card>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">{{ __('My Taken Courses') }}</h4>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="notify-list" class="table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('No.')</th>
                                <th style="max-width:10rem;">@lang('Title')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Progress Percentage')</th>
                                <th>@lang('Certificates')&nbsp;
                                    <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Total number of certificates generated')">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </th>
                                <th style="min-width:6rem;">@lang('Notification Count')&nbsp;
                                    <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                        title="@lang('Total number of notifications sent to complete the course')">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </th>
                                <th style="min-width:10rem;">@lang('View Course Details')&nbsp;</th>                           
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $key => $data)                   
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    @php 
                                      $title = \App\Repositories\CourseRepository::getCourseTitleById($data->course_id);  
                                      $slug = \App\Repositories\CourseRepository::getCourseSlugById($data->course_id);
                                      $lectureUuid = \App\Repositories\LectureRepository::getLectureUuidByCourseId($data->course_id); 
                                    @endphp
                                    <td>{{ strip_tags($title[0]) }}</td>
                                    <td>{{ __(\App\Models\CourseLearner::COURSE_LEARNER_STATUSES[$data->status])  }}</td>
                                    <td>                              
                                        {{ $data->percentage }}%                                        
                                    </td>
                                    <td>
                                        {{$data->certificate_count }}
                                    </td>
                                    <td>
                                        {{$data->notify_count }} {{$data->is_published}}
                                    </td> 
                                    <td>
                                        @php 
                                            $lastVisited = \App\Repositories\CourseLearnerRepository::getlastVisited($data->course_id, auth()->user()->id);
                                            $isCoursePublished = \App\Repositories\CourseRepository::isCoursePublished($data->course_id);
                                        @endphp
                                        @if($isCoursePublished)
                                            <a href="{{$lastVisited}}">
                                                {{ strip_tags($title[0]) }}
                                            </a>
                                        @else 
                                            <span class="tooltip-info text-secondary" data-toggle="tooltip" data-placement="right" 
                                                title="@lang('This course is currently unpublished')">
                                                <span>{{ strip_tags($title[0]) }}</span>
                                            </span>
                                        @endif
                                    </td>
                                   
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
            </div>
            <div class="card-footer text-center">
                {{ $courses->links() }}
            </div>
            
        </div>
    </div>
</div>