@extends('frontend.layouts.default')

@section('title', $subject->title)

@php
$img_url= 'assets/img/logo-dark@2x.png';
@endphp
@section('og_image', asset($img_url))
@section('meta_description', str_limit(strip_tags($subject->description),
200))
@section('og_description', str_limit(strip_tags($subject->description), 200))

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
    <section class="section bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="accordion accordion-connected border" id="accordion-1">                        
						<div class="card">
							<div class="card-title" style="background-color:#FFF">
								<a data-toggle="collapse" href="#collapse-subject" style="font-size:20px">
								{{ __('Subjects') }}
								</a>
							</div>
							@foreach ($subjects as $subject)
							<div id="collapse-subject" class="collapse" data-parent="#accordion-1">
								<div class="card-body" style="border-bottom:1px solid #D9DADB">
								   <a href="{{ $subject->path() }}" class="">{{ $subject->title }}</a>
								</div>
							</div>
							@endforeach
						</div>
						
						<br>
					
					<div class="accordion accordion-connected border" id="accordion-1">                        
                        <div class="card">
                            <div class="card-title" style="background-color:#FFF">
                                <a data-toggle="collapse" href="#collapse-format" style="font-size:20px">
								{{ __('Resource Format') }}
								</a>
                            </div>
							@foreach ($formats as $key => $format)
                            <div id="collapse-format" class="collapse" data-parent="#accordion-1">
                                <div class="card-body" style="border-bottom:1px solid #D9DADB">
                                   <a href="{{ url('/resource_format/' . $key) }}" class="">{{ $format }}</a>
                                </div>
                            </div>
							@endforeach
                        </div>
                        
                    </div>
						
					</div>
                </div>
                <div class="col-md-9">
                    <div>
                        <div class="row gap-y gap-2">
                            @foreach ($posts as $post)

                            <div class="col-md-4">
                            @include('frontend.resource.partials.card', $post)
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
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