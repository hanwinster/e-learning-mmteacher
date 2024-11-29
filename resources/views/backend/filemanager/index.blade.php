@extends('backend.layouts.default')
@section('title', 'Files')
@section('content')
<div class="card">
    <iframe src="{{ url(config('lfm.url_prefix')) }}" width="100%" height="600"></iframe>
</div>
@endsection