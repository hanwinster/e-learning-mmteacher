@extends('backend.layouts.default')

@section('title', __('Assignment Detail'))

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Default Elements -->
            <div class="card">
                <h4 class="card-title">
                    [Show] #<strong title="ID">{{ $assignment->id }}</strong>
                </h4>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 pt-4">
                            <a href="{{route('admin.course.show', $assignment->course_id)}}#nav-assignment" class="pull-right">@lang('Go To Assignments')</a>
                            <table class="table table-bordered table-vcenter dataTable no-footer">
                                <tr>
                                    <td>Course Title</td>
                                    <td>{{$assignment->course->title}}</td>
                                </tr>
                                <tr>
                                    <td>Assignment Title</td>
                                    <td>{{$assignment->title}}</td>
                                </tr>
                                <tr>
                                    <td>Assignment Instruction</td>
                                    <td>{!!$assignment->description!!}</td>
                                </tr>
                                <tr>
                                    <td>Attachement PDF</td>
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
        <!-- END Default Elements -->


        </div>
    </div>
</div>
@stop

@section('css')
@parent
@endsection

@section('js')
@parent
<script>
    $(document).ready(function() {

    });
</script>

@endsection
