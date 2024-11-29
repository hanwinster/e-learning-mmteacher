@extends('backend.layouts.default')

@section('title', __('Education Colleges'))

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.college.index') }}">{{ __('Education Colleges') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id))
                                {{ __('Edit Education College') }}
                            @else 
                                {{ __('Add Education College') }}
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
                                {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.college.update',
                                $post->id) , 'class' => 'form-horizontal')) !!}
                            @else
                                {!! \Form::open(array('files' => true, 'route'
                                => 'admin.college.store', 'class' => 'form-horizontal')) !!}
                            @endif
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label for="title" class="require">@lang('Title')</label>
                                        <input type="text" placeholder="Title.." name="title" id="title"
                                            class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                            value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="description">@lang('Description')</label>
                                        <textarea
                                                class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                rows="5" name="description"
                                                id="description">{{ old('description', isset($post->description) ? $post->description: '') }}</textarea>
                                        {!! $errors->first('description', '<p class="invalid-feedback">:message</p>') !!}
                                    </div>

                                    @can('publish_article_category')
                                        <div class="form-group">
                                            <div><label for="published_yes" class="col-xs-12">Published</label></div>
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
                                            {!! $errors->first('published', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                    @endcan
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        @if (auth()->user()->can('add_college') || auth()->user()->can('edit_college'))
                                            <button class="btn btn-primary btn-sm" type="submit" name="btnSave" value="1">
                                                {{ __('Save') }}
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.college.index') }}"
                                        class="btn btn-outline-dark btn-sm">{{ ('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END Default Elements -->
        </div>
    </section>
</div>
