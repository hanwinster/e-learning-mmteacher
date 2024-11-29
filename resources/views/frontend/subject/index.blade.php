@extends('frontend.layouts.default')

@section('title', __('Subjects'))

@section('header')
<div class="section mb-0 pb-0">
<header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
    <canvas class="constellation" data-radius="0"></canvas>        
                @include('frontend.resource.partials.search')   
</header>
</div>
@endsection

@section('content')
<main class="main-content">

    <section class="section overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h3>{{ __('Subjects') }}</h3>
                    <ul class="list">
                    @foreach ($subjects as $subject)
                    <li><a href="{{ $subject->path() }}">{{ $subject->title }}<a></li>
                    @endforeach
                </ul>
                </div>
                <div class="col-md-9">
                    <div data-provide="shuffle">
                        <div class="row gap-y gap-2" data-shuffle="list">
                            @foreach ($posts as $post)

                      <div class="col-md-4" data-shuffle="item" data-groups="g_{{ $post->id }}">
                        <a class="hover-move-up" href="{{ $post->path() }}">

                                <img src="{{ asset('assets/img/logo-dark@2x.png') }}" alt="{{ $post->title }}">
                          <div class="text-center pt-5">
                            <h5 class="fw-5001 mb-0">{{ $post->title }}</h5>
                            <small class="small-5 text-lightest text-uppercase ls-2">{{ $post->subject->title ?? '' }}</small>
                          </div>
                        </a>
                      </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>



    <!--
          |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
          | Get a Quote
          |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
          !-->
{{--     <section class="section bg-gray text-center">

        <h2 class="mb-6">Get a Quote</h2>
        <p class="lead text-muted">We’ve completed more than 100+ project for our amazing clients, if you interested?</p>
        <hr class="w-50px">
        <a class="btn btn-lg btn-round btn-success" href="#">Design your site now</a>

    </section> --}}


</main>
<!-- /.main-content -->
@endsection
@section('css')
@parent
<style>
	@foreach($slides as $slide)
		.slide-{{ $slide->id }} {
			background-image: url({{ asset($slide->getFirstMedia('slides')->getUrl()) }})
		}
	@endforeach
</style>
@endsection
