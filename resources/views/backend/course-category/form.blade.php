@extends('backend.layouts.default')

@section('title', __('Course Category'))

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.course-category.index') }}">{{ __('Course Categories') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)){{ __('Edit Course Category') }}
                            @else {{ __('Add A Course Category') }}
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
                <div class="col-md-12">
                    <!-- Default Elements -->
                    <div class="card">
                        <h4 class="card-title">
                            @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                        </h4>
                        <div class="card-body">
                            @if (isset($post))
                                {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.course-category.update',
                                $post->id) , 'class' => 'form-horizontal')) !!}
                            @else
                                {!! \Form::open(array('files' => true, 'route'
                                => 'admin.course-category.store', 'class' => 'form-horizontal')) !!}
                            @endif
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label for="name" class="require">{{ __('Name') }}</label>
                                        <input type="text" placeholder="Name...." name="name" id="name"
                                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        @if (auth()->user()->can('add_article_category') || auth()->user()->can('edit_article_category'))
                                            <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">
                                                {{ __('Save') }}
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.course-category.create') }}"
                                        class="btn btn-secondary btn-md">{{ __('Reset') }}</a>
                                        <a href="{{ route('admin.course-category.index') }}"
                                        class="btn btn-outline-dark btn-md">{{ __('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <!-- END Default Elements -->
            </div>
        </div>
    </section>
</div>
@stop
