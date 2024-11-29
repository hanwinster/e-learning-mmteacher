@extends('backend.layouts.default')

@section('title', __('Slide'))

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12"> 
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.slide.index') }}">{{ __('Slides') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id))
                                {{ __('Edit Slide') }}
                            @else 
                                {{ __('Add Slide') }}
                            @endif
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4 class="card-title">
                                @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.slide.update',
                            $post->id) ,
                            'class' => 'form-horizontal')) !!}
                            @else
                            {!! \Form::open(array('files' => true, 'route' => 'admin.slide.store',
                            'class' => 'form-horizontal')) !!}
                            @endif

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label for="title">@lang('Title')&nbsp;
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" placeholder="Title.." name="title" id="title"
                                            class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                            value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="weight">{{ __('Weight') }}&nbsp;
                                            <span class="required">*</span></label>
                                        <input type="text" placeholder="0 (or) must be integer" name="weight" id="weight"
                                            class="form-control{{ $errors->has('weight') ? ' is-invalid' : '' }}"
                                            value="{{ old('weight', isset($post->weight) ? $post->weight: '') }}">
                                        {!! $errors->first('weight', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="">@lang('Description')</label>
                                        <textarea data-provide="summernote" data-height="200"
                                            class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5"
                                            name="description"
                                            id="description">{{ old('description', isset($post->description) ? $post->description: '') }}</textarea>
                                        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label for="published">@lang('Image')&nbsp;
                                                <span class="required">*</span>
                                            </label>
                                            {{ Form::file('uploaded_file') }}

                                            @if (isset($post))
                                                @php
                                                    $images = $post->getMedia('slides');
                                                @endphp

                                                @foreach($images as $image)
                                                    <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                                        <img src="{{ asset($image->getUrl('thumb')) }}">
                                                    </a>
                                                    <a onclick="return confirm('Are you sure you want to delete?')"
                                                        href="{{ route('admin.media.destroy', $image->id) }}">@lang('Remove')</a>
                                                @endforeach
                                            @endif

                                            {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>')
                                            !!}
                                        </div>
                                    </div>

                                    @can('publish_page')
                                    <div class="form-group">
                                        <div><label for="published_yes" class="col-xs-12">Published</label></div>
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), ['id' => 'published_yes',
                                                'class' => 'form-check-input']) }}
                                            <label for="published_yes" class="form-check-label">@lang('Yes')</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ), ['id' => 'published_no',
                                                'class' => 'form-check-input']) }}
                                            <label for="published_no" class="form-check-label">@lang('No')</label>
                                        </div>
                                        {!! $errors->first('published', '
                                        <div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    @endcan

                                    <div class="form-group">
                                        @if (auth()->user()->can('add_page') || auth()->user()->can('edit_page'))
                                        <button class="btn btn-primary btn-sm" type="submit" name="btnSave" value="1">{{ __('Save') }}
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.slide.index') }}" class="btn btn-outline-dark btn-sm">{{ __('Cancel') }}</a>
                                    </div>
                                </div>

                            </div>
                            </form>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
