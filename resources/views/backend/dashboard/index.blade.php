@extends('backend.layouts.default')

@section('title', 'Dashboard')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <card title="{{__('Courses')}}" count-data=" {{ App\Models\Course::count() }}" grid="col-md-3" 
                card-border-color="card-primary"></card>
                <card title="{{__('Course Categories')}}" count-data="{{ App\Models\CourseCategory::count() }}" grid="col-md-3" 
                card-border-color="card-success"></card>
                <card title="{{__('Pages')}}" count-data="{{ App\Models\Page::count() }}" grid="col-md-3"
                 card-border-color="card-warning"></card>
                <card title="{{__('Users')}}" count-data="{{ App\User::count() }}" grid="col-md-3" 
                card-border-color="card-danger"></card>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-5">
                                    <h3 class="card-title">{{ __('Signup Overview') }}</h3>
                                    <form action="{{ route('admin.exportSignups') }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        &nbsp;&nbsp;
                                        <button type="submit" id="downloadSignups" class="btn btn-secondary btn-sm">
                                            <!-- {{ __('Download') }}&nbsp;--><i class="fa fa-download"></i>
                                        </button>
                                        <input type="hidden" name="startDate" value="{{ $startDate }}">
                                        <input type="hidden" name="endDate" value="{{ $endDate }}">
                                    </form>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <!-- <label>{{ __('Date') }}:</label> -->
                                        <div class="input-group date" id="signup-start-date" data-target-input="nearest">
                                            <input id="signup-start-date-btn" type="text" class="form-control datetimepicker-input" 
                                            placeholder="{{ __('Start Date') }}" data-target="#signup-start-date" value="{{$startDate}}" required/>
                                            <div class="input-group-append" data-target="#signup-start-date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <!-- <label>{{ __('Date') }}:</label> -->
                                        <div class="input-group date" id="signup-end-date" data-target-input="nearest">
                                            <input id="signup-end-date-btn" type="text" class="form-control datetimepicker-input" 
                                            placeholder="{{ __('End Date') }}" data-target="#signup-end-date" value="{{$endDate}}" required/>
                                            <div class="input-group-append" data-target="#signup-end-date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <button id="searchSignupData" class="btn btn-secondary btn-sm">
                                        <i class="fa fa-search"></i>
                                    </button>
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
                <div class="col-4">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Gender Ratio') }}</h3>
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
                            <canvas id="gender-donut-chart" style="min-height: 250px; height: 250px; 
                                    max-height: 250px; max-width: 100%; display: block; width: 572px;"
                                     width="715" height="312" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection




