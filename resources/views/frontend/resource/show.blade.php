@extends('frontend.layouts.default')
@section('title', $post->title . ' - '. __('Resource'))

@php
    if ($cover_image = $post->getFirstMedia('resource_cover_image')) {
        $img_url = asset($cover_image->getUrl('large'));
    } else {
        $img_url = 'assets/img/og-img.jpg';
    }
@endphp


@section('og_image', asset($img_url))
@section('meta_description',
str_limit(strip_tags($post->description), 200))
@section('og_description', str_limit(strip_tags($post->description), 200))

@section('header')
<div class="section mb-0 pb-0">
    <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
    @include('frontend.resource.partials.search')
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
                    <li class="breadcrumb-item"><a href="{{ route('elibrary.index') }}">{{ __('eLibrary') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('content')
<main class="main-content" id="app-root">
    <section class="section bb-1 pt-7">
        <div class="container">
            @if (isset($isPreview) && $isPreview)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        {{ __('Preview Mode!') }}
                        @if (App\Repositories\ResourcePermissionRepository::canEdit($post))
                        <br>
                        Click <a href="{{ route('member.resource.edit', $post->id) }}">here</a> to edit.
                        @endif
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-8 mb-6">
                    <h1 class="article-title"> {{ $post->title }}</h1>

                    @if (config('cms.sharing_enabled'))
                    <div class="mt-5 mb-3">
                        <div class="addthis_inline_share_toolbox"></div>
                    </div>
                    @endif
                    @php
                    $preview_images = $post->getMedia('resource_previews');
                    @endphp
                    @foreach($preview_images as $image)
                    @if($image->getCustomProperty('iframe'))
                        <div class="lity-iframe-container">
                        @php
                        $iframe = $image->getCustomProperty('iframe'); echo htmlspecialchars_decode(
                        htmlentities( $iframe, ENT_NOQUOTES, 'UTF-8', false) , ENT_NOQUOTES );
                        @endphp
                        </div>
                    @elseif ($cover_image = $post->getFirstMedia('resource_cover_image'))
                    <!-- <img src="{{ asset($cover_image->getUrl('large')) }}" class="shadow-4 rounded-lg"> -->
                    <img src="{{ asset($cover_image->getUrl()) }}" class="shadow-4 rounded-lg">
                    {{-- {{  $post->getFirstMedia('resource_cover_image') }} --}}                    
                    @endif
                    
                    <hr>
                    @include('frontend.resource.partials.download', $post)
                    @endforeach
                    @if (isset($post->description))
                    <div class="mt-7 resourceBody">
                    {!! $post->description  !!}
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
                                    {{ $post->getResourceFormat() }}
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
                                    <a target="_blank" href="{{ $post->url ?? '' }}" rel="nofollow">{{ $post->url ?? '' }} <i class="fa fa-external-link" aria-hidden="true"></i>
                                    </a>
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

                    <div class="mt-4" id="elibrary_root__">
                        <button data-toggle="modal" data-target="#modal-link-report" class="btn btn-default" type="button">
                            <i class="fa fa-flag"></i>
                        {{ __('Report') }}
                        </button>

                        <link-report-component :logged_in="{{ Auth::check() ? 'true': 'false' }}" :resource_id="{{  isset($post->id) ? $post->id: '' }}"></link-report-component>
                    </div>
                    <div class="mt-4">
{{--                         @if ($post->favourited())
                        <a href="{{ route('resource.unfavourite', $post->id) }}" class="btn btn-primary">{{ __('Remove from favourite') }}</a>
                        @else
                        <a href="{{ route('resource.favourite', $post->id) }}" class="btn btn-primary">{{ __('Add to favourite') }}</a>
                        @endif --}}
                    </div>
                    @include('frontend.resource.partials.latest')
                </div>

            </div>
            <hr>
            @include('frontend.resource.partials.reviews', $post)

        </div>
    </section>
</main>
@endsection

@section('css')
@parent
<link rel="stylesheet" href="{{ asset('assets/js/jquery.star-rating-svg/star-rating-svg.css') }}">

@endsection

@section('script')
@parent
<script src="{{ asset('assets/js/jquery.star-rating-svg/jquery.star-rating-svg.js')}}"></script>
<script>
    new Vue({
    el: '#elibrary_root',
    data: {
        selectedResource: ''
    },

    mounted() {
    },
    methods: {
        chooseResource: function (passedResource) {
            this.selectedResource = passedResource;
        }
    }

});

</script>
<script type="text/javascript">

/*     $("#input-id").rating(); */
    $(".my-rating").starRating({
    starSize: 25,
    totalStars: 5,
    useFullStars: true,
    disableAfterRate: false,
    callback: function(currentRating, $el){
        $('#rating').val(currentRating);
    }
});

</script>
@endsection
