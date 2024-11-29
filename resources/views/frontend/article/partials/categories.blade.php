<h3>{{ __('Categories') }}</h3>
<div class="list-group">
@foreach ($categories as $category)
<a href="{{ $category->path() }}" class="list-group-item list-group-item-action">{{ $category->title }}</a>
@endforeach
</div>

<br>
