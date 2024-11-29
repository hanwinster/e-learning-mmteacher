@extends('frontend.layouts.default')
@section('title', $post->title)
@section('meta_description', str_limit(strip_tags($post->body),
200))
@section('og_description', str_limit(strip_tags($post->body), 200))
@section('header')
<div class="section mb-0 pb-0">
    <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
    @include('frontend.resource.partials.search')
    </header>
</div>
@endsection

@section('content')
<main class="main-content">
    <section id="section-mission" class="mt-7">
        <div class="container">
            <div class="row gap-y align-items-center">
                <div class="col-md-7 mx-auto">
                    <h2>{{ $post->title }}</h2>

                    <div>
                        {!! Blade::compileString($post->body) !!}
                    </div>

                    <div>
                        @if ($images = $post->getMedia('pages'))
                        @foreach($images as $image)
                        <a class="gallery-item" href="{{ $image->getUrl('large') }}">
                                    <img src="{{ asset($image->getUrl('large')) }}" alt="{{ $image->name }} ">
                                </a>
                        @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection
