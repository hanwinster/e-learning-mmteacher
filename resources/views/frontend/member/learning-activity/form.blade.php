@extends('backend.layouts.default')
@section('title', __('Learning Activity'))

@if (isset($post->id)) 
    @section('title', __('Edit LearningActivity'))
@else 
    @section('title', __('New LearningActivity'))
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
                        <li class="breadcrumb-item"><a href="{{route('member.course.show', $course->id)}}#nav-learning-activity">{{ __('LearningActivity') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit Learning Activity') }} 
                            @else 
                                {{ __('New Learning Activity') }}                         
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
                <div class="col-12" id="LearningActivity_root">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ __('Learning Activity') }}
                                @if (isset($post->id)) [@lang('Edit')] @else [@lang('New')] @endif
                            </h5>
                        </div>
                        <div class="card-body"> <!-- '@submit' => 'validateBeforeSubmit' -->
                            @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.learning-activity.update',
                                $post->id), 'class' => 'form-horizontal')) !!}
                            @else
                            {!! \Form::open(array('files' => true, 'route' => ['member.learning-activity.store', $course->id],
                                'class' => 'form-horizontal')) !!}
                            @endif
                            {!! Form::hidden('redirect_to', url()->previous()) !!}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__('Course Title')}} : </label><span> {{ strip_tags($course->title) }} </span>
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                    </div>    
                                    <div class="form-group">
                                        <label for="title">
                                            @lang('Learning Activity Title')&nbsp;<span class="required">*</span>
                                        </label>
                                        <textarea  v-validate="'required'" name="title" class="form-control summernote {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                            placeholder="Title.." id="title" >{{old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">
                                            @lang('Learning Activity Description')
                                        </label>
                                        <textarea v-validate="'required'" name="description" placeholder="Description..." id="description" 
                                            class="form-control summernote">{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                                    </div>
                                    <div id="lecture-attach-file" class="form-group">
                                        <label for="attached_file">
                                            {{ __('Attached File') }}
                                        </label>
                                        @if(isset($post->id))
                                            {{ Form::file('attached_file',
                                                ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 
                                                'v-validate' => "'ext:pdf,ppt,pptx,mp3,mp4,docx'"]) }}
                                            <small>.mp3, .mp4, .ppt, .pptx, .docx & .pdf</small>
                                            <div style="padding: 10px 0px;">
                                                @if(count($post->getMedia('learning_activity_attached_file')))
                                                    @foreach($post->getMedia('learning_activity_attached_file') as $resource)
                                                        <a href="{{asset($resource->getUrl())}}"  target="_blank">
                                                            <i class="fas fa-file"></i>&nbsp;
                                                            {{ $resource->file_name }}
                                                        </a>
                                                        &nbsp;&nbsp;
                                                        <a onclick="return confirm('Are you sure you want to delete?')"
                                                            href="{{ route('member.media.destroy', $resource->id) }}" class="text-danger">
                                                            <i class="fas fa-trash"></i> @lang('Remove')
                                                        </a>
                                                        <br/>
                                                        <span class="text-info">{{asset($resource->getUrl())}}</span>
                                                        <br/>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @else
                                            {{ Form::file('attached_file', ['class' => $errors->has('attached_file') ? 'form-control is-invalid' :
                                                'form-control', 'v-validate' => "'ext:pdf,ppt,pptx,mp3,mp4,docx'"]) }}
                                            <small>.mp3, .mp4, .ppt, .pptx, .docx & .pdf</small>
                                        @endif
                                        <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                                        {!! $errors->first('attached_file', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    <div class="form-group">
                                        @php
                                            $existing_lecture_id = get_lecture_from_query_string_or_resource(isset($post->lecture_id)? $post->lecture_id: '', request()->lecture_id);
                                            $isDisabled = isset($post->id) ? true : false;
                                        @endphp
                                        <label for="lecture_id" class="col-xs-12">{{__('Course or Lecture Title') }}</label>
                                            {{-- @if(\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course)) --}}
                                                <!-- <small class="text-muted">&nbsp;<kbd>@lang('Cannot edit as the course already had course takers')</kbd></small>
                                                    {!! Form::select('lecture_id', stripTagsFromArray($lectures), 
                                                    old('lecture_id', $existing_lecture_id), 
                                                    ['class' => 'form-control disabled', 'disabled' => true ]) !!}
                                                <input type="hidden" name="lecture_id" value="{{$existing_lecture_id}}" /> -->
                                            {{--@else --}}
                                                {!! Form::select('lecture_id', stripTagsFromArray($lectures), 
                                                    old('lecture_id', $existing_lecture_id), 
                                                    ['class' => $errors->has('lecture_id')?
                                                    'form-control is-invalid' : 'form-control', 'v-validate' => "'min:1'" ]) !!}
                                                {!! $errors->first('lecture_id', '<div class="invalid-feedback">:message</div>') !!}
                                                <div v-show="errors.has('lecture_id')" class="invalid-feedback">@{{ errors.first('lecture_id') }}</div>
                                            {{--@endif --}}
                                    </div>
                                                          
                                </div>                    
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="btnSave" value="{{ __('Save') }}">
                                @if(!isset($post))
                                    <input class="btn btn-primary" type="submit" name="btnSaveNew" value="{{ __('Save & New') }}">
                                @endif
                                <input class="btn btn-primary" type="submit" name="btnSaveClose" value="{{ __('Save & Close') }}">
                                <a href="{{ route('member.course.show', $course->id).'#nav-learning-activity' }}" class="btn btn-outline-dark">{{ __('Cancel') }}</a>
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