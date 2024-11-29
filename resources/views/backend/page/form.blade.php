@extends('backend.layouts.default')

@section('title', __('Page'))

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.page.index') }}">{{ __('Pages') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Pages') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="content">
                <div class="row">                 
                    <div class="col-12">
                        <!-- Default Elements -->
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h4 class="card-title">
                                    @if (isset($post->id)) [@lang('Edit')] #<strong title="ID">{{ $post->id }}</strong> @else [@lang('New')] @endif
                                </h4>
                            </div>
                            <div class="card-body">
                                @if (isset($post))
                                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.page.update',
                                    $post->id) ,
                                    'class' => 'form-horizontal')) !!}
                                @else
                                    {!! \Form::open(array('files' => true, 'route' => 'admin.page.store',
                                    'class' => 'form-horizontal')) !!}
                                @endif

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">{{ __('Title') }}&nbsp;<span class="required">*</span></label>
                                            <input type="text" placeholder="{{__('Title')}}.." name="title" id="title"
                                                class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="title_mm">{{ __('Title') }}&nbsp;({{ __('MM') }})</label>
                                            <input type="text" placeholder="{{__('Title')}}.." name="title_mm" id="title_mm"
                                                class="form-control{{ $errors->has('title_mm') ? ' is-invalid' : '' }}"
                                                value="{{ old('title_mm', isset($post->title_mm) ? $post->title_mm: '') }}">
                                            {!! $errors->first('title_mm', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="slug">{{ __('Slug') }}
                                                <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-original-title="The user friendly and part of a URL which identifies 
                                                    a particular page on a website in a form readable by users. e.g., http://example.com/{about-us}"></i>
                                            </label>
                                            <input type="text" placeholder="Slug.." name="slug" id="slug"
                                                class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                                                value="{{ old('slug', isset($post->slug) ? $post->slug: '') }}">
                                            {!! $errors->first('slug', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <label for="body">{{ __('Body') }}&nbsp;<span class="required">*</span></label>
                                        <div class="form-group">                                          
                                            <textarea data-height="200"
                                                class="form-control summernote {{ $errors->has('body') ? ' is-invalid' : '' }}" rows="5" name="body"
                                                id="body">{{ old('body', isset($post->body) ? $post->body: '') }}</textarea>
                                            {!! $errors->first('body', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <label for="body_mm">{{ __('Body') }}&nbsp;&nbsp;({{ __('MM') }})</label>
                                        <div class="form-group">                                          
                                            <textarea data-height="200"
                                                class="form-control summernote {{ $errors->has('body_mm') ? ' is-invalid' : '' }}" rows="5" name="body_mm"
                                                id="body">{{ old('body_mm', isset($post->body_mm) ? $post->body_mm: '') }}</textarea>
                                            {!! $errors->first('body_mm', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <label for="published">{{ __('Image') }}</label>
                                                {{ Form::file('uploaded_file') }}

                                                @if (isset($post))
                                                    @php
                                                        $images = $post->getMedia('pages');
                                                    @endphp

                                                    <div>
                                                        @foreach($images as $image)
                                                        <a target="_blank"
                                                            href="{{ $image->getFullUrl() }} {{-- asset($image->getUrl()) --}}">
                                                            <img src="{{ asset($image->getUrl('thumb')) }} {{-- asset($image->getFullUrl('thumb')) --}}"
                                                                style="border: 1px solid #ccc; margin-right: 10px">
                                                        </a>

                                                        <a onclick="return confirm('Are you sure you want to delete?')"
                                                            href="{{ route('admin.media.destroy', $image->id) }}">{{ __('Remove') }}</a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
                                            </div>
                                        </div>

                                        @can('publish_page')
                                        <div class="form-group">
                                            <div><label for="published_yes" class="col-xs-12">{{ __('Published') }}</label></div>
                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), ['id' => 'published_yes',
                                                            'class' => 'form-check-input']) }}
                                                <label for="published_yes" class="form-check-label">{{ __('Yes') }}</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ), ['id' => 'published_no',
                                                            'class' => 'form-check-input']) }}
                                                <label for="published_no" class="form-check-label">{{ __('No') }}</label>
                                            </div>
                                            {!! $errors->first('published', '
                                            <div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        @endcan

                                        <div class="form-group">
                                            @if (auth()->user()->can('add_page') || auth()->user()->can('edit_page'))
                                            <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">{{ __('Save') }}
                                            </button>
                                            {{--<button class="btn btn-secondary" type="submit" name="btnApply" value="1">Apply
                                                        </button>--}}
                                            @endif
                                            <a href="{{ route('admin.page.index') }}" class="btn btn-md btn-outline-dark">{{ __('Cancel') }}</a>
                                        </div>
                                    </div>     
                                </div>   
                                </form>
                            </div>                      
                        </div>
                    </div>                
                </div>   
            </div>
        </div>
    </section>
</div>
@stop
