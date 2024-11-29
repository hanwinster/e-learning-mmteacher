@extends('backend.layouts.default')

@section('title', __('FAQ'))

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.faq.index') }}">{{ __('FAQs') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('FAQ') }}</li>
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
                    <div class="col-md-12">
                        <!-- Default Elements -->
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h4 class="card-title">
                                    @if (isset($post->id)) [@lang('Edit')] #<strong title="ID">{{ $post->id }}</strong> @else [@lang('New')] @endif
                                </h4>
                            </div>
                            <div class="card-body">
                                @if (isset($post))
                                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.faq.update', $post->id)
                                    , 'class' => 'form-horizontal')) !!}
                                @else
                                    {!! \Form::open(array('files' => true, 'route' => 'admin.faq.store',
                                    'class' => 'form-horizontal')) !!}
                                @endif

                                <div class="form-group">
                                    <label for="category_id" class="col-6">
                                        {{ __('Category') }}&nbsp;<span class="required">*</span>
                                    </label>
                                    {!! Form::select('category_id', $categories, old('category_id', isset($post->category_id)
                                    ? $post->category_id: ''), ['class' => $errors->has('category_id') ? 'form-control is-invalid' : 'form-control' ]) !!}
                                    {!! $errors->first('category_id', '<div class="invalid-feedback">:message</div>') !!}
                                </div>

                                <div class="form-group">
                                    <label for="question">{{ __('Question') }}&nbsp;<span class="required">*</span></label>
                                    <textarea class="form-control summernote {{ $errors->has('question') ? ' is-invalid' : '' }}" rows="5" name="question"
                                            id="question">{{ old('question', isset($post->question) ? $post->question: '') }}</textarea>
                                    {!! $errors->first('question', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                                <div class="form-group">
                                    <label for="question_mm">{{ __('Question') }}&nbsp;({{ __('MM') }})</label>
                                    <textarea class="form-control summernote {{ $errors->has('question_mm') ? ' is-invalid' : '' }}" rows="5" name="question_mm"
                                            id="question_mm">{{ old('question_mm', isset($post->question_mm) ? $post->question_mm: '') }}</textarea>
                                    {!! $errors->first('question_mm', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                                <div class="form-group">
                                    <label for="answer">{{ __('Answer') }}&nbsp;<span class="required">*</span></label>
                                    <textarea class="form-control summernote {{ $errors->has('answer') ? ' is-invalid' : '' }}" rows="5" name="answer"
                                            id="answer">{{ old('answer', isset($post->answer) ? $post->answer: '') }}</textarea>
                                    {!! $errors->first('answer', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                                <div class="form-group">
                                    <label for="answer_mm">{{ __('Answer') }}&nbsp;&nbsp;({{ __('MM') }})</label>
                                    <textarea class="form-control summernote {{ $errors->has('answer_mm') ? ' is-invalid' : '' }}" rows="5" name="answer_mm"
                                            id="answer_mm">{{ old('answer_mm', isset($post->answer_mm) ? $post->answer_mm: '') }}</textarea>
                                    {!! $errors->first('answer_mm', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                                @can('publish_faq')
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
                                        {!! $errors->first('published', '<p class="help-block">:message</p>') !!}
                                    </div>
                                @endcan

                                <div class="form-group">
                                    @if (auth()->user()->can('add_faq') || auth()->user()->can('edit_faq'))
                                        <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">{{ __('Save') }}
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.faq.index') }}" class="btn btn-md btn-outline-dark">{{ __('Cancel') }}</a>
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
