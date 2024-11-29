@extends('frontend.layouts.default')
@section('title', __('Article'). ' ['. (isset($post->id) ? 'Edit #'.$post->id : 'New ').']')

@section('header')
<div class="section mb-0 pb-0">
    <header class="text-white">
        <div class="container text-center h-50">
            <div class="row">
                <div class="col-md-8 mx-auto">
                   <!--<h1>{{ __('Article'). ' ['. (isset($post->id) ? 'Edit #'.$post->id : 'New ').']' }}</h1>-->
                </div>
            </div>
        </div>
    </header>
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container">
            <div class="row">

                <div class="col-md-3 mx-auto">
                    @include('frontend.member.partials.sidebar')
                </div>
                <div class="col-md-9 mx-auto">
                        <h4>
                                {{ __('Article') }}
                                @if (isset($post->id)) [Edit] @else [New] @endif
                            </h4>
                        @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.article.update', $post->id)
                            , 'class' => 'form-horizontal')) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route' => 'member.article.store',
                            'class' => 'form-horizontal')) !!}
                        @endif

                        <div class="form-group">
                            <label for="title" class="require">@lang('Title')</label>
                            <input type="text" placeholder="Title.." name="title" id="title"
                                   class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                   value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="col-xs-12 require">
                                @lang('Category')
                            </label>
                            {!! Form::select('category_id', $categories, old('category_id', isset($post->category_id)
                            ? $post->category_id: ''), ['class' => $errors->has('category_id') ? 'form-control is-invalid' : 'form-control']) !!}
                            {!! $errors->first('category_id', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="body" class="require">@lang('Body')</label>
                            <textarea class="form-control {{ $errors->has('body') ? ' is-invalid' : '' }}" rows="5" name="body"
                                      id="body">{{ old('body', isset($post->body) ? $post->body: '') }}</textarea>
                            {!! $errors->first('body', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="published">@lang('Image')</label>
                                {{ Form::file('uploaded_file') }}
                                @if (isset($post))
                                @php
                                $images = $post->getMedia('articles');
                                @endphp
                                <div>
                                @foreach($images as $image)
                                <a target="_blank" href="{{ asset($image->getUrl('thumb')) }}">
                                    {{-- <img src="{{ asset($image->getUrl('thumb')) }}"> --}}
                                    @if(!file_exists(public_path($post->getThumbnailPath())))
                                        <img src="{{ asset('storage/'.$image->id.'/'.$image->file_name) }}" alt="{{ $post->title }}" width="150px">                                        
                                    @else
                                        <img src="{{ asset($image->getUrl('thumb')) }}">
                                    @endif
                                </a>
                                <a onclick="return confirm('Are you sure you want to delete?')" href="{{ route('member.media.destroy', $image->id) }}">Remove</a>
                                @endforeach
                                </div>
                                @endif
                                {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>

                            <div class="form-group">
                                <div><label for="published_yes" class="col-xs-12">{{ __('Published') }}</label></div>
                                <div class="form-check form-check-inline">
                                    {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), 
                                        ['id' => 'published_yes', 'class' => 'form-check-input']) }}
                                    <label for="published_yes" class="form-check-label">{{ __('Yes') }}</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ),
                                         ['id' => 'published_no', 'class' => 'form-check-input']) }}
                                    <label for="published_no" class="form-check-label">{{ __('No') }}</label>
                                </div>
                                {!! $errors->first('published', '<p class="help-block">:message</p>') !!}
                            </div>

                        <div class="form-group">
                                @if (!config('cms.enable_article_notification'))
                                <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                                    {{ __('Save') }}
                                </button>
                                @else
                                <div class="btn-group">
                                    <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                                        {{ __('Save') }}
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" type="submit" name="btnSaveNotify" value="1">
                                        {{ __('Save & Notify') }}
                                        </button>
                                    </div>
                                </div>
                                @endif
                                {{--<button class="btn btn-secondary" type="submit" name="btnApply" value="1">Apply
                                </button>--}}
                            <a href="{{ route('member.article.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                        </div>

                        </form>
                    </div>
            </div>
        </div>
    </section>
</main>
@endsection

<!-- @section('css')
@parent
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
@endsection

@section('script')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
<script>
    $(document).ready(function() {
        $('#body').summernote({
            height: 200
        });
    });
</script>
@endsection -->
