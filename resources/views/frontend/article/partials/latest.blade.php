<h3>{{ __('Latest ') . $post->category->title }}</h3>
<div class="list-group">
@foreach ($latest_articles as $latest_article)
<a href="{{ $latest_article->path() }}" class="list-group-item list-group-item-action">{{ $latest_article->title }}</a>
@endforeach
</div>