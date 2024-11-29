@extends('frontend.layouts.default')
@section('title', __('Assignment'))
@section('header')
    <div class="section mb-0 pb-0">
        <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
            <div class="container text-center">
                <h2>{{__('Assignment') }}</h2>
            </div>
        </header>
    </div>
@endsection
@section('breadcrumbs')
    <div class="bg-gray">
        <div class="container">
            @if(\Illuminate\Support\Facades\Session::get('message'))
                <div class="alert alert-primary">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">X</button>
                    {{ \Illuminate\Support\Facades\Session::get('message') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <form action="{{ route('courses.submit-assignment', $assignment) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mt-3">
                <div class="col-md-12">
                    <p class="heading lead">
                        {{ $assignment->title }}
                    </p>
                    <p>
                        {!! $assignment->description !!}
                    </p>
                    <a href="{{ asset($assignmentMedia->getUrl()) }}" >Download Assignment</a>
                </div>
            </div>

            <div class="row mt-3 mb-3">
                <div class="col-md-12">
                    <h4 class="mb-3">Your Assignment File</h4>
                    @foreach($assignmentUser->getMedia('user_assignment_attached_file') as $resource)
                        <p>
                            <a href="{{asset($resource->getUrl())}}"  class="">{{ $resource->file_name }}</a>
                            ({{ $resource->human_readable_size }})
                            <div class="mb-5"><i>Submitted Date</i> - {{ $resource->created_at  }}</div>
                        </p>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4>Comment</h4>
                    @if($assignmentUser->comment)
                        <p>
                            {{ $assignmentUser->comment }}
                        </p>
                        <p>
                            <i>Comment by {{ $assignmentUser->commentUser->name }}</i>
                        </p>
                    @else
                        <p>
                            Admin not review your assignment yet!
                        </p>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ \App\Repositories\CourseLearnerRepository::goToLastSection(auth()->user()->id, $assignment->course) }}" class="btn btn-primary">Back To Course</a>
                    <a href="{{ route('courses.submit-assignment', $assignment) }}" class="btn btn-warning">Resubmit Assignment</a>
                </div>
            </div>

        </form>
    </div>
@endsection
