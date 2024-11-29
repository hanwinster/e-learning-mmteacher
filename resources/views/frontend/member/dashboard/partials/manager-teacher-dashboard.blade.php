
<div class="row">
    @if( auth()->user()->isManager() )
        @if(auth()->user()->is_unesco_mgr)
            <card title="{{__('Courses')}}" count-data=" {{ App\Models\Course::count() }}" grid="col-md-3" 
                card-tooltip=" @lang('Total courses in the system') " card-border-color="card-primary"></card>
        @else
            <card title="{{__('Courses')}}" count-data=" {{ $totalCourses }}" grid="col-md-3" 
                card-tooltip=" @lang('Total courses from the same education college') " card-border-color="card-primary"></card>
        @endif
    @else
        <card title="{{__('My Courses')}}" count-data=" {{ $totalCourses }}" grid="col-md-3" card-border-color="card-primary"></card>
    @endif
    <!-- <card title="{{ __('Favourites') }}" count-data="{{ $totalFavourites }}" grid="col-md-3" card-border-color="card-success"></card> -->
    <card title="{{ __('Total Notifications') }}" count-data="{{ $totalNotifications }}" grid="col-md-3" card-border-color="card-warning"></card>
    @if( auth()->user()->isManager() )
        @if(auth()->user()->is_unesco_mgr)
            <card title="{{__('Users')}}" count-data="{{ App\User::count() }}" grid="col-md-3" card-border-color="card-danger"></card>
        @else 
            <card title="{{__('Users')}}" count-data="{{ $totalUsersFromSameEC }}" grid="col-md-3" 
                card-tooltip=" @lang('Total users from the same education college') " card-border-color="card-danger"></card>
        @endif
    @endif
</div>

