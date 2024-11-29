@extends('backend.layouts.default')

@section('title', __('Course Approval Requests'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Course Approval Requests') }}</li>
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
                    <h1>{{ __('Course Approval Requests') }}</h1>
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('member.course-approval-request.index') }}" method="get">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="lookup lookup-right d-none d-lg-block">
                                            <input name="search" class="form-control" placeholder="Course Title" type="text" value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        {!! Form::select('approval_status', ['' => '-Approval Status -'] + $approvalStatus, request('approval_status'),
                                        ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-4">
                                        <button class="btn btn-primary btn-sm">{{ __('Search') }}</button>
                                        <a href="{{ route('member.course-approval-request.index') }}" 
                                            class="btn btn-secondary btn-sm">{{ __('Reset') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            @if (isset($posts) && ! $posts->isEmpty())
                                <div class="row mb-3">            
                                    <div class="col-12">
                                        <ul class="list-group">
                                            @forelse ($posts as $post)
                                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <div class="flex-column">
                                                        <a title="{{ __('Click here to view Course Approval Request Details') }}" class="text-muted" href="{{ route('member.course-approval-request.show', $post->id) }}">
                                                            <h4>{{ strip_tags($post->course->title) }}</h4>
                                                        </a>
                                                        <p><small>
                                                                {{ __('Submitted') }} <span title="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</span>
                                                                {{ __('by') }}
                                                                {!! profileUrl($post->user) !!} {{ __('from') }} {{ $post->user->college->title ?? '' }}
                                                            </small></p>
                                                        @if ($post->approval_status == App\Models\Course::APPROVAL_STATUS_APPROVED)
                                                            <span class="badge badge-primary badge-pill">
                                                                {{ App\Models\Course::APPROVAL_STATUS[$post->approval_status] }}
                                                            </span>
                                                            <small>
                                                                at {{ $post->updated_at }} by {!! profileUrl($post->approver) !!}
                                                            </small>
                                                            <div class="mt-4">
                                                                <a href="{{ route('member.course-approval-request.update-status', [$post->id, 'undo']) }}" 
                                                                    class="btn btn-outline-primary">
                                                                    {{ __('Undo') }}
                                                                </a>
                                                            </div>
                                                        @elseif ($post->approval_status == App\Models\Course::APPROVAL_STATUS_REJECTED)
                                                            <span class="badge badge-danger badge-pill">
                                                                {{ App\Models\Course::APPROVAL_STATUS[$post->approval_status] }}
                                                            </span>
                                                            <small>
                                                                at {{ $post->updated_at }} by {!! profileUrl($post->approver) !!}
                                                            </small>
                                                            <div class="mt-4">
                                                                <a href="{{ route('member.course-approval-request.update-status', [$post->id, 'undo']) }}" 
                                                                class="btn btn-outline-success">
                                                                    {{ __('Undo') }}
                                                                </a>
                                                            </div>
                                                        @else
                                                            <span class="badge badge-secondary badge-pill">
                                                                {{ App\Models\Course::APPROVAL_STATUS[$post->approval_status] }}
                                                            </span>
                                                            <div class="mt-4">
                                                                <a href="{{ route('member.course-approval-request.update-status', [$post->id, 'approve']) }}" 
                                                                    class="btn btn-outline-success">
                                                                    {{ __('Approve') }}
                                                                </a>
                                                                <a href="{{ route('member.course-approval-request.update-status', [$post->id, 'reject']) }}" 
                                                                    class="btn btn-outline-danger">
                                                                    {{ __('Reject') }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="image-parent">
                                                        <img class="img-fluid" src="{{ asset($post->course->getThumbnailPath()) }}" 
                                                            alt="{{ $post->course->title }}">
                                                    </div>
                                                </div>
                                            @empty
                                            @endforelse
                                        </ul>
                                    </div>                               
                                    <div>
                                        {{ $posts->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="text-info">{{ __('There are no approval requests.') }}</div>
                            @endif                       
                        </div> <!-- end of card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection