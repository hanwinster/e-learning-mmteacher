@extends('backend.layouts.default')

@section('title', __('Users Who Took The Course'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('member.course.index')}}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Users Who Took The Course') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row gap-y">
                @include('layouts.form_alert')                                       
                <div class="col-12">
                    
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5>{{strip_tags($course->title)}}&nbsp;
                                <button class="btn btn-primary btn-sm sendNotiMail mb-1" data-toggle="modal"
                                    data-target="#modal-noti-course-takers" value="{{ $course->id }}_{{ strip_tags($course->title) }}"
                                            >@lang('Send Email To All Course Takers') 
                                </button>&nbsp;
                            </h5>
                        </div>
                        <div class="card-body">                            
                        <!-- <a href="{{route('member.course.index')}}" class="pull-right" style="text-decoration: underline;">@lang('Back To Courses')</a> -->
                        <h6>@lang('Users Who Took The Course')</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-vcenter dataTable no-footer" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="60">@lang('No#')</th>
                                        <th>@lang('User Name')</th>
                                        <th>@lang('Email')</th>
                                        <th>@lang('User Type')</th> 
                                        <th>@lang('Progress')</th>   
                                        <th>@lang('Assignment')</th>                                      
                                    </tr>
                                </thead>
                                <tbody>
                                        @forelse($course->courseLearners as $key => $learner)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>{{$learner->name}}</td>
                                                <td>{{$learner->email}}</td>
                                                <td>{{ $learner->user_type}}</td>
                                                <td>{{ __(\App\Models\CourseLearner::COURSE_LEARNER_STATUSES[$learner->pivot->status]) }}
                                                    &nbsp;({{$learner->pivot->percentage}}%)
                                                </td>
                                                @php 
                                                    //dd($course->quizzes);exit;
                                                    $linkToViewAssignment = [];
                                                    $hasUserAssignment = \App\Repositories\AssignmentRepository::hasUserAssignment($learner->id);
                                                    if($hasUserAssignment) {
                                                        $linkToViewAssignment = \App\Repositories\CourseRepository::getAssignmentLink($course->id, $learner->id);
                                                    }
                                                @endphp
                                                <td>
                                                    @if(count($linkToViewAssignment) > 0)
                                                        @foreach($linkToViewAssignment as $idx => $la)
                                                            <a href="{{$la}}">{{__('View')}}</a>&nbsp; 
                                                            @if($idx !== count($linkToViewAssignment) -1 ) , @endif
                                                        @endforeach
                                                    @else
                                                        -
                                                    @endif
                                                </td>                                              
                                            </tr>
                                        @empty
                                        @endforelse
                                   
                                </tbody>
                            </table>
                        </div>  
                        <br>
                        <hr>
                        <h6 class="mt-3">@lang('Users Who Cancelled The Course')</h6>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-vcenter dataTable no-footer" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="60">@lang('No#')</th>
                                        <th>@lang('User Name')</th>
                                        <th>@lang('Email')</th>
                                        <th>@lang('User Type')</th> 
                                        <th>@lang('Cancelled At')</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                        @forelse($course->courseCancelLearners as $key => $ccl)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>{{$ccl->name}}</td>
                                                <td>{{$ccl->email}}</td>
                                                <td>{{$ccl->user_type}}</td>
                                                <td>{{$ccl->pivot->created_at}}</td>                                              
                                            </tr>
                                        @empty
                                        @endforelse
                                   
                                </tbody>
                            </table>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal-noti-course-takers">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('member.course.notify-course-takers') }}">
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
                                    id="noti-c-subject" required/>                             
                            </div>
                            <div class="form-floating">
                                <label>@lang('Message')&nbsp;
                                    <span class="required">*</span>
                                </label>
                                <textarea class="form-control" name="noti_message" rows="4" cols="30" 
                                    id="noti-c-message" required></textarea>                             
                            </div>
                        </div>
                    </div>              
                </div>
                <div class="modal-footer justify-content-between">         
                    <input type="hidden" name="course_id" id="course-id" value="{{$course->id}}" />      
                    <button type="submit" class="btn btn-primary btn-sm" name="btnSend" value="1">@lang('Send')</button>
                    <button type="button" class="btn btn-outline-dark btn-sm" data-dismiss="modal">@lang('Cancel')</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

