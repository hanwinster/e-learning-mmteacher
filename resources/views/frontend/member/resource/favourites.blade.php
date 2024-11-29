@extends('frontend.layouts.default')
@section('title', __('My Favourites'))

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
                    <h1>{{ __('My Favourites') }}</h1>

                    @if (isset($posts) && ! $posts->isEmpty())
                    <div class="row gap-y gap-2">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">{{ __('Resource') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                        <tbody>
                        @foreach ($posts as $post)
                        <tr>
                            <td width="210">
                            @if ($image = $post->getMedia('resource_cover_image')->first())
                                <a href="{{ route('resource.show', $post->slug) }}">
                                    <img src="{{ asset($image->getUrl('thumb')) }}">
                                </a>
                            @endif
                            </td>
                            <td>
                            <a href="{{ route('resource.show', $post->slug) }}">{{ $post->title }}</a>
                            </td>
                            <td width="100">
                                <a class="text-danger" onclick="return confirm('Are you sure you want to remove?')" href="{{ route('resource.unfavourite', $post->id) }}">
                                    <i class="ti-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                    <div>
                        {{ $posts->links() }}
                    </div>
                        @else
                        <div class="text-info">{{ __('There are no favourite resources.') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
