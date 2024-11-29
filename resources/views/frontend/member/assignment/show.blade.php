@extends('backend.layouts.default')

@section('title', __('Assignment Detail'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="/{{ config('app.locale') }}/profile/course">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item"><a href="/{{ config('app.locale') }}/profile/course/{{$course->id}}">{{$course->title}}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('member.course.show', $assignment->course_id)}}#nav-assignment">{{$assignment->title}}</a></li>
                        <li class="breadcrumb-item active">
                            {{ __('Submitted Assignments') }}                         
                        </li>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row gap-y">
                @include('layouts.form_alert') 
                <div class="col-12 mx-auto">
                    <h1>{{ __('Assignment Detail') }}</h1>                    

                    <div class="card card-outline card-info">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pt-4">
                                    <a href="{{route('member.course.show', $assignment->course_id)}}#nav-assignment" 
                                        class="pull-right">@lang('Go To Assignments')</a>
                                    <table class="table table-bordered table-vcenter dataTable no-footer">
                                        <tr>
                                            <td>@lang('Course Title')</td>
                                            <td>{{$assignment->course->title}}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('Assignment Title')</td>
                                            <td>{{$assignment->title}}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('Assignment Instruction')</td>
                                            <td>{!!$assignment->description!!}</td>
                                        </tr>
                                        <tr>
                                            <td>@lang('Attachement PDF')</td>
                                            <td>
                                                <div style="padding: 10px 0px;">
                                                    @foreach($assignment->getMedia('assignment_attached_file') as $resource)
                                                        <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    </table>     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
</div>

@stop

@section('css')
@parent
@endsection

@section('script')
@parent
    <script src="https://unpkg.com/vee-validate@latest"></script>
<!-- <script>
$(document).ready(function() {

});
</script> -->

<script>

        // new Vue({
        //     el: '#assignment_root',
        //     data: {
        //         messages: {

        //         },
        //     },
        //     //components: [commodity_component],

        //     mounted() {
        //     },
        //     methods: {
        //         validateBeforeSubmit: function(e) {
        //             this.$validator.validateAll().then((result) => {
        //                 console.log(result)

        //                 if (result) {
        //                     // eslint-disable-next-line
        //                     return true;
        //                 }
        //                 e.preventDefault();
        //             });
        //         }
        //     }

        // });
        </script>
    @endsection
