<div class="text-center mb-5">
	@if($profileThumbnail = auth()->user()->getThumbnailPath())
    <div class="form-group col-xs-12">
		<img src="{{ asset($profileThumbnail) }}">
    </div>
	@endif

    <div class="lead-3">{{ auth()->user()->name }}</div>

    <div class="text-gray">
        <small>
            <div>{{ __('Since: ') }} {{ auth()->user()->created_at->format('Y-m-d') }}</div>
            <div>{{ __('Type: ') }} {{ auth()->user()->getType() }}</div>
            <div>{{ __('Education College: ') }} {{ auth()->user()->college->title ?? '' }}</div>
        </small>
    </div>
</div>

<div class="list-group">
    @foreach ($links as $menu)
        <a href="{{ route($menu['route']) }}" class="list-group-item list-group-item-action {{ return_if(on_page($menu['url']), 'active') }}">
                {{ $menu['name'] }} - {{ $menu['route'] }}
        </a>
    @endforeach
</div>