<div class="row">
    <div class="col-12"> 
        @if ( \Session::has('warning') || \Session::has('error') || \Session::has('success'))
            @include('layouts.form_alert')                                       
        @endif
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">
                    {{ __('Course Details') }}&nbsp;
                </h4>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                         <i class="fas fa-minus"></i>
                    </button>
                     <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                    
            </div>
            <div class="card-body table-responsive">
                <table id="course-list" class="table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('No.')</th>
                            <th style="max-width:10rem;">@lang('Title')</th>
                            <th>@lang('Enrollments')</th>
                            <th>@lang('Progress')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Participants With Progress')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th>@lang('Certificates')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Total number of certificates generated')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th style="min-width:6rem;">@lang('Views')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Total number of views for the course')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th style="min-width:10rem;">@lang('Comments')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Total Number of feedbacks for the course')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th class="text-center">@lang('Rating')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Rating By Course Takers')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $key => $data)                   
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ strip_tags($data->title) }}</td>
                                <td>{{ $data->learners->count()  }}</td>
                                <td>
                                    @if(count($data->learners) > 0)
                                        @foreach($data->learners as $key => $value)
                                            @php 
                                                if($value->user_id) {
                                                   $user = \App\User::getUserById($value->user_id); 
                                                }
                                            @endphp
                                            @if(isset($user) && $user)
                                                {{ $user->name }}&nbsp;-&nbsp;
                                                <small class="text-primary">{{ $value->percentage }}%</small><br>
                                            @else
                                                -
                                            @endif
                                            
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if( $data->getCourseType($data->course_type_id)->name == 'Certified' )
                                        {{ \App\Repositories\CourseLearnerRepository::getTotalCertificatesForCourse($data->id) }}
                                    @else
                                        @lang('N/A - Non-certified Course') 
                                    @endif
                                </td> 
                                <td>{{ $data->view_count  }}</td>
                                <td>
                                    @php
                                        $ratings = $data->ratingReviews;
                                        $finalRating = 0; $ratingCount = 0;
                                        if(count($ratings) > 1) {
                                            $sum = 0;
                                            for($i=0; $i < count($ratings); $i++) {
                                                $sum += $ratings[$i]->rating;
                                                $ratingCount++;
                                            }
                                            $finalRating = ceil($sum/$ratingCount);
                                        } else {
                                            $finalRating = isset($ratings[0]) ? $ratings[0]->rating : $finalRating;
                                            $ratingCount = isset($ratings[0]) ? 1 : 0;
                                        }
                                    @endphp
                                    {{ $ratingCount }}
                                </td> 
                                <td>{{ $finalRating  }}</td>
                            </tr>
                        @endforeach 
                    </tbody>
                </table>
            </div>
            <footer class="card-footer text-center">
                {{ $courses->links() }}
            </footer>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">
                    {{ __('List of Course Takers To Notify') }}&nbsp;
                    <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="right" 
                        title="@lang('This is the list of course takers who did not complete the course within the estimated duration and grace period!')">
                        <i class="fas fa-info-circle"></i>
                    </span>
                </h4>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                         <i class="fas fa-minus"></i>
                    </button>
                     <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                    
            </div>
            <div class="card-body table-responsive">
                
                <table id="notify-list" class="table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('No.')</th>
                            <th>@lang('Username')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Course Title')</th>
                            <th style="min-width:6rem;">@lang('Days')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="right" 
                                    title="@lang('Days Exceeding the Expected Time To Finish A Course and Grace Period')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th style="min-width:6rem;">@lang('Date')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="right" 
                                    title="@lang('The Date When The Course Was Taken')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th style="min-width:10rem;">@lang('Notifications')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="right" 
                                    title="@lang('Total Notification Sent')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifyList as $key => $data)                   
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ $data['username'] }}</td>
                                <td>{{ $data['email']  }}</td>
                                <td>{{ strip_tags($data['course_title'])  }}</td>
                                <td>{{ $data['overPeriod']  }}</td> 
                                <td>{{ $data['created_at']  }}</td>
                                <td>{{ $data['notify_count']  }}</td> 
                                <td>
                                    @php 
                                        $dataToPass = $data['user_id'].'-'.$data['course_learner_id'].'-'.strip_tags($data['course_title']); 
                                        $courseLearnerId = $data['course_learner_id'];
                                    @endphp
                                    <input type="hidden" name="data_for_tdialog[]" id="data-for-tdialog-{{$key}}" value="{{$dataToPass}}" />
                                    <button class="btn btn-warning btn-sm mb-1" data-toggle="modal" onclick="sendNotification({{$key}});"
                                            data-target="#modal-noti-email-t">@lang('Send Email') 
                                    </button>&nbsp;
                                    <button class="btn btn-danger btn-sm " data-toggle="modal" onclick="removeUserFromCourse({{ $courseLearnerId }})"
                                        data-target="#modal-drop-user" value="{{ $data['course_learner_id'] }}">@lang('Drop User')
                                    </button>
                                </td>
                            </tr>
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-noti-email-t">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('member.dashboard.notify-user') }}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Sending an email to the course taker to complete the course')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">             
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <label>@lang('Subject')&nbsp;
                                    <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="noti_subject" 
                                    id="noti-subject-t" required/>                             
                            </div>
                            <div class="form-floating">
                                <label>@lang('Message')&nbsp;
                                    <span class="required">*</span>
                                </label>
                                <textarea class="form-control" name="noti_message" rows="4" cols="30" 
                                    id="noti-message-t" required></textarea>                             
                            </div>
                        </div>
                    </div>              
                </div>
                <div class="modal-footer justify-content-between">         
                    <input type="hidden" name="user_id" id="user-id-t" value="" />      
                    <button type="submit" class="btn btn-primary btn-sm" name="btnSend" value="1">@lang('Send')</button>
                    <button type="button" class="btn btn-outline-dark btn-sm" data-dismiss="modal">@lang('Cancel')</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-drop-user">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('member.dashboard.remove-user') }}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Removing the user from the course')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you sure you want to remove this user from the course?')</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" name="remove_user_id" id="remove-user-id" value="" />
                    <button type="submit" class="btn btn-primary btn-sm" name="remove_user_btn" value="1">
                        @lang('Remove')
                    </button>
                    <button type="button" class="btn btn-outline-dark btn-sm" data-dismiss="modal">
                        @lang('Cancel')
                    </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>