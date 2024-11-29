@extends('frontend.layouts.default')

@section('title', $current->name.' FAQs')

@section('breadcrumbs')
<div class="bg-gray">
<div class="container">
    <div class="row">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('/faq') }}">{{ __('FAQs') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $current->title }}</li>
        </ol>
    </nav>
    </div>
</div>
</div>
@endsection

@section('header')
<div class="section mb-0 pb-0">
<header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
    @include('frontend.resource.partials.search')
</header>
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="pt-0">
        <div class="container">
            <!--<header class="section-header">-->
                <h2>{{ $current->title }}</h2>
            <!--</header>-->

            <div class="row">

				<div class="col-md-3">
					<h3>{{ __('Categories') }}</h3>
					<div class="list-group">
						@foreach ($categories as $category)
							<a href="{{ $category->path() }}" class="list-group-item list-group-item-action">{{ $category->title }}</a>
						@endforeach
					</div>
				</div>

                <div class="col-md-9 mx-auto">

                    <div class="accordion accordion-connected border" id="accordion-1">
                        @foreach($posts as $post)
                        <div class="card">
                            <div class="card-title">
                                <a data-toggle="collapse" href="#collapse-{{ $post->id }}">

								@php
								echo htmlspecialchars_decode(
									htmlentities(
										strip_tags($post->question, '<a>'),
										ENT_NOQUOTES, 'UTF-8', false)
									, ENT_NOQUOTES
								);
								@endphp
								</a>
                            </div>

                            <div id="collapse-{{ $post->id }}" class="collapse" data-parent="#accordion-1">
                                <div class="card-body">

									@php
									echo htmlspecialchars_decode(
										htmlentities(
											strip_tags($post->answer, '<a>'),
											ENT_NOQUOTES, 'UTF-8', false)
										, ENT_NOQUOTES
									);
									@endphp
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

<br><br>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
