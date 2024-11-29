<div class="row">
    
    <card title="{{__('Courses')}}" count-data=" {{ count($totalCoursesUnescoMgr) }}" grid="col-md-3" 
        card-tooltip=" @lang('Total courses in the system') " card-border-color="card-primary"></card>
    <card title="{{ __('Total Notifications') }}" count-data="{{ $totalNotifications }}" grid="col-md-3" card-border-color="card-warning"></card>
    <card title="{{__('Users')}}" count-data="{{ $totalUsers }}" grid="col-md-3" card-border-color="card-danger"></card>          
</div>
@if ( \Session::has('warning') || \Session::has('error') || \Session::has('success'))
    @include('layouts.form_alert')                                       
@endif

<!-- Signups & Gender -->
<div class="row">
    <!-- Signups -->
    <div class="col-md-12 col-lg-9 col-xl-8">
        <div class="card card-outline card-info">
            <div class="card-header signup">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-2">
                        <h4 class="card-title" style="padding-top:10px">{{ __('Signups') }}</h4>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="form-group">
                            <!-- <label>{{ __('Date') }}:</label> -->
                            <div class="input-group date" id="signup-start-date-mgr" data-target-input="nearest">
                                <input id="signup-start-date-btn-mgr" type="text" class="form-control datetimepicker-input" placeholder="{{ __('Start Date') }}" 
                                data-target="#signup-start-date-mgr" value="{{$startDate}}" required />
                                <div class="input-group-append" data-target="#signup-start-date-mgr" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="form-group">
                            <!-- <label>{{ __('Date') }}:</label> -->
                            <div class="input-group date" id="signup-end-date-mgr" data-target-input="nearest">
                                <input id="signup-end-date-btn-mgr" type="text" class="form-control datetimepicker-input" placeholder="{{ __('End Date') }}"
                                 data-target="#signup-end-date-mgr" value="{{$endDate}}" required />
                                <div class="input-group-append" data-target="#signup-end-date-mgr" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <button id="searchSignupDataMgr" class="btn btn-secondary btn-sm" style="height:36px;">
                            <i class="fa fa-search"></i>
                        </button>&nbsp;
                        <form action="{{ route('member.exportSignups') }}" method="POST" style="display:inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            &nbsp;&nbsp;
                            <button type="submit" id="downloadSignupsUnescoMgr" class="btn btn-secondary btn-sm" style="height:36px;">
                                <!-- {{ __('Download') }}&nbsp;--><i class="fa fa-download"></i>
                            </button>
                            <input type="hidden" name="startDate" value="{{ $startDate }}">
                            <input type="hidden" name="endDate" value="{{ $endDate }}">
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Line Chart -->
                <div class="chart">
                    <canvas id="signup-chart" style="min-height: 250px; height: 250px; 
                                    max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <div class="row text-center d-spinner d-none">
                    <div class="col-12">
                        <div class="fa-5x"><i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Gender -->
    <div class="col-md-12 col-lg-3 col-xl-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">{{ __('Gender Ratio') }}</h4>&nbsp;
                <button type="button" name="gender_select_umgr" class="btn btn-secondary up-half-rem" id="gender-select-umgr">
                    <i class="far fa-calendar-alt"></i>&nbsp;{{__('Select Date')}}
                    <i class="fas fa-caret-down"></i>
                </button>
                 
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Donut Chart -->
                <canvas id="gender-donut-chart" style="min-height: 266px; height: 266px; 
                                        max-height: 266px; max-width: 100%; display: block;" height="312" class="chartjs-render-monitor"></canvas>
                <div class="row text-center d-spinner-gender d-none">
                    <div class="col-12">
                        <div class="fa-5x"><i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of Signups & Gender -->

<!-- Course Details -->
<div class="row">
    <div class="col-12">
        
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
                <table id="db-course-table-umgr" class="table table-hover">
                    <thead> 
                        <tr>
                            <th>@lang('No')</th>
                            <th>@lang('Title')</th>
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
                            <th>@lang('Views')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Total number of views for the course')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <!-- <th>@lang('Comments')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Total Number of feedbacks for the course')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th> -->
                            <th>@lang('Rating')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Rating By Course Takers')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th>@lang('Uploaded By')</th>
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
                                        0
                                        <!-- @lang('N/A - Non-certified Course')  -->
                                    @endif
                                </td> 
                                <td>{{ $data->view_count  }}</td>
                                <!-- <td> -->
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
                                    <!-- {{ $ratingCount }}
                                </td>  -->
                                <td>{{ $finalRating  }}</td>
                                <td>
                                    @php 
                                        $ownerName = "-";
                                        if($data->user_id) {
                                            $owner = \App\User::getUserById($data->user_id); 
                                            $ownerName = $owner->name;
                                        }
                                    @endphp
                                    {{ $ownerName }}
                                </td>
                            </tr>
                        @endforeach 
                    </tbody>
                    <tfoot> 
                        <tr>
                            <th>@lang('No')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Enrollments')</th>
                            <th>@lang('Progress')</th>
                            <th>@lang('Certificates')</th>
                            <th>@lang('Views')</th>
                            <th>@lang('Comments')</th>
                            <th class="text-center">@lang('Rating')</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- <footer class="card-footer text-center">
                {{-- $courses->links() --}}
            </footer> -->
        </div>
    </div>
