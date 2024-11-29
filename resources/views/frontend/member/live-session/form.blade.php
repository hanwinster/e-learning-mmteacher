@extends('backend.layouts.default')

@if (isset($post->id)) 
    @section('title', __('Edit Session'))
@else 
    @section('title', __('New Session'))
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
                        <li class="breadcrumb-item"><a href="{{ route('member.course.show', $course->id) }}">{{ strip_tags($course->title) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.course.show', $course->id).'#nav-zoom' }}">{{ __('Live Sessions') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit Live Session') }} 
                            @else 
                                {{ __('New Live Session') }}                         
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
                <div class="col-12" id="assignment_root">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ __('Live Session') }}
                                @if (isset($post->id)) 
                                    [@lang('Edit')]
                                @else 
                                    [@lang('New')] 
                                @endif
                            </h5>
                        </div>
                        <div class="card-body"> <!-- '@submit' => 'validateBeforeSubmit' -->
                            @if (isset($post))
                            {!! \Form::open(array( 'method' => 'put', 'route' => array('member.course.live-session.update',
                                $post->id), 'class' => 'form-horizontal')) !!}
                            @else
                            {!! \Form::open(array( 'route' => ['member.course.live-session.store', $course->id],
                                'class' => 'form-horizontal')) !!}
                            @endif
                            {!! Form::hidden('redirect_to', url()->previous()) !!}
                            @if (isset($post->id)) 
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <span class="font-weight-bold">@lang('Zoom Meeting ID')</span>    -   
                                        <code class="font-italic">{{$post->meeting_id}}</code>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold">@lang('Start URL')</span>    -   
                                        <code class="font-italic">{{$post->start_url}}</code>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold">@lang('Join URL')</span>     -   
                                        <code class="font-italic">{{$post->join_url}}</code>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold">@lang('Meeting Status')</span>     -   
                                        <code class="font-italic">{{$post->status}}</code>
                                    </div>
                                </div>
                            </div>                          
                            @endif
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <!-- <label>{{__('Course Title')}} : </label><span> {{$course->title}} </span> -->
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                        @if (isset($post->id)) 
                                            <input type="hidden" name="meeting_id" value="{{$post->meeting_id}}">
                                        @endif
                                    </div>    
                                    <div class="form-group">
                                        <label for="topic">{{ __('Session/Meeting Title') }}&nbsp;<span class="required">*</span></label>
                                        <input v-validate="'required'" name="topic" placeholder="{{ __('Session/Meeting Title') }}" 
                                            value="{{old('topic', isset($post->topic) ? $post->topic: '')}}"
                                            type="text" id="topic" class="form-control {{ $errors->has('topic') ? ' is-invalid' : '' }}">
                                        {!! $errors->first('topic', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('topic')" class="invalid-feedback">@{{ errors.first('topic') }}</div>
                                    </div>
                                    <div class="form-group">
                                        @php
                                            $existingLectureId = get_lecture_from_query_string_or_resource(
                                                                isset($post->lecture_id)? $post->lecture_id: '', request()->lecture_id);
                                                                                      
                                        @endphp
                                        <label for="lecture_id" class="col-xs-12">
                                            {{__('Meeting For the Course or Lecture') }}&nbsp;<span class="required">*</span>
                                        </label>
                                        {!! Form::select('lecture_id', stripTagsFromArray($lectures), old('lecture_id', $existingLectureId), 
                                            ['class' => $errors->has('lecture_id')?
                                            'form-control is-invalid' : 'form-control', 'v-validate' => "'required'" ]) !!}
                                        {!! $errors->first('lecture_id', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('lecture_id')" class="invalid-feedback">@{{ errors.first('lecture_id') }}</div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="duration">{{ __('Session/Meeting Duration') }}&nbsp;
                                                    <span class="required">*</span>
                                                    
                                                </label>
                                                <i class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                        title="@lang('Please provide duration in minutes')">
                                                    <input v-validate="'required'" name="duration" placeholder="{{ __('Session/Meeting Duration') }}" 
                                                        value="{{old('duration', isset($post->duration) ? $post->duration: '')}}"
                                                        type="number" id="duration" class="form-control {{ $errors->has('duration') ? ' is-invalid' : '' }}">  
                                                </i>                            
                                                {!! $errors->first('duration', '<div class="invalid-feedback">:message</div>') !!}
                                                <div v-show="errors.has('duration')" class="invalid-feedback">@{{ errors.first('duration') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="passcode">{{ __('Session/Meeting Passcode') }}&nbsp;
                                                    <span class="required">*</span>&nbsp;
                                                    
                                                </label>
                                                <i class="tooltip-info" data-toggle="tooltip" data-placement="top" 
                                                        title="@lang('Please provide the passcode with 6 digits')">
                                                <input v-validate="'required'" name="passcode" placeholder="{{ __('Session/Meeting Passcode') }}" 
                                                    value="{{old('passcode', isset($post->passcode) ? $post->passcode: '')}}"
                                                    type="text" id="passcode" class="form-control {{ $errors->has('passcode') ? ' is-invalid' : '' }}"></i>
                                                {!! $errors->first('passcode', '<div class="invalid-feedback">:message</div>') !!}
                                                <div v-show="errors.has('passcode')" class="invalid-feedback">@{{ errors.first('passcode') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="row pt-3">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="zoom-start-date">
                                                    @lang('Session/Meeting Date')&nbsp;<span class="required">*</span>
                                                </label>                                               
                                                <div class="input-group date" id="zoom-start-date" data-target-input="nearest">                                            
                                                    <input type="text" class="form-control datetimepicker-input" id="zoom-start-date-input"
                                                        placeholder="@lang('Session/Meeting Date')" name="start_date" 
                                                        data-target="#zoom-start-date" v-validate="'required'"
                                                        value="{{old('start_date', isset($post->start_date) ? $post->start_date: '')}}"/>
                                                    <div class="input-group-append" data-target="#zoom-start-date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                    {!! $errors->first('start_date', '<div class="invalid-feedback">:message</div>') !!}
                                                    <div v-show="errors.has('start_date')" class="invalid-feedback">@{{ errors.first('start_date') }}</div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="zoom-start-time">
                                                    @lang('Start Time')&nbsp;<span class="required">*</span>
                                                </label>
                                                <div class="input-group date" id="zoom-start-time" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" placeholder="@lang('Start Time')" 
                                                        name="start_time" data-target="#zoom-start-time" v-validate="'required'"
                                                        value="{{old('start_time', isset($post->start_time) ? $post->start_time: '')}}"/>
                                                    <div class="input-group-append" data-target="#zoom-start-time" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                                    </div>
                                                    {!! $errors->first('start_time', '<div class="invalid-feedback">:message</div>') !!}
                                                    <div v-show="errors.has('start_time')" class="invalid-feedback">@{{ errors.first('start_time') }}</div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="agenda" class="require">@lang('Agenda')</label>
                                                <textarea name="agenda" placeholder="@lang('Agenda')" rows="5"
                                                    class="form-control{{ $errors->has('agenda') ? ' is-invalid' : '' }}"
                                                    id="agenda">{{old('agenda', isset($post->agenda) ? $post->agenda: '')}}</textarea>
                                                {!! $errors->first('agenda', '<div class="invalid-feedback">:message</div>') !!}
                                                <div v-show="errors.has('agenda')" class="invalid-feedback">@{{ errors.first('agenda') }}</div>
                                            </div>
                                        </div>  
                                    </div>                                                                 
                                </div>
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary btn-md" type="submit" name="btnSave" value="{{ __('Save') }}">
                                @if(!isset($post))
                                    <input class="btn btn-primary btn-md" type="submit" name="btnSaveNew" value="{{ __('Save & New') }}">
                                @endif
                                <input class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="{{ __('Save & Close') }}">
                                <a href="{{ route('member.course.show', $course->id).'#nav-zoom' }}" class="btn btn-outline-dark btn-md">{{ __('Cancel') }}</a>
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
