@extends('backend.layouts.default')

@if (isset($post->id)) 
    @section('title', __('Edit Certificate'))
@else 
    @section('title', __('New Certificate'))
@endif

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="/{{ config('app.locale') }}/profile/course">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit Certificate Setting') }} 
                            @else 
                                {{ __('New Certificate Setting') }}                         
                            @endif
                            </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row gap-y">
                @include('layouts.form_alert')                                       
                                                            
                <div class="col-12 mx-auto">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                            @if (isset($post->id)) 
                                {{ __('Edit Certificate Setting') }} 
                            @else 
                                {{ __('New Certificate Setting') }}                         
                            @endif                             
                            </h5>
                        </div>
                        <div class="card-body">
                                <!-- '@submit' => 'validateBeforeSubmit'  -->
                                @if (isset($post))
                                    {!! \Form::open(array('files' => false, 'method' => 'put', 
                                        'route' => array('member.course.certificate.update', $post->id),
                                        'class' => 'form-horizontal' )) !!}
                                @else
                                    {!! \Form::open(array('files' => false, 'method' => 'post', 
                                        'route' => array('member.course.certificate.store', $course->id),
                                        'class' => 'form-horizontal' )) !!}                   
                                 @endif
                                    {!! Form::hidden('redirect_to', url()->previous()) !!} 
                                        <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                                    @if (isset($post->id))
                                        <input type="hidden" name="id" id="id" value="{{ $post->id }}">
                                    @endif
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                            <div class="row">
                                <div class="col-12">                                                                                                 
                                    <div class="form-group">
                                        <label for="cert-title" class="">@lang('Title')</label>
                                        <input type="text" placeholder="@lang('Title')" 
                                                name="title" id="cert-title"
                                                class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                                    </div>                                         
                                    <div class="form-group">
                                        <label for="certify-text">@lang('Certify Text')</label>
                                        <textarea  name="certify_text" placeholder="@lang('Certify Text')"   id="certify-text" 
                                                class="form-control{{ $errors->has('certify_text') ? ' is-invalid' : '' }}" 
                                            required>{{old('certify_text', isset($post->certify_text) ? $post->certify_text: '')}}
                                        </textarea>
                                        {!! $errors->first('certify_text', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('certify_text')" class="invalid-feedback">@{{ errors.first('certify_text') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="completion-text">@lang('Completion Text')</label>
                                        <textarea  name="completion_text" placeholder="@lang('Completion Text')"   id="completion-text" 
                                                class="form-control{{ $errors->has('completion_text') ? ' is-invalid' : '' }}" 
                                            required>{{old('completion_text', isset($post->completion_text) ? $post->completion_text: '')}}
                                        </textarea>
                                        {!! $errors->first('completion_text', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('completion_text')" class="invalid-feedback">@{{ errors.first('completion_text') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cert-description" class="">@lang('Description/Remark')</label>
                                        <input type="text" placeholder="@lang('Description/Remark')" 
                                                name="description" id="cert-description"
                                                class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                value="{{ old('description', isset($post->description) ? $post->description: '') }}">
                                            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">
                                        {{ __('Save') }}
                                    </button>
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="1">
                                        {{ __('Save & Close') }}
                                    </button>
                                    <a href="{{ route('member.course.index') }}" class="btn btn-md btn-outline-dark">{{ __('Cancel') }}</a>
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