</div>
<!-- end of Course Details -->

<!-- List of Course Takers To Notify -->
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">
                    {{ __('List of Course Takers To Notify') }}&nbsp;
                    <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="right" 
                        title="@lang('This is the list of course takers who did not complete the course 
                                        within the estimated duration and grace period!')">
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
                
                <table id="notify-table-umgr" class="table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('No')</th>
                            <th>@lang('Username')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Course Title')</th>
                            <th style="min-width:6rem;">@lang('Days')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Days Exceeding the Expected Time To Finish A Course and Grace Period')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th style="min-width:6rem;">@lang('Date')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('The Date When The Course Was Taken')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th style="min-width:10rem;">@lang('Notifications')&nbsp;
                                <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="top" 
                                    title="@lang('Total Notification Sent')">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </th>
                            <th class="text-center">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($notifyList) > 0)
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
                                        <input type="hidden" name="data_for_dialog[]" id="data-for-dialog-{{$key}}" value="{{$dataToPass}}" />
                                        <button class="btn btn-warning btn-sm  mb-1" data-toggle="modal" onclick="sendNotification({{$key}});"
                                            data-target="#modal-noti-email" value="{{ $dataToPass }}"
                                            >@lang('Send Email') 
                                        </button>&nbsp;
                                        <button class="btn btn-danger btn-sm " data-toggle="modal" onclick="removeUserFromCourse({{ $courseLearnerId }})"
                                            data-target="#modal-drop-user" value="{{ $data['course_learner_id'] }}">@lang('Drop User')
                                        </button>
                                    </td>
                                </tr>
                            @endforeach 
                        @else
                            <tr>
                                <td colspan="8">@lang('No Users To Notify Yet')</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end of List of Course Takers To Notify -->

<!-- Visitors Per Role -->
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">@lang('Visitors Per Role')
                    &nbsp;
                    <!-- <a class="btn btn-secondary btn-sm " onclick="selectContents( document.getElementById('visitors') );">
                        @lang('Select Table Data To Copy')
                    </a> -->
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
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="visitors-table-umgr" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>@lang('No')</th>
                            <th>@lang('Role')</th>
                            <th>@lang('Total Users')</th>
                            <th>@lang('Total Users Visited In Last Year')</th>
                            <th>@lang('Percentage')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitorsPerRole as $key => $data)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$data->user_type}}</td>
                                <td>{{$data->total}}</td>
                                <td>{{$data->totalVisitors}}</td>
                                <td>{{ round($data->percentage,2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<!-- end of Visitors Per Role -->

<!-- EDC Statistics -->
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">@lang('EDC Statistics')
                    &nbsp;
                    <!-- <a class="btn btn-secondary btn-sm " onclick="selectContents( document.getElementById('edc') );">
                        @lang('Select Table Data To Copy')
                    </a> -->
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
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="edc-table-umgr" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>@lang('No')</th>
                            <th>@lang('EDC')</th>
                            <th>@lang('Total Users')</th>
                            <th>@lang('Total Teachers')</th>
                            <th>@lang('Total Students')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usersPerEc as $key => $data)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$data->ec_name}}</td>
                            <td>{{$data->total}}</td>
                            <td>{{$data->teachers}}</td>
                            <td>{{$data->students}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<!-- end of EDC Statistics -->

<!-- Visitors From EDC -->
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">@lang('Visitors From EDC ')
                    &nbsp;
                    <!-- <a class="btn btn-secondary btn-sm " onclick="selectContents( document.getElementById('visitors') );">
                        @lang('Select Table Data To Copy')
                    </a> -->
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
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="visitors-table-umgr" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>@lang('No')</th>
                            <th>@lang('EDC')</th>
                            <th>@lang('Total Users')</th>
                            <th>@lang('Total Users Visited In Last Year')</th>
                            <th>@lang('Percentage')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitorsPerEc as $key => $data)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$data->ec_name}}</td>
                                <td>{{$data->total}}</td>
                                <td>{{$data->totalVisitors}}</td>
                                <td>{{ round($data->percentage,2) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<!-- end of Visitors From EDC -->

<!-- Modals -->
<div class="modal fade" id="modal-noti-email">
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
                                    id="noti-subject" required/>                             
                            </div>
                            <div class="form-floating">
                                <label>@lang('Message')&nbsp;
                                    <span class="required">*</span>
                                </label>
                                <textarea class="form-control" name="noti_message" rows="4" cols="30" 
                                    id="noti-message" required></textarea>                             
                            </div>
                        </div>
                    </div>              
                </div>
                <div class="modal-footer justify-content-between">         
                    <input type="hidden" name="user_id" id="user-id" value="" />      
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
<!-- end of Modals --> 