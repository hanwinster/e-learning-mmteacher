@extends('backend.layouts.default')
@if (isset($post->id)) 
    @section('title', __('Edit Quiz'))
@else 
    @section('title', __('New Quiz')) 
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
                        <li class="breadcrumb-item"><a href="/{{ config('app.locale') }}/profile/course/{{$course->id}}">{{ strip_tags($course->title) }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit Quiz') }} 
                            @else 
                                {{ __('New Quiz') }}                         
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
                <div class="col-12 mx-auto" id="quiz_root">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ __('Quiz') }}
                                @if (isset($post->id)) [@lang('Edit')] @else [@lang('New')] @endif
                            </h5>                     
                        </div>
                        <div class="card-body">
                            @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.quiz.update',
                                $post->id), 'class' => 'form-horizontal' )) !!} <!-- '@submit' => 'validateBeforeSubmit'-->
                            @else
                            {!! \Form::open(array('files' => true, 'route' => ['member.quiz.store', $course->id],
                                'class' => 'form-horizontal')) !!}
                            @endif
                            {!! Form::hidden('redirect_to', url()->previous()) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h5>@lang('Course') : {{ strip_tags($course->title) }}</h5>
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                    </div>    
                                    <div class="form-group">
                                        <label for="title">@lang('Quiz Title')&nbsp;<span class="required">*</span></label>
                                        <textarea  v-validate="'required|max:255'" name="title" placeholder="Title.."   
                                            id="title" class="form-control summernote {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                            >{{old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                                    </div>
                                    <div class="form-group">
                                        @php
                                            $existing_lecture_id = get_lecture_from_query_string_or_resource(isset($post->lecture_id)? $post->lecture_id:
                                            '', request()->lecture_id);
                                        @endphp
                                        <label for="lecture_id" class="col-xs-12">{{__('Course or Lecture Title') }}</label>
                                        {!! Form::select('lecture_id', stripTagsFromArray($lectures), old('lecture_id', $existing_lecture_id), ['class' => $errors->has('lecture_id')?
                                            'form-control is-invalid' : 'form-control', 'v-validate' => "'min:1'" ]) !!}
                                        {!! $errors->first('lecture_id', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('lecture_id')" class="invalid-feedback">@{{ errors.first('lecture_id') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="col-xs-12 require">{{__('Quiz Type') }}&nbsp;<span class="required">*</span></label>
                                        @if(isset($post->type) && $alreadyHasQuestion)
                                        {!! Form::select('type', $quiz_types, old('type', isset($post->type)? $post->type:
                                            ''), ['class' => $errors->has('type')?
                                            'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'", 'id'=>'quiz_type', 'disabled' => true ]) !!}
                                            <input type="hidden" name="type" id="type" value="{{$post->type}}">
                                        @else
                                            {!! Form::select('type', $quiz_types, old('type', isset($post->type)? $post->type:
                                            ''), ['class' => $errors->has('type')?
                                            'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'", 'id'=>'quiz_type' ]) !!}
                                        @endif
                                        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('type')" class="invalid-feedback">@{{ errors.first('type') }}</div>
                                    </div> 
                                </div>                    
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary btn-md" type="submit" name="btnSave" value="{{ __('Save')}}">
                                <input class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="{{ __('Save & Close')}}">
                                @if(!isset($post))
                                    <input class="btn btn-primary btn-md" type="submit" name="btnSaveNew" value="{{ __('Save & New')}}">
                                @endif
                                <input class="btn btn-primary btn-md" type="submit" name="btnSaveAddQuestion" value="{{ __('Save & Add Question')}}">
                                <a href="{{ route('member.course.show', $course->id).'#nav-quiz' }}" class="btn btn-md btn-outline btn-outline-dark">{{ __('Cancel') }}</a>
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

