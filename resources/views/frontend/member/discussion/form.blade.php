@extends('backend.layouts.default')

@if (isset($post->id)) 
    @section('title', __('Edit Discussion'))
@else 
    @section('title', __('New Discussion'))
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
                        <li class="breadcrumb-item"><a href="{{ route('member.course.index') }}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item"><a href="/{{ config('app.locale') }}/profile/course/{{$course->id}}">{{ strip_tags($course->title)}}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('member.course.show', $course->id)}}#nav-discussion">{{ __('Discussion') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit Discussion') }} 
                            @else 
                                {{ __('New Discussion') }}                         
                            @endif
                        </li>
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
                            <h4 class="card-title">
                                @if (isset($post->id)) 
                                    {{ __('Edit Discussion') }} 
                                @else 
                                    {{ __('New Discussion') }}                         
                                @endif                           
                            </h4>
                        </div>
                        <div class="card-body">
                                <!-- '@submit' => 'validateBeforeSubmit'  -->
                                @if (isset($post))
                                    {!! \Form::open(array('files' => false, 'method' => 'put', 
                                        'route' => array('member.course.discussion.update', $post->id),
                                        'class' => 'form-horizontal' )) !!} 
                                @else
                                    {!! \Form::open(array('files' => false,  'method' =>'post',
                                        'route' => array('member.course.discussion.store', $course->id),
                                        'class' => 'form-horizontal' )) !!}
                                @endif                         
                                    {!! Form::hidden('redirect_to', url()->previous()) !!} 
                                        <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                                    @if (isset($post->id))
                                        <input type="hidden" name="id" id="id" value="{{ $post->id }}">
                                    @endif
                                    <input type="hidden" name="course_id" id="course-id" value="{{ $course->id }}">
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="title">
                                                    @lang('Discussion Title')&nbsp;<span class="required">*</span>
                                                </label>
                                                <textarea v-validate="'required'" name="title" placeholder="@lang('Title')" type="text" id="discussion-title" 
                                                    class="form-control summernote" aria-required="true" aria-invalid="false"
                                                    >{{ old('title', isset($post->title) ? $post->title: '') }}</textarea>
                                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                                <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group"><label for="title">@lang('Permission')&nbsp;<span class="required">*</span></label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="per-all-takers" name="allow_takers" 
                                                        value="1" {{ isset($post->allow_takers) && $post->allow_takers ? 'checked': '' }} >
                                                    <label class="form-check-label" for="per-all-takers">@lang('All Course Takers')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group"><label for="title">&nbsp;&nbsp;</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="per-all-learners" name="allow_learners" 
                                                        value="1" {{ isset($post->allow_learners) && $post->allow_learners ? 'checked': '' }} >
                                                    <label class="form-check-label" for="per-all-learners">@lang('All Learners')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group"><label for="title">@lang('Description')</label>
                                                <textarea name="description" placeholder="@lang('Description')" type="text" id="discussion-description" 
                                                    class="form-control summernote" aria-required="true" aria-invalid="false"
                                                    >{{ old('description', isset($post->description) ? $post->description: '') }}</textarea>
                                                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                                <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input class="btn btn-primary" type="submit" name="btnSave" value="Save">                                            
                                                <input class="btn btn-primary" type="submit" name="btnSaveClose" value="Save & Close">
                                                <a href="{{ route('member.course.show', $course->id).'#nav-discussion' }}" class="btn btn-outline-dark">{{ __('Cancel') }}</a>
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
