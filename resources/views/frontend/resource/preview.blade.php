@extends('frontend.layouts.preview')
@section('title', 'Preview - '. $post->title . ' - '. __('Resource'))

@section('content')
<main class="main-content" id="app-root">
    <section class="section bb-1 pt-7">
        <div class="container">
            @if (isset($isPreview) && $isPreview)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        {{ __('Preview Mode') }}
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-8 mb-6">
                    <h1> {{ $post->title }}</h1>

                    @if (config('cms.sharing_enabled'))
                    <div class="mt-5 mb-3">
                        <div class="addthis_inline_share_toolbox"></div>
                    </div>
                    @endif
                    @php
                    $cover_images = $post->getMedia('resource_cover_image');
                    @endphp
                    @foreach($cover_images as $image)
                    <a target="_blank" href="{{ asset($image->getUrl()) }}">
                    <img src="{{ asset($image->getUrl('large')) }}" class="shadow-4 rounded-lg">
                    </a>
                    @endforeach

                    @if (isset($post->description))
                    <div class="mt-7">
                    {{ strip_tags($post->description)  }}
                    </div>
                    @endif

                    <hr>
                    <h3>{{ __('Information') }}</h3>
                    <table class="table table-bordered mt-5">
                        <tbody>
                            <tr>
                                <td width="250">{{ __('Author') }}</td>
                                <td>{{ $post->author ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Resource Format') }}</td>
                                <td>
                                    {{ ($post->resource_format && array_key_exists($post->resource_format, $resource_formats))? $resource_formats[$post->resource_format]
                                    : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ __('Subject(s)/ Learning Area(s)') }}
                                </td>
                                <td>
                                    @if ($post->subjects)
                                    @foreach($post->subjects as $subject)
                                    {{ $subject->title ?? '' }}@if (!$loop->last), @endif
                                    @endforeach
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Publisher') }}</td>
                                <td>
                                    {{ $post->publisher ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Publishing Year') }}/{{ __('Publishing Month') }}</td>
                                <td>
                                    {{ $post->publishing_year ?? '' }}/{{ $post->publishing_month ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Strand') }}</td>
                                <td>
                                    {{ $post->strand ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Sub Strand') }}</td>
                                <td>
                                    {{ $post->sub_strand ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Lesson') }}</td>
                                <td>
                                    {{ $post->lesson ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Keywords') }}</td>
                                <td>
                                   @if ($keywords)
                                   @foreach($keywords as $keyword)
                                        {{ $keyword }}@if (!$loop->last), @endif
                                   @endforeach
                                   @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Keywords by Other') }}</td>
                                <td>
                                   @if ($otherKeywords)
                                   @foreach($otherKeywords as $keyword)
                                        {{ $keyword }}@if (!$loop->last), @endif
                                   @endforeach
                                   @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ __('URL') }}
                                </td>
                                <td>
                                    {{ $post->url ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ __('Additional Information') }}
                                </td>
                                <td>
                                    {{ $post->additional_information ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Suitable for Education College Year(s)') }}</td>
                                <td>
                                    @if ($post->years)
                                    @foreach($post->years as $year)
                                    {{ $year->title ?? '' }}@if (!$loop->last), @endif
                                    @endforeach
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('License') }}</td>
                                <td>
                                    {{ $post->license->title ?? '' }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <div>
                        <div class="text-center row">
                            <div class="col">
                                @include('frontend.resource.partials.rating-star', ['rating' => $post->averageRating() ])
                                <div>
                                    @if ($post->reviews->count())
                                    {{ $post->averageRating() }} / 5 ({{ $post->reviews->count() }}) {{ __('reviews') }}
                                    @else
                                    <div>Be the first to leave a <a href="{{ route('resource.show', $post->slug) }}#reviews">review</a></div>
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                @if (auth()->check())
                                <div>
                              {{--       <favourite-component
                                        :post={{ $post->id }}
                                        :favorited={{ $post->favourited() ? 'true' : 'false' }}
                                    ></favourite-component> --}}

                                    <favourite-component :post={{ $post->id }} :favourited={{ $post->favourited() ? 'true' : 'false' }}></favourite-component>
                                </div>
                                @endif
                            </div>
                        </div>
                        <br>

                        <div class="resource-description__list">
                            <div class="resource-description__list__item">
                                <span class="resource-description__item__title">{{ __('Resource ID') }}</span><span>{{ $post->id }}</span>
                            </div>
                            <div class="resource-description__list__item">
                                <span class="resource-description__item__title">{{ __('Resource Format') }}</span><span>{{ $post->getResourceFormat() }}</span>
                            </div>
                            <div class="resource-description__list__item">
                                <span class="resource-description__item__title">{{ __('Total Views') }}</span><span>{{ $post->total_page_views }}</span>
                            </div>
                            <div class="resource-description__list__item">
                                <span class="resource-description__item__title">{{ __('Total Downloads') }}</span><span>{{ $post->total_downloads }}</span>
                            </div>
                            <div class="resource-description__list__item">
                                <span class="resource-description__item__title">{{ __('Created') }}</span><span>{{ $post->created_at }}</span>
                            </div>
                            <div class="resource-description__list__item">
                                <span class="resource-description__item__title">{{ __('Updated') }}</span><span>{{ $post->updated_at }}</span>
                            </div>
                            <div class="resource-description__list__item">
                                <div class="profile-author">
                                    <div class="profile-author__logo">
                                        <a href="{{ route('profile.show', $post->user->username) }}">
                                        <img class="profile-author__img" src="{{ $post->user->getThumbnailPath() }}" alt="{{ $post->user->name }}">
                                        </a>
                                    </div>
                                    <div class="profile-author__author__description">
                                        <div>{{ __('Uploaded by') }}</div>
                                        <a href="{{ route('profile.show', $post->user->username) }}">
                                        <div class="profile-logo__author__title">{{ $post->user->name ?? '' }}</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
{{--                         @if ($post->favourited())
                        <a href="{{ route('resource.unfavourite', $post->id) }}" class="btn btn-primary">{{ __('Remove from favourite') }}</a>
                        @else
                        <a href="{{ route('resource.favourite', $post->id) }}" class="btn btn-primary">{{ __('Add to favourite') }}</a>
                        @endif --}}
                    </div>
                </div>

            </div>
            <hr>
        </div>
    </section>
</main>
@endsection
