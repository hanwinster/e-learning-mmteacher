<div class="row">
    <card title="{{__('Courses')}}" count-data=" {{ count($totalCourses) }}" grid="col-12 col-md-6 col-lg-3" card-border-color="card-primary"></card>
    <card title="{{__('Course Categories')}}" count-data="{{ App\Models\CourseCategory::count() }}" grid="col-12 col-md-6 col-lg-3" card-border-color="card-success"></card>
    <card title="{{__('Users')}}" count-data="{{ App\User::count() }}" grid="col-12 col-md-6 col-lg-3" card-border-color="card-warning"></card>
    <card title="{{__('Certificates')}}" count-data="{{ \App\Repositories\CourseLearnerRepository::getTotalCertificates() }}" grid="col-12 col-md-6 col-lg-3" card-border-color="card-danger"></card>
</div>
<div class="row">
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
                            <div class="input-group date" id="signup-start-date" data-target-input="nearest">
                                <input id="signup-start-date-btn" type="text" class="form-control datetimepicker-input" placeholder="{{ __('Start Date') }}" data-target="#signup-start-date" value="{{$startDate}}" required />
                                <div class="input-group-append" data-target="#signup-start-date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="form-group">
                            <!-- <label>{{ __('Date') }}:</label> -->
                            <div class="input-group date" id="signup-end-date" data-target-input="nearest">
                                <input id="signup-end-date-btn" type="text" class="form-control datetimepicker-input" placeholder="{{ __('End Date') }}" data-target="#signup-end-date" value="{{$endDate}}" required />
                                <div class="input-group-append" data-target="#signup-end-date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <button id="searchSignupData" class="btn btn-secondary btn-sm" style="height:36px;">
                            <i class="fa fa-search"></i>
                        </button>&nbsp;
                        <form action="{{ route('member.exportSignups') }}" method="POST" style="display:inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            &nbsp;&nbsp;
                            <button type="submit" id="downloadSignups" class="btn btn-secondary btn-sm" style="height:36px;">
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
    <div class="col-md-12 col-lg-3 col-xl-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">{{ __('Gender Ratio') }}</h4>&nbsp;
                <button type="button" name="gender_select" class="btn btn-secondary up-half-rem" id="gender-select">
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
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">@lang('EDC Statistics')
                    &nbsp;
                    <a class="btn btn-secondary btn-sm " onclick="selectContents( document.getElementById('edc') );">
                        @lang('Select Table Data To Copy')
                    </a>
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
            <div class="card-body table-responsive p-0">
                <table id="edc" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>@lang('No.')</th>
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
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h4 class="card-title">@lang('Visitors From EDC ')
                    &nbsp;
                    <a class="btn btn-secondary btn-sm " onclick="selectContents( document.getElementById('visitors') );">
                        @lang('Select Table Data To Copy')
                    </a>
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
            <div class="card-body table-responsive p-0">
                <table id="visitors" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>@lang('No.')</th>
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