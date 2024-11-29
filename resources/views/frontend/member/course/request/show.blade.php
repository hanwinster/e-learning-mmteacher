@extends('backend.layouts.default')
@section('title', __('Approval Request from ') . '#'. $post->id)
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb"> 
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.course-approval-request.index') }}">{{ __('Course Approval Requests') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Approval Details') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mx-auto">
                    <h4>{{ __('Course Approval Detail') }}</h4>
                    <div
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div class="flex-column">
                            {{--<a class="text-muted"
                                href="{{ route('member.preview.show', $post->course->id) }}"></a>--}}
                                <h2>{{ strip_tags($post->course->title) }}</h2>
                            <p><small>
                                    {{ __('Submitted').' '. $post->created_at->diffForHumans() }}
                                    {{ __('by') }}
                                    {!! profileUrl($post->user) !!}
                                </small></p>

                            <blockquote class="blockquote">
                                <p>{{ $post->description }}</p>
                            </blockquote>

                            @if ($post->approval_status == App\Models\Course::APPROVAL_STATUS_APPROVED)
                                <span class="badge badge-success badge-pill">
                                    {{ App\Models\Course::APPROVAL_STATUS[$post->approval_status] }}
                                </span>
                                <small>
                                    at {{ $post->updated_at }} by {!! profileUrl($post->approver) !!}
                                </small>
                                @if (auth()->user()->type == App\User::TYPE_ADMIN || auth()->user()->type == App\User::TYPE_MANAGER)
                                <div class="mt-4">
                                    <a href="{{ route('member.course-approval-request.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                <i class="fa fa-check"></i> {{ __('Undo') }}
                                </a>
                                </div>
                                @endif
                            @elseif ($post->approval_status == App\Models\Course::APPROVAL_STATUS_REJECTED)
                                <span class="badge badge-danger badge-pill">
                                    {{ App\Models\Course::APPROVAL_STATUS[$post->approval_status] }}
                                </span>
                                <small>
                                    at {{ $post->updated_at }} by {!! profileUrl($post->approver) !!}
                                </small>
                                @if (auth()->user()->type == App\User::TYPE_ADMIN || auth()->user()->type == App\User::TYPE_MANAGER)
                                <div class="mt-4">
                                    <a href="{{ route('member.course-approval-request.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                    <i class="fa fa-check"></i> {{ __('Undo') }}
                                    </a>
                                </div>
                                @endif
                            @else
                                <span class="badge badge-secondary badge-pill">
                                    {{ App\Models\Course::APPROVAL_STATUS[$post->approval_status] }}
                                </span>
                                @if (auth()->user()->type == App\User::TYPE_ADMIN || auth()->user()->type == App\User::TYPE_MANAGER)
                                <div class="mt-4">
                                    <a href="{{ route('member.course-approval-request.update-status', [$post->id, 'approve']) }}"
                                        class="btn btn-outline-success">
                                        <i class="fa fa-check"></i> {{ __('Approve') }}
                                    </a>
                                    <a href="{{ route('member.course-approval-request.update-status', [$post->id, 'reject']) }}"
                                        class="btn btn-outline-danger">
                                        <i class="fa fa-close"></i> {{ __('Reject') }}
                                    </a>
                                </div>
                                @endif
                            @endif
                        </div>
                        <div class="image-parent">
                            <img class="img-fluid" src="{{ asset($post->course->getThumbnailPath()) }}"
                                alt="{{ $post->course->title }}">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
