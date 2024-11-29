@extends('frontend.layouts.default')
@section('title', __('Approval Requests'))

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
                    <div class="card">
                            <header class="card-header">
                                <form action="{{ route('member.approval-request.index') }}" method="get">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="lookup lookup-right d-none d-lg-block">
                                            <input name="search" class="form-control" placeholder="Resource Title" type="text" value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        {!! Form::select('approval_status', ['' => '-Approval Status -'] + $approvalStatus, request('approval_status'),
                                        ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-4">
                                        <button class="btn btn-primary">{{ __('Search') }}</button>
                                        <a href="{{ route('member.approval-request.index') }}" class="btn">{{ __('Reset') }}</a>
                                    </div>
                                </div>
                                </form>
                            </header>
                        </div>
                    <div class="row">

                        <div class="container">

                            @if (isset($posts) && ! $posts->isEmpty())
                            <div class="row">
                                <div class="col-12 col-sm-8 col-lg-12">
                                    <ul class="list-group">
                                    @foreach ($posts as $post)
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
                                            <div class="flex-column">
                                                <a title="{{ __('Click here to view Approval Request Details') }}" class="text-muted" href="{{ route('member.approval-request.show', $post->id) }}">
                                                    <h4>{{ $post->resource->title }}</h4>
                                                </a>
                                                <p><small>
                                                    {{ __('Submitted') }} <span title="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</span>
                                                    {{ __('by') }}
                                                    {!! profileUrl($post->user) !!} {{ __('from') }} {{ $post->user->college->title ?? '' }}
                                                    </small></p>
                                                @if ($post->approval_status == App\Models\Resource::APPROVAL_STATUS_APPROVED)
                                                <span class="badge badge-success badge-pill">
                                                    {{ App\Models\Resource::APPROVAL_STATUS[$post->approval_status] }}
                                                </span>
                                                <small>
                                                at {{ $post->updated_at }} by {!! profileUrl($post->approver) !!}
                                                </small>
                                                <div class="mt-4">
                                                    <a href="{{ route('member.approval-request.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                                    {{ __('Undo') }}
                                                    </a>
                                                </div>
                                                @elseif ($post->approval_status == App\Models\Resource::APPROVAL_STATUS_REJECTED)
                                                <span class="badge badge-danger badge-pill">
                                                    {{ App\Models\Resource::APPROVAL_STATUS[$post->approval_status] }}
                                                </span>
                                                <small>
                                                at {{ $post->updated_at }} by {!! profileUrl($post->approver) !!}
                                                </small>
                                                <div class="mt-4">
                                                    <a href="{{ route('member.approval-request.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                                    {{ __('Undo') }}
                                                    </a>
                                                </div>
                                                @else
                                                <span class="badge badge-secondary badge-pill">
                                                    {{ App\Models\Resource::APPROVAL_STATUS[$post->approval_status] }}
                                                </span>
                                                <div class="mt-4">
                                                <a href="{{ route('member.approval-request.update-status', [$post->id, 'approve']) }}" class="btn btn-outline-success">
                                                {{ __('Approve') }}
                                                </a>
                                                <a href="{{ route('member.approval-request.update-status', [$post->id, 'reject']) }}" class="btn btn-outline-danger">
                                                {{ __('Reject') }}
                                                </a>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="image-parent">
                                                <img class="img-fluid" src="{{ asset($post->resource->getThumbnailPath()) }}" alt="{{ $post->resource->title }}" >

                                            </div>
                                        </div>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div>
                                {{ $posts->links() }}
                            </div>
                            @else
                            <div class="text-info">{{ __('There are no approval requests.') }}</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
@endsection
