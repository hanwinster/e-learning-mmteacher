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
                        <li class="breadcrumb-item"><a href="{{ route('admin.course-evaluation.index') }}">{{ __('Course Evaluations') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)){{ __('Edit Evaluation') }}
                            @else {{ __('Add Evaluation') }}
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
                    <div class="card">
                        <h4 class="card-title">
                            @if (isset($post->id)) [{{ __('Edit') }}] #<strong title="ID">{{ $post->id }}</strong>
                            @else [{{ __('New') }}]
                            @endif
                        </h4>
                        <div class="card-body">
                            @if (isset($post))
                                {!! \Form::open(array('files' => false, 'method' => 'put',
                                'route' => array('admin.course-evaluation.update', $post->id) , 'class' => 'form-horizontal')) !!}
                            @else
                                {!! \Form::open(array('files' => false, 'method' => 'post',
                                'route' => 'admin.course-evaluation.store', 'class' => 'form-horizontal')) !!}
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="question">{{ __('Question') }}&nbsp;<span class="required">*</span></label>
                                        <input type="text" placeholder="{{ __('Question') }}" name="question" id="question" 
                                            class="form-control {{ $errors->has('question') ? ' is-invalid' : '' }}"
                                             value="{{ old('question', isset($post->question) ? $post->question: '') }}">
                                        {!! $errors->first('question', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="question_mm">{{ __('Question') }}&nbsp;({{ __('MM') }})</label>
                                        <input type="text" placeholder="{{ __('Question') }}" name="question_mm" id="question" 
                                            class="form-control {{ $errors->has('question_mm') ? ' is-invalid' : '' }}"
                                             value="{{ old('question_mm', isset($post->question_mm) ? $post->question_mm: '') }}">
                                        {!! $errors->first('question_mm', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    <div class="form-group">
                                        <label for="order" >{{ __('Order') }}&nbsp;<span class="required">*</span></label>
                                        <input type="text" placeholder="{{ __('Order') }}" name="order" id="order" 
                                        class="form-control {{ $errors->has('order') ? 'is-invalid' : '' }}" 
                                        value="{{ old('order', isset($post->order) ? $post->order: '') }}" v-validate="'required'">
                                        {!! $errors->first('order', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('question')" class="invalid-feedback">@{{ errors.first('question') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="type">{{ __('Type') }}&nbsp;<span class="required">*</span></label>
                                        {!! Form::select('type', $types, old('type'), ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
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
                                        <a href="{{ route('admin.course-evaluation.create') }}" class="btn btn-secondary btn-sm">
                                            {{ __('Reset') }}
                                        </a>
                                        <a href="{{ route('admin.course-evaluation.index') }}" class="btn btn-outline-dark btn-sm">
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