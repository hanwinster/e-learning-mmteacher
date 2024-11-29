@extends('frontend.layouts.default')

@section('title', $category->title)

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
            <li class="breadcrumb-item"><a href="{{ route('article.index') }}">{{ __('Media') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ url($category->path()) }}">{{ $category->title }}</a></li>
        </ol>
    </nav>
    </div>
</div>
</div>
@endsection

@section('content')
<main class="main-content pt-7">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                            @include('frontend.article.partials.search')
                            @include('frontend.article.partials.categories')
                    </div>

                    <div class="col-md-9 text-center">
                    <div>
                        <div class="row gap-y gap-2">
                                @foreach ($posts as $post)
                               <div class="col-md-4">
                                @include('frontend.article.partials.classic')
                               </div>
                                @endforeach
                                <div>
                                    {{ $posts->links() }}
                                </div>
                            </div>
                    </div>
                    </div>


                </div>
            </div>
        </section>
</main>
@endsection
