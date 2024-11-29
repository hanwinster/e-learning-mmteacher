@extends('frontend.layouts.default')
@section('title', __('Approval Requests') . '#'. $post->id)
@section('header')
<div class="section mb-0 pb-0">
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container">
            <div class="row">

                <div class="col-md-3 mx-auto">
                    @include('frontend.member.partials.sidebar')
                </div>

                <div class="col-md-9 mx-auto">
                    <h1>{{ __('Approval Requests') }}</h1>
                    <div
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
                        <div class="flex-column">
                            <a class="text-muted"
                                href="{{ route('member.preview.show', $post->resource->id) }}"><h2>{{ $post->resource->title }}</h2></a>
                            <p><small>
                                    {{ __('Submitted').' '. $post->created_at->diffForHumans() }}
                                    {{ __('by') }}
                                    {!! profileUrl($post->user) !!}
                                </small></p>

                            <blockquote class="blockquote">
                                <p>{{ $post->description }}</p>
                            </blockquote>

                            @if ($post->approval_status == App\Models\Resource::APPROVAL_STATUS_APPROVED)
                                <span class="badge badge-success badge-pill">
                                    {{ App\Models\Resource::APPROVAL_STATUS[$post->approval_status] }}
                                </span>
                                <small>
                                    at {{ $post->updated_at }} by {!! profileUrl($post->approver) !!}
                                </small>
                                @if (auth()->user()->type == App\User::TYPE_ADMIN || auth()->user()->type == App\User::TYPE_MANAGER)
                                <div class="mt-4">
                                    <a href="{{ route('member.approval-request.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                <i class="fa fa-check"></i> {{ __('Undo') }}
                                </a>
                                </div>
                                @endif
                            @elseif ($post->approval_status == App\Models\Resource::APPROVAL_STATUS_REJECTED)
                                <span class="badge badge-danger badge-pill">
                                    {{ App\Models\Resource::APPROVAL_STATUS[$post->approval_status] }}
                                </span>
                                <small>
                                    at {{ $post->updated_at }} by {!! profileUrl($post->approver) !!}
                                </small>
                                @if (auth()->user()->type == App\User::TYPE_ADMIN || auth()->user()->type == App\User::TYPE_MANAGER)
                                <div class="mt-4">
                                    <a href="{{ route('member.approval-request.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                    <i class="fa fa-check"></i> {{ __('Undo') }}
                                    </a>
                                </div>
                                @endif
                            @else
                                <span class="badge badge-secondary badge-pill">
                                    {{ App\Models\Resource::APPROVAL_STATUS[$post->approval_status] }}
                                </span>
                                @if (auth()->user()->type == App\User::TYPE_ADMIN || auth()->user()->type == App\User::TYPE_MANAGER)
                                <div class="mt-4">
                                    <a href="{{ route('member.approval-request.update-status', [$post->id, 'approve']) }}"
                                        class="btn btn-outline-success">
                                        <i class="fa fa-check"></i> {{ __('Approve') }}
                                    </a>
                                    <a href="{{ route('member.approval-request.update-status', [$post->id, 'reject']) }}"
                                        class="btn btn-outline-danger">
                                        <i class="fa fa-close"></i> {{ __('Reject') }}
                                    </a>
                                </div>
                                @endif
                            @endif
                        </div>
                        <div class="image-parent">
                            <img class="img-fluid" src="{{ asset($post->resource->getThumbnailPath()) }}"
                                alt="{{ $post->resource->title }}">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
