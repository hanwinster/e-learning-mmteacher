@extends('frontend.layouts.default')
@section('title', __('View Users'))

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
                    <h1>{{ __('View Users') }}</h1>
                    <div class="card">
                        <header class="card-header">
                            <form action="{{ route('member.view-user.index') }}" method="get">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="lookup lookup-right d-none d-lg-block">
                                        <input name="search" class="form-control" placeholder="Search" type="text" value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <button class="btn btn-primary">{{ __('Search') }}</button>
                                    <a href="{{ route('member.view-user.index') }}" class="btn">{{ __('Reset') }}</a>
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
                                            </div>
                                            <div class="image-parent">
                                                <img class="img-fluid" src="{{ asset($post->getThumbnailPath()) }}" alt="{{ $post->name }}" >

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
                                    <div class="alert alert-info" role="alert">
                                {{ __('There are no Student Teachers in this college ('.optional($college)->title.').') }}</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
@endsection
