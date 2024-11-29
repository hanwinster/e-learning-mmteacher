@extends('backend.layouts.default')

@section('title', __('View Course'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="/{{config('app.locale')}}/profile/course">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('View Course') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('layouts.form_alert') 
                <div class="col-12 mx-auto">                   
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5>{{ __('Manage Course') }}</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $canEdit = App\Repositories\CoursePermissionRepository::canEdit($course);
                            @endphp
                            <nav>
                                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" 
                                        aria-controls="nav-home" aria-selected="true">@lang('Course Info')</a>
                                    <a class="nav-item nav-link" id="nav-lecture-tab" data-toggle="tab" href="#nav-lecture" role="tab" 
                                        aria-controls="nav-lecture" aria-selected="false">@lang('Lecture')</a>
                                    <a class="nav-item nav-link" id="nav-learning-activity-tab" data-toggle="tab" href="#nav-learning-activity" role="tab" 
                                        aria-controls="nav-learning-activity" aria-selected="false">@lang('Learning Activity')</a>
                                    <a class="nav-item nav-link" id="nav-quiz-tab" data-toggle="tab" href="#nav-quiz" role="tab" 
                                        aria-controls="nav-quiz" aria-selected="false">@lang('Quiz')</a>
                                    <!-- <a class="nav-item nav-link" id="nav-assignment-tab" data-toggle="tab" href="#nav-assignment" role="tab" 
                                        aria-controls="nav-assignment" aria-selected="false"></a> -->
                                    <a class="nav-item nav-link" id="nav-zoom-tab" data-toggle="tab" href="#nav-zoom" role="tab" 
                                        aria-controls="nav-zoom" aria-selected="false">@lang('Live Session')</a>
                                    <a class="nav-item nav-link" id="nav-summary-tab" data-toggle="tab" href="#nav-summary" role="tab" 
                                        aria-controls="nav-summary" aria-selected="false">@lang('Summary')</a>
                                    
                                    <a class="nav-item nav-link" id="nav-assessment-tab" data-toggle="tab" href="#nav-assessment" role="tab" 
                                        aria-controls="nav-assessment" aria-selected="false">@lang('Assessment')</a>
                                    <a class="nav-item nav-link" id="nav-evaluation-tab" data-toggle="tab" href="#nav-evaluation" role="tab" 
                                        aria-controls="nav-evaluation" aria-selected="false">@lang('Evaluation')</a>
                                    <a class="nav-item nav-link" id="nav-certificate-tab" data-toggle="tab" href="#nav-certificate" role="tab" 
                                        aria-controls="nav-certificate" aria-selected="false">@lang('Certificate')</a>
                                    <a class="nav-item nav-link" id="nav-discussion-tab" data-toggle="tab" href="#nav-discussion" role="tab" 
                                        aria-controls="nav-discussion" aria-selected="false">@lang('Discussion')</a>
                                    <a class="nav-item nav-link" id="nav-order-tab" data-toggle="tab" href="#nav-order" role="tab" 
                                        aria-controls="nav-order" aria-selected="false">@lang('Order')</a>
                                  </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <!------------------- Course Info Start -------------------------->
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        @include('frontend.member.course.show_preview_course')
                                    </div>
                                    <!------------------- Lecture List Start -------------------------->
                                    <div class="tab-pane fade" id="nav-lecture" role="tabpanel" aria-labelledby="nav-lecture-tab">
                                        @include('frontend.member.course.show_lecture_list')
                                    </div>
                                    <!------------------- Learning Activity List Start -------------------------->
                                    <div class="tab-pane fade" id="nav-learning-activity" role="tabpanel" aria-labelledby="nav-learning-activity-tab">
                                        @include('frontend.member.course.show_learning_activity_list')
                                    </div>
                                    <!------------------- Quiz List Start -------------------------->
                                    <div class="tab-pane fade" id="nav-quiz" role="tabpanel" aria-labelledby="nav-quiz-tab">
                                        @include('frontend.member.course.show_quiz_list')
                                    </div>
                                    <!------------------- Assignment List Start -------------------------->
                                    <!-- <div class="tab-pane fade" id="nav-assignment" role="tabpanel" aria-labelledby="nav-assignment-tab">
                                        
                                    </div> -->
                                    <!------------------- Live Session Start -------------------------->
                                    <div class="tab-pane fade" id="nav-zoom" role="tabpanel" aria-labelledby="nav-zoom-tab">
                                        @include('frontend.member.course.show_live_session')
                                    </div>
                                    <!------------------- Summary List Start -------------------------->
                                    <div class="tab-pane fade" id="nav-summary" role="tabpanel" aria-labelledby="nav-summary-tab">
                                        @include('frontend.member.course.show_summary_list') 
                                    </div>                                
                                    <!------------------- Assessment Start -------------------------->
                                    <div class="tab-pane fade" id="nav-assessment" role="tabpanel" aria-labelledby="nav-assessment-tab">
                                        @include('frontend.member.course.show_assessment')
                                    </div>
                                    <!------------------- Evaluation Start -------------------------->
                                    <div class="tab-pane fade" id="nav-evaluation" role="tabpanel" aria-labelledby="nav-evaluation-tab">
                                        @include('frontend.member.course.show_evaluation')
                                    </div>
                                    <!------------------- Certificate Start -------------------------->
                                    <div class="tab-pane fade" id="nav-certificate" role="tabpanel" aria-labelledby="nav-certificate-tab">
                                        @include('frontend.member.course.show_certificate')
                                    </div>
                                    <!------------------- Discussion Start -------------------------->
                                    <div class="tab-pane fade" id="nav-discussion" role="tabpanel" aria-labelledby="nav-discussion-tab">
                                        @include('frontend.member.course.show_discussion') 
                                    </div>
                                    <!------------------- Order List Start -------------------------->
                                    <div class="tab-pane fade" id="nav-order" role="tabpanel" aria-labelledby="nav-order-tab">
                                        @include('frontend.member.course.show_order') 
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

