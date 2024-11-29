@extends('frontend.layouts.default')

@section('title', $post->title)

@section('meta_description', str_limit(strip_tags($post->body), 200))
@section('og_description', str_limit(strip_tags($post->body), 200))

@if ($img_url = optional($post->getMedia('articles')->first())->getUrl('large'))
@else
@php $img_url = 'assets/img/logo-dark@2x.png'; @endphp
@endif

@section('og_image', asset($img_url))
@section('meta_description', str_limit(strip_tags($post->body), 200))
@section('og_description', str_limit(strip_tags($post->body), 200))

@section('header')
<div class="section mb-0 pb-0">
<header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
    @include('frontend.article.partials.banner')
</header>
</div>
@endsection

@section('breadcrumbs')
<div class="bg-gray">
    <div class="container">
        <div class="row">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/news') }}">{{ __('Media') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ url($post->category->path()) }}">{{ $post->category->title ?? '' }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('content')
<main class="main-content pt-5 mb-5">
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('frontend.article.partials.search')
                    @include('frontend.article.partials.categories')
                    @include('frontend.article.partials.latest')
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <article itemscope itemtype="http://schema.org/Article">

                                <!--<h1 itemprop="name">{{ $post->title }}</h1>-->
                                <h2 class="article-title">{{ $post->title }}</h2>

                                <div class="page-meta mb-5">
                                    {{ __('Posted on ') }}<span class="text-gray" itemprop="datePublished">{{ $post->updated_at->format('Y-m-d') }}</span> in <a href="{{ url($post->category->path()) }}">{{ $post->category->title ?? '' }}</a>
                                </div>

                                <div class="mb-4">
{{-- {{ $post->getImagePath() }} --}}
                                
                                    @if ($images = $post->getMedia('articles'))
                                        @if (!file_exists(public_path($post->getImagePath())))
                                        @foreach($post->media as $mediafile)
                                            @php
                                            $_filename = str_replace('-thumb','',$mediafile->file_name);
                                            @endphp
                                            <img src="{{ asset('storage/'.$mediafile->id.'/'.$_filename) }}" alt="{{ $post->title }}" itemprop="image" class="shadow-2">
                                            @break
                                            @endforeach
                                            
                                        @elseif ($images->count() == 1)
                                            <img itemprop="image" class="shadow-2" src="{{ asset($post->getImagePath()) }}" alt="...">
                                        @else
                                            <div class="gallery gallery-4-type4" data-provide="photoswipe">
                                                @if ($first = $images->first())
                                                <a class="gallery-item" href="{{ $first->getUrl() }}">
                                                    <img src="{{ asset($first->getUrl()) }}" alt="{{ $first->name }} ">
                                                </a>
                                                @endif

                                                <div class="gallery-item-group">

                                                    @php $images->shift() @endphp

                                                    @if ($items = $images->splice(0, 3))

                                                        @foreach($items as $item)
                                                        <a class="gallery-item" href="{{ asset($item->getUrl()) }}">
                                                            @if ($loop->last && $images->count() != 0)
                                                            <div class="gallery-item-overlay">+{{ $images->count() }}</div>
                                                            @endif
                                                            <img src="{{ asset($item->getUrl()) }}" alt="...">
                                                        </a>
                                                        @endforeach
                                                    @endif
                                                </div>


                                                <div class="gallery-extra-items">
                                                    @if ($images->count())
                                                        @foreach($images as $item)
                                                        <a class="gallery-item" href="{{ $item->getUrl() }}">
                                                            <img src="{{ $item->getUrl() }}" alt="...">
                                                        </a>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                </div>

                                <div class="articleBody" itemprop="articleBody" class="mb-7">
                                    {!! Blade::compileString($post->body) !!}
                                </div>

                            </article>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</main>
@endsection

