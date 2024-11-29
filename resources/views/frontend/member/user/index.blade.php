@extends('backend.layouts.default')
@section('title', __('Manage Users'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Manage Users') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
                <div class="row">  
                    @include('layouts.form_alert')  
                    <div class="col-12 mx-auto">
                    
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4>{{ __('Manage Users') }}</h4>
                            <form action="{{ route('member.user.index') }}" method="get">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="lookup lookup-right d-none d-lg-block">
                                            <input name="search" class="form-control" placeholder="Search" type="text" 
                                            value="{{ request('search') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        {!! Form::select('approved', ['' => '-Approval Status -'] + $approvalStatus, request('approved'),
                                        ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-4">
                                        <button class="btn btn-primary btn-md">{{ __('Search') }}</button>
                                        <a href="{{ route('member.user.index') }}" class="btn btn-secondary btn-md">{{ __('Reset') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                   
                        <div class="card-body">
                            <div class="row">
                                <div class="container">
                                    @if (isset($posts) && ! $posts->isEmpty())
                                    <div class="row">
                                        <div class="col-12 col-sm-8 col-lg-12">
                                            <ul class="list-group">
                                            @foreach ($posts as $post)
                                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <div class="flex-column">
                                                        <a title="{{ __('Click here to view Profile') }}" class="text-muted" href="{{ route('profile.show', $post->username) }}">
                                                            <h4>{{ $post->name }} <small>({{ '@'.$post->username }})</small></h4>
                                                        </a>

                                                        <p><small>
                                                            {{ __('Registered') }} <span title="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</span>
                                                            {{ __('from') }} {{ $post->college->title ?? '' }}
                                                            </small></p>
                                                            <div>
                                                                {{ __('Email') }} : {{ $post->email }}
                                                                <br>
                                                                {{ __('Mobile No.') }} : {{ $post->mobile_no }}
                                                                <br>
                                                                {{ __('Ministry Staff') }} : {{ $post->getStaffType() }}
                                                                <br>
                                                                {{ __('User Type') }} : {{ $post->getType() }}
                                                                <br>
                                                                {{ __('Subjects') }} :
                                                                @if ($subjects = $post->subjects)
                                                                    @foreach($subjects as $subject)
                                                                    {{ $subject->title }}@if (!$loop->last), @endif
                                                                    @endforeach
                                                                @endif
                                                                <br>
                                                                {{ __('Years')}} :

                                                                @if ($years = $post->getYears())
                                                                @foreach ($years as $year)
                                                                    {{ $year->title }}@if (!$loop->last), @endif
                                                                @endforeach
                                                                @endif
                                                            </div>
                                                        @if ($post->approved == App\User::APPROVAL_STATUS_APPROVED)
                                                        <span class="badge badge-success badge-pill">
                                                            {{ $post->getApprovalStatus() }}
                                                        </span>
                                                        <small>
                                                        </small>
                                                        <div class="mt-4">
                                                            <a href="{{ route('member.user.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                                            {{ __('Undo') }}
                                                            </a>
                                                        </div>
                                                        @elseif ($post->approved == App\User::APPROVAL_STATUS_BLOCKED)
                                                        <span class="badge badge-danger badge-pill">
                                                            {{ $post->getApprovalStatus() }}
                                                        </span>
                                                        <small>
                                                        </small>
                                                        <div class="mt-4">
                                                            <a href="{{ route('member.user.update-status', [$post->id, 'undo']) }}" class="btn btn-outline-success">
                                                            {{ __('Undo') }}
                                                            </a>
                                                        </div>
                                                        @else
                                                        <span class="badge badge-secondary badge-pill">
                                                            {{ $post->getApprovalStatus() }}
                                                        </span>
                                                        <div class="mt-4">
                                                            <a href="{{ route('member.user.update-status', [$post->id, 'approve']) }}" class="btn btn-outline-success">
                                                        {{ __('Approve') }}
                                                        </a>
                                                            <a href="{{ route('member.user.update-status', [$post->id, 'block']) }}" class="btn btn-outline-danger">
                                                        {{ __('Block') }}
                                                        </a>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="image-parent text-center">
                                                        <img class="img-fluid" src="{{ asset($post->getThumbnailPath()) }}" alt="{{ $post->name }}" >
                                                        <br>
                                                        <a href="{{ route('member.user.edit', $post->id) }}" 
                                                            class="mt-3 btn btn-primary btn-sm">
                                                            {{ __('Edit') }}
                                                        </a>
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
                                        <div class="text-info">{{ __('There are no search results.') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>    
                    </div>                        
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
