    @extends('backend.layouts.default')

    @section('title', __('Course'))

    @section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Default Elements -->
                <div class="card">
                    <h4 class="card-title">
                        Take Course Users for <strong>'{{$course->title}}'</strong>.
                    </h4>
                    <div class="card-body">
                        {{-- @if(count($course->courseLearners) != 0)
                            {{$course->courseLearners}}
                        @endif --}}
                        <a href="{{route('admin.course.index')}}" class="pull-right" style="text-decoration: underline;">@lang('Back To Courses')</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-vcenter dataTable no-footer" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th width="60">@lang('No#')</th>
                                        <th>@lang('User Name')</th>
                                        <th>@lang('Email')</th>
                                        <th>@lang('User Type')</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                        @forelse($course->courseLearners as $key => $learner)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>{{$learner->name}}</td>
                                                <td>{{$learner->email}}</td>
                                                <td>{{$learner->user_type}}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                   
                                </tbody>
                            </table>
                        </div>
                          
                    </div>
                </div>
            <!-- END Default Elements -->
        </div>
    </div>
    </div>
    @stop

    @section('css')
    @parent
        <style>

        </style>
    @endsection

