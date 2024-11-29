@extends('backend.layouts.default')
@section('title', __('Assignment'))

@if (isset($post->id)) 
    @section('title', __('Edit Assignment'))
@else 
    @section('title', __('New Assignment'))
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
                        <li class="breadcrumb-item"><a href="{{route('member.course.show', $course->id)}}#nav-assignment">{{ __('Assignments') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit Assignment') }} 
                            @else 
                                {{ __('New Assignment') }}                         
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
                                {{ __('Assignment') }}
                                @if (isset($post->id)) [Edit] @else [New] @endif
                            </h5>
                        </div>
                        <div class="card-body"> <!-- '@submit' => 'validateBeforeSubmit' -->
                            @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.assignment.update',
                            $post->id), 'class' => 'form-horizontal')) !!}
                            @else
                            {!! \Form::open(array('files' => true, 'route' => ['member.assignment.store', $course->id],
                                'class' => 'form-horizontal')) !!}
                            @endif
                            {!! Form::hidden('redirect_to', url()->previous()) !!}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__('Course Title')}} : </label><span> {{$course->title}} </span>
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                    </div>    
                                    <div class="form-group">
                                        <label for="title" class="require">@lang('Assignment Title')</label>
                                        <textarea  v-validate="'required'" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                            placeholder="Title.." id="title" >{{old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">@lang('Assignment Instruction')</label>
                                        <textarea name="description" placeholder="Description..." id="description" 
                                            class="form-control">{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        @php
                                            $existing_lecture_id = get_lecture_from_query_string_or_resource(isset($post->lecture_id)? $post->lecture_id: '', request()->lecture_id);
                                            $isDisabled = isset($post->id) ? true : false;
                                        @endphp
                                        <label for="lecture_id" class="col-xs-12">{{__('Course or Lecture Title') }}</label>
                                        {!! Form::select('lecture_id', $lectures, old('lecture_id', $existing_lecture_id), ['class' => $errors->has('lecture_id')?
                                            'form-control is-invalid' : 'form-control', 'v-validate' => "'min:1'" , 'disabled' => $isDisabled ]) !!}
                                        {!! $errors->first('lecture_id', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('lecture_id')" class="invalid-feedback">@{{ errors.first('lecture_id') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="attached_file" class="@if(isset($post->id))  @else require @endif">{{ __('Attached File') }}</label>
                                        @if(isset($post->id))
                                        {{ Form::file('attached_file',
                                        ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 
                                            'v-validate' => "'ext:pdf,ppt,pptx,docx,xlsx,mp3,mp4'"]) }}
                                        <small>.mp3, .mp4, .ppt, .pptx, .docx, .xlsx and .pdf</small>
                                        <div style="padding: 10px 0px;">
                                            @foreach($post->getMedia('assignment_attached_file') as $resource)
                                                <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                            @endforeach
                                        </div>
                                        @else
                                        {{ Form::file('attached_file', ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 
                                            'form-control', 'v-validate' => "'required|ext:pdf,ppt,pptx,docx,xlsx,mp3,mp4'"]) }}
                                        <small>.mp3, .mp4, .ppt, .pptx, .docx, .xlsx and .pdf</small>
                                        @endif 
                                        <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                                        {!! $errors->first('attached_file', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>                        
                                </div>                    
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="btnSave" value="Save">
                                @if(!isset($post))
                                <input class="btn btn-primary" type="submit" name="btnSaveNew" value="Save & New">
                                @endif
                                <input class="btn btn-primary" type="submit" name="btnSaveClose" value="Save & Close">
                                <a href="{{ route('member.course.show', $course->id).'#nav-assignment' }}" class="btn btn-flat">{{ __('Cancel') }}</a>
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
