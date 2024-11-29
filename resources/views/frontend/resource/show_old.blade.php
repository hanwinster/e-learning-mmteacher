@extends('frontend.layouts.default')

@section('title', 'Resource')

{{-- section('navbar-css', 'navbar-light') --}}

@php $img_url = 'assets/img/logo-dark@2x.png'; @endphp

@section('og_image', asset($img_url))
@section('meta_description', str_limit(strip_tags($post->description), 200))
@section('og_description', str_limit(strip_tags($post->description), 200))
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
	<section class="section bb-1">
		<div class="container">
                
			<div class="row">
				<div class="col-md-9 mb-6">
					<h2> {{ $post->title }}</h2>
					
					<div class="page-meta mb-5">
						{{ __('Posted on ') }}<span class="text-gray" itemprop="datePublished">{{ $post->updated_at->format('Y-m-d') }}</span></a>
					</div>	
					
					@php
						$cover_images = $post->getMedia('resource_cover_image');
					@endphp	
					
					@foreach($cover_images as $image)
					<a target="_blank" href="{{ asset($image->getUrl()) }}">
						<img src="{{ asset($image->getUrl('large')) }}" class="shadow-4 rounded-lg">
					</a>
					@endforeach	
				
					{{-- Preview 
						@php
						$preview_images = $post->getMedia('resource_previews');
						@endphp	
								
						@foreach($preview_images as $image)
							@if($image->getCustomProperty('iframe'))
								@php
									$iframe = $image->getCustomProperty('iframe');
									echo htmlspecialchars_decode(
										htmlentities(
											$iframe, 
											ENT_NOQUOTES, 'UTF-8', false)
										, ENT_NOQUOTES
									); 
								@endphp
							@elseif(substr($image->mime_type, 0, 5) == 'image')
								<a target="_blank" href="{{ asset($image->getUrl()) }}">
									<img src="{{ asset($image->getUrl('large')) }}" class="shadow-4 rounded-lg">
								</a>
							@elseif(substr($image->mime_type, 0, 5) == 'audio')	
							{{ dd(pathinfo($image->getUrl(), PATHINFO_EXTENSION)) }}
								<audio controls>
									<source src="{{ asset($image->getUrl()) }}" type="{{ $image->mime_type }}">
									Your browser does not support the audio element.
								</audio> 
							@endif
										
						@endforeach	
					--}}
				</div>	

				<div class="col-md-3">
                    @include('frontend.resource.partials.latest')
                </div>
			</div>  <!----------------------- Preview --------------------------------->
			
			<div class="row mb-6">
            

				<div class="col-md-12">
						
					<div class="rating">
						<label class="fa fa-star"></label>
						<label class="fa fa-star"></label>
						<label class="fa fa-star"></label>
						<label class="fa fa-star empty"></label>
						<label class="fa fa-star empty"></label>
					</div>
					
					<ul class="mt-7"> <!--<ul class="project-detail mt-7">-->
						<li>
							<strong>{{ __('Author') }} : {{ $post->author ?? '' }}</strong>
							<!-- <span></span>-->
						</li>
						
						<li>
							<strong>{{ __('Format') }} : {{ ($post->resource_format && array_key_exists($post->resource_format, $resource_formats))? $resource_formats[$post->resource_format] : '' }}</strong>
							
						</li>

						<li>
							<strong>{{ __('License') }} : {{ $license ? $license->title : '' }}</strong>
							
						</li>

						<li>
							<strong>{{ __('Publisher') }} :  {{ $post->publisher ?? '' }}</strong>
							
						</li>
						
						<li>
							<strong>{{ __('Publishing Year') }} : {{  $post->publishing_year ?? '' }}</strong>
						
						</li>							
						
						<li>
							<strong>{{ __('Strand') }} : {{ $post->strand ?? '' }}</strong>
						
						</li>
						
						<li>
							<strong>{{ __('Sub Strand') }} : {{ $post->sub_strand ?? '' }}</strong>
							
						</li>
						
						<li>
							<strong>{{ __('URL') }} : {{ $post->url ?? '' }}</strong>
							
						</li>
						
						<li>
							<strong>{{ __('Additional Information') }} : {{ $post->additional_information ?? '' }}</strong>
							
						</li>
						
						<li>
							<strong>{{ __('Suitable for EC year') }}  :
							
							@php
								$ec_years = array();
								if($post->suitable_for_ec_year)
								{
									if (strpos($post->suitable_for_ec_year, ',') !== false) {
										$ec_years = explode(',', $post->suitable_for_ec_year);
									}
									else $ec_years[] = $post->suitable_for_ec_year;
								}
								
								foreach($ec_years as $ec_year) echo $years[$ec_year] . ", ";
								
							@endphp		
														
							</strong>
						</li>
						
						{{--
						<li>
							<strong>{{ __('Description') }}</strong>
							<span>
							
							@if (isset($post->description))
								@php
									$sample = str_replace('{path}', asset(''), $post->description); 
									
									echo htmlspecialchars_decode(
										htmlentities(
											$sample, 
											ENT_NOQUOTES, 'UTF-8', false)
										, ENT_NOQUOTES
									); 
								@endphp
							
							@endif
							
							</span>
						</li>
						--}}
					</ul>
						
					{{--
					<p>Format: {{ title_case($post->resource_format) }} 
					@php
					//dd($resource_formats);
					//if(array_key_exists($post->resource_format, $resource_formats)) echo $resource_formats[$post->resource_format];
					
					@endphp
					</p>	
					--}}
					
			   
					@if (isset($post->description))
					
					<p> 
					<?php 
						$sample = str_replace('{path}', asset(''), $post->description); 
						/*
						$needle = ".";
						
						$pos1 = strpos($sample, $needle);
						$pos2 = strpos($sample, $needle, $pos1 + strlen($needle));
						$pos3 = strpos($sample, $needle, $pos2 + strlen($needle));
						
						
						//$description = substr($sample, 0, strpos($sample, ".")+1);
						$description = substr($sample, 0, $pos3 +1 );*/
						
						echo htmlspecialchars_decode(
							htmlentities(
								$sample, 
								ENT_NOQUOTES, 'UTF-8', false)
							, ENT_NOQUOTES
						); 
					?>
					</p>
					
					@endif	
					
				</div>
			
				<!--<div class="col-md-4 mb-5 mb-md-0"><!------------------------ Cover ----------------------------
					@php
					$images = $post->getMedia('resource_cover_image');
					@endphp				
					
					@foreach($images as $image)
					<a target="_blank" href="{{ asset($image->getUrl()) }}">
						<img src="{{ asset($image->getUrl('medium')) }}" class="shadow-4 rounded-lg" alt="">
					</a>						
					@endforeach	

					<ul class="project-detail mt-7">
                            <li>
                                <strong>{{ __('Author') }}</strong>
                                <span>{{ $post->duration ?? '' }}</span>
                            </li>

                            <li>
                                <strong>{{ __('License') }}</strong>
                                <span>{{ $post->price ?? '' }}</span>
                            </li>

                            <li>
                                <strong>{{ __('Publisher') }}</strong>
                                <span>{{  $post->location ?? '' }}</span>
                            </li>
                        </ul>
				</div>	-->			

		
		</div>
		  
		
			<div class="row">
				
				<!--<div class="col-md-6 mb-md-0 mt-5">--> 
					@php
					$preview_images = $post->getMedia('resource_previews');
					@endphp	
							
					@foreach($preview_images as $image)
						@if($image->getCustomProperty('iframe'))
							<div class="col-md-6 mb-md-0 mt-5">
							@php
								$iframe = $image->getCustomProperty('iframe');
								echo htmlspecialchars_decode(
									htmlentities(
										$iframe, 
										ENT_NOQUOTES, 'UTF-8', false)
									, ENT_NOQUOTES
								); 
							@endphp
							</div>
						@elseif(substr($image->mime_type, 0, 5) == 'image' || $image->mime_type == 'application/pdf' )
						<div class="col-md-6 mb-md-0 mt-5">
							<a target="_blank" href="{{ asset($image->getUrl()) }}">
								<img src="{{ asset($image->getUrl('medium')) }}" class="shadow-4 rounded-lg">
							</a>
						</div>
						@elseif(substr($image->mime_type, 0, 5) == 'audio')	
						{{ dd(pathinfo($image->getUrl(), PATHINFO_EXTENSION)) }}
						<div class="col-md-6 mb-md-0 mt-5">
							<audio controls>
								<source src="{{ asset($image->getUrl()) }}" type="{{ $image->mime_type }}">
								Your browser does not support the audio element.
							</audio> 
						</div>
						@else
							<div class="col-md-6 mb-md-0 mt-5">
							<a target="_blank" href="{{ asset($image->getUrl()) }}">
								<img src="{{ asset($image->getUrl('medium')) }}" class="shadow-4 rounded-lg">
							</a>
						</div>
						@endif
									
					@endforeach			
				<!--</div>-->
				
					@if($post->allow_download)
			
						@php
							$resources = $post->getMedia('resource_full_version_files');
						@endphp
						
						@foreach($resources as $resource)
						{{--
						<div class="col-md-4 mb-md-0 mt-5">
							<a target="_blank" href="{{ asset($resource->getUrl()) }}" class="btn btn-lg btn-outline-info"><i class="fa fa-download" aria-hidden="true"></i> 				
								Download Full File
							</a>
						</div>
						--}}				
					
						<div class="col-md-4 mb-md-0 mt-5">
							<a href="{{ route('resource.download', $resource) }}" class="btn btn-lg btn-outline-info"><i class="fa fa-download" aria-hidden="true"></i>
								Download Full File
							</a>
						</div>						
					
						@endforeach							
					@endif
			</div>
			
		</div>
	</section>
</main>	

<!--<main class="main-content">
        <section class="section bb-1">
            <div class="container">

                <div class="row">
                    <div class="col-md-8 mb-6 mb-md-0">
                        <img src="{{ asset('assets/img/logo-dark@2x.png') }}" alt="{{ $post->title }}">
                    </div>


                    <div class="col-md-4">
                        <ul class="project-detail mt-7">
                            <li>
                                <strong>{{ __('Author') }}</strong>
                                <span>{{ $post->duration ?? '' }}</span>
                            </li>

                            <li>
                                <strong>{{ __('License') }}</strong>
                                <span>{{ $post->price ?? '' }}</span>
                            </li>

                            <li>
                                <strong>{{ __('Publisher') }}</strong>
                                <span>{{  $post->location ?? '' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                    <div class="project-description">
                        {!! $post->description !!}
                    </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
	-->
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
