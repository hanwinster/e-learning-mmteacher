@extends('frontend.layouts.default')
@section('title', __('Advanced Search'))
@section('header')
    @include('frontend.search.partials.search-box')
@endsection

@section('content')
<main class="main-content">
    <section class="section bg-gray pt-7">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div>
                        <div class="row gap-y gap-2">
                            @if (isset($posts))
                            @foreach ($posts as $post)
                            <div class="col-md-9">
                                @include('frontend.resource.partials.list-item', $post)
                            </div>
                            @endforeach
                            @endif
                        </div>
                        @if (isset($posts))
                        <div class="mt-5">
                            {{ $posts->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- /.main-content -->
@endsection
