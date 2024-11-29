@extends('backend.layouts.default')

@section('title', __('FAQ Category'))

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.faq.index') }}">{{ __('FAQ Category') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id))
                                {{ __('Edit FAQ') }}
                            @else 
                                {{ __('Add FAQ') }}
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
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' =>
                            array('admin.faq-category.update',
                            $post->id) , 'class' => 'form-horizontal')) !!}
                            @else
                            {!! \Form::open(array('files' => true, 'route'
                            => 'admin.faq-category.store', 'class' => 'form-horizontal')) !!}
                            @endif

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">@lang('Title')&nbsp;<span class="required">*</span></label>
                                        <input type="text" placeholder="Title.." name="title" id="title"
                                            class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                            value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="slug">@lang('Slug')&nbsp;<span class="required">*</span>
                                            <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                                data-placement="top"
                                                data-original-title="The user friendly and part of a URL which identifies a particular Category on a website in a form readable by users. e.g., http://example.com/{about-us}"></i>
                                        </label>
                                        <input type="text" placeholder="Slug.." name="slug" id="slug"
                                            class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                                            value="{{ old('slug', isset($post->slug) ? $post->slug: '') }}">
                                        {!! $errors->first('slug', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="body">@lang('Body')</label>
                                        <textarea data-height="200"
                                            class="form-control summernote {{ $errors->has('body') ? ' is-invalid' : '' }}" rows="5"
                                            name="body"
                                            id="body">{{ old('body', isset($post->body) ? $post->body: '') }}</textarea>
                                        {!! $errors->first('body', '<p class="invalid-feedback">:message</p>') !!}
                                    </div>

                                    @can('publish_faq_category')
                                    <div class="form-group">
                                        <div><label for="published_yes" class="col-xs-12">@lang('Published')</label></div>
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), 
                                                ['id' => 'published_yes', 'class' => 'form-check-input']) }}
                                            <label for="published_yes" class="form-check-label">@lang('Yes')</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ),
                                                 ['id' => 'published_no', 'class' => 'form-check-input']) }}
                                            <label for="published_no" class="form-check-label">@lang('No')</label>
                                        </div>
                                        {!! $errors->first('published', '
                                        <div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    @endcan
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        @if (auth()->user()->can('add_faq_category') ||
                                        auth()->user()->can('edit_faq_category'))
                                        <button class="btn btn-primary btn-sm" type="submit" name="btnSave" value="1">
                                            {{ __('Save') }}
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.faq-category.index') }}"
                                            class="btn btn-outline-dark btn-sm">{{ __('Cancel') }}</a>
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
