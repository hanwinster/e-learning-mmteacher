@extends('backend.layouts.default')

@section('title', __('Course Category'))

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.course-type.index') }}">{{ __('Course Types') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)){{ __('Edit Type') }}
                            @else {{ __('Add Type') }}
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
                                @if (isset($post->id)) [{{ __('Edit') }}] #<strong title="ID">{{ $post->id }}</strong>
                                @else [{{ __('New') }}]
                                @endif
                            </h4>
                        </div>                  
                        <div class="card-body">
                            @if (isset($post))
                                {!! \Form::open(array('files' => true, 'method' => 'put',
                                'route' => array('admin.course-type.update', $post->id) , 'class' => 'form-horizontal')) !!}
                            @else
                                {!! \Form::open(array('files' => true, 'method' => 'post',
                                'route' => 'admin.course-type.store', 'class' => 'form-horizontal')) !!}
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name" class="require">{{ __('Name') }}</label>
                                        <input type="text" placeholder="{{ __('Name') }}" name="name" id="name" 
                                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                            value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        @if (auth()->user()->can('add_course_category') ||
                                        auth()->user()->can('edit_course_category'))
                                        <button class="btn btn-primary btn-sm" type="submit" name="btnSave" value="1">
                                            {{ __('Save') }}
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.course-type.create') }}" class="btn btn-secondary btn-sm">
                                            {{ __('Reset') }}
                                        </a>
                                        <a href="{{ route('admin.course-type.index') }}" class="btn btn-outline-dark btn-sm">
                                            {{ __('Cancel') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                           </form>
                    </div>
                </div>
                 <!-- /col-12 -->
            </div>
        </div>
    </section>
</div>
@endsection