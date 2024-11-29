@extends('backend.layouts.default')

@if (isset($post->id)) 
    @section('title', __('Edit Lecture'))
@else 
    @section('title', __('New Lecture'))
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
                                {{ __('Edit Lecture') }} 
                            @else 
                                {{ __('New Lecture') }}                         
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
                                {{ __('Lecture') }}&nbsp;[
                                @if(isset($post->id))
                                     @lang('Edit') ]
                                @else  @lang('New') ]
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                        @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.lecture.update',
                            $post->id), 'class' => 'form-horizontal' )) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route' => ['member.lecture.store', $course->id],
                            'class' => 'form-horizontal' )) !!} <!-- '@submit' => 'validateBeforeSubmit' -->
                        @endif
                        {!! Form::hidden('redirect_to', url()->previous()) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="course_id" value="{{$course->id}}">
                            </div>
                            <div class="form-group">
                                <label for="lecture_title">
                                    @lang('Lecture Title')&nbsp;<span class="required">*</span>
                                </label>
                                <textarea  v-validate="'required'" name="lecture_title" placeholder="Title.."   id="lecture_title" 
                                    class="form-control summernote {{ $errors->has('lecture_title') ? ' is-invalid' : '' }}"
                                    >{{old('lecture_title', isset($post->lecture_title) ? $post->lecture_title: '')}}</textarea>
                                {!! $errors->first('lecture_title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('lecture_title')" class="invalid-feedback">@{{ errors.first('lecture_title') }}</div>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label class="col-xs-12">
                                        {{ __('Resource Type') }}&nbsp;<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline"> 
                                    {{ Form::radio('resource_type', 'none', ( (isset($post->resource_type) 
                                            && $post->resource_type == 'none') || !isset($post->id) ? true : false ),
                                            ['id' => 'lect-resource-type-none', 'class' => 'form-check-input resource-type', 
                                            'v-validate'=>"'required'"]) }}
                                    <label for="item_affect_certification_assign" class="form-check-label">
                                        {{ __(\App\Models\Lecture::RESOURCE_TYPE['none']) }}
                                    </label>
                                </div>
                                <div class="form-check form-check-inline"> 
                                    {{ Form::radio('resource_type', 'attach_file', ( (isset($post->resource_type) 
                                            && $post->resource_type == 'attach_file' ? true : false) ),
                                            ['id' => 'lect-resource-type-attach', 'class' => 'form-check-input resource-type', 
                                            'v-validate'=>"'required'"]) }}
                                    <label for="item_affect_certification_assign" class="form-check-label">
                                        {{ __(\App\Models\Lecture::RESOURCE_TYPE['attach_file']) }}
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    {{ Form::radio('resource_type', 'embed_video', (isset($post->resource_type) &&
                                            $post->resource_type == 'embed_video' ? true : false ), 
                                        ['id' => 'lect-resource-type-embed', 'class' => 'form-check-input resource-type',
                                        'v-validate'=>"'required'"]) }}
                                    <label for="resource_type" class="form-check-label">
                                        {{ __(\App\Models\Lecture::RESOURCE_TYPE['embed_video']) }}
                                    </label>
                                    {!! $errors->first('resource_type', '<div class="invalid-feedback">:message</div>') !!}
                                    <div v-show="errors.has('resource_type')" class="invalid-feedback">@{{ errors.first('resource_type') }}</div>
                                </div> 
                                                         
                            </div>
                            <div id="lecture-embed-video" class="form-group {{ isset($post->id) && $post->resource_type == 'embed_video'? '' : 'd-none' }}">
                                <label for="resource_link">
                                    {{ __('Video Link') }}&nbsp;<span class="required">*</span>
                                </label>
                                <input v-validate="'url'" type="text" placeholder="link" name="video_link" id="video_link"
                                        value="{{ old('video_link', isset($post->video_link) ? $post->video_link: '') }}"
                                        class="form-control {{ $errors->has('video_link') ? ' is-invalid' : '' }}">
                                {!! $errors->first('video_link', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('video_link')" class="invalid-feedback">@{{ errors.first('video_link') }}</div>
                            </div>
                            <div id="lecture-attach-file" class="form-group {{ isset($post->id) && 
                                        $post->resource_type == 'attach_file' ? '' : 'd-none' }}">
                                
                                @if(isset($post->id) && $post->resource_type == 'attach_file')
                                    <label for="attached_file">
                                        {{ __('Attached File') }}
                                    </label>
                                    {{ Form::file('attached_file',
                                        ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 
                                        'v-validate' => "'ext:pdf,ppt,pptx,mp3,mp4,odp'"]) }}
                                    
                                    <small>.mp3, .mp4, .ppt, .pptx and .pdf</small>
                                    <div style="padding: 10px 0px;">
                                        {{--<img src="{{asset('assets/course/attached_file'.'/'.$post->id.'/'.$post->attached_file)}}" 
                                            width="150" height="90" style="border: 3px solid #ddd;"> --}}
                                        @if(count($post->getMedia('lecture_attached_file')))
                                            @foreach($post->getMedia('lecture_attached_file') as $resource)
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
                                     <label for="attached_file">
                                        {{ __('Attached File') }}&nbsp;<span class="required">*</span> 
                                    </label>
                                    {{ Form::file('attached_file', ['class' => $errors->has('attached_file') ? 
                                          'form-control is-invalid' :
                                          'form-control', 'v-validate' => "'ext:pdf,ppt,pptx,mp3,mp4'"]) }}                                  
                                    <small>.mp3, .mp4, .ppt, .pptx and .pdf</small>
                                @endif
                                <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                                {!! $errors->first('attached_file', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                            <div class="form-group">
                                <label for="resource_link" class="">{{ __('Resource Link') }}</label>
                                <input v-validate="'url'" type="text" placeholder="link" name="resource_link" id="resource_link"
                                        value="{{ old('resource_link', isset($post->resource_link) ? $post->resource_link: '') }}"
                                        class="form-control{{ $errors->has('strand') ? ' is-invalid' : '' }}">
                                {!! $errors->first('resource_link', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('resource_link')" class="invalid-feedback">@{{ errors.first('resource_link') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="">@lang('Description')</label>
                                <textarea  v-validate="''" name="description" placeholder="Description..."   id="description" 
                                    class="form-control summernote {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                    >{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-primary btn-md" type="submit" name="btnSave" value="@lang('Save')">
                        <input class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="@lang('Save & Close')">
                        @if(!isset($post))
                            <input class="btn btn-primary btn-md" type="submit" name="btnSaveNew" value="@lang('Save & New')">
                        @endif
                        <input class="btn btn-primary btn-md" type="submit" name="btnSaveNext" value="@lang('Save & Next')">
                        <a href="{{ route('member.course.show', $course->id).'#nav-lecture' }}" 
                            class="btn btn-md btn-outline-dark">{{ __('Cancel') }}
                        </a>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        
    </section>
</div>
@endsection