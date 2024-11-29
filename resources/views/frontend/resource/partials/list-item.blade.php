<div class="card hover-shadow-7 my-3 p-3">
    <div class="row">
        <div class="col-md-4">
            <a href="{{ url($post->path()) }}">
                @if ($image_url = $post->getImagePath())
                <img class="fit-cover h-150" src="{{ asset($image_url) }}" alt="{{ $post->title }}">
                @else
                <img class="fit-cover h-150" src="{{ asset('assets/img/vector/3.png') }}" alt="{{ $post->title }}">
                @endif
                </a>
        </div>

        <div class="col-md-6">
            <div class="p-0">
                <h5 class="m-0 overflow-hidden"><a href="{{ url($post->path()) }}">{{ $post->title }}</a></h5>
                <small class="m-0 text-black-50">
                    <span class="text-info">{{ title_case($post->getResourceFormat()) }}</span> - <i>{{ __('by') }}</i> {{ $post->author ?? '' }}
                </small>
                <p>
                    {{ str_limit(strip_tags($post->description), 100, '...') }}
                </p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-1">

            </div>
            <div class="text-center">
                <small>
                <i>{{__('Last Updated') }}</i>
                {{ $post->updated_at->format('Y-m-d') }}
                </small>
            </div>
            <div class="mt-1">
                <a class="btn btn-flat btn-sm" href="{{ route('resource.preview', $post->id) }}" data-provide="lightbox">{{ __('Preview') }}</a>
            </div>
        </div>
    </div>
</div>
