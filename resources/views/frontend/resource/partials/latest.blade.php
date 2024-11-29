<h3 class="mt-5">{{ __('Related Resources') }}</h3>
<div class="list-group">
@if (isset($related_resources))
@foreach ($related_resources as $latest_resource)
<a href="{{ $latest_resource->path() }}" class="list-group-item list-group-item-action">{{ $latest_resource->title }}</a>
@endforeach
@endif
</div>
