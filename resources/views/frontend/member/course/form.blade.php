@extends('backend.layouts.default')

@if (isset($post->id)) 
    @section('title', __('Edit Course'))
@else 
    @section('title', __('New Course'))
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
                        <li class="breadcrumb-item"><a href="{{route('member.course.index')}}">{{ __('Courses') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit Course') }} 
                            @else 
                                {{ __('New Course') }}                         
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
                
                @if ( \Session::has('warning') || \Session::has('error') || \Session::has('success'))
                    @include('layouts.form_alert')                                       
                @else
                    @if(auth()->user()->type == 'teacher_educator')
                        <div class="col-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{ __('Attention: Please be reminded to submit for approval by clicking on 
                                    REQUEST APPROVAL actions from Manage Course page 
                                        after creating a new course ') }} 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    @endif
                @endif
                                                                     
                <div class="col-12 mx-auto" id="elearning_root">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                @if (isset($post->id)) 
                                    {{ __('Edit Course') }} 
                                @else 
                                    {{ __('New Course') }}                         
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                                <!-- '@submit' => 'validateBeforeSubmit'  -->
                                @if (isset($post))
                                    {!! \Form::open(array('files' => true, 'method' => 'put', 
                                        'route' => array('member.course.update', $post->id),
                                        'class' => 'form-horizontal' )) !!}
                                @else
                                    {!! \Form::open(array('files' => true,  'method' =>'post',
                                        'route' => 'member.course.store', 
                                        'class' => 'form-horizontal' )) !!}
                                 @endif
                                    {!! Form::hidden('redirect_to', url()->previous()) !!} 
                                        <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                                    @if (isset($post->id))
                                        <input type="hidden" name="id" id="id" value="{{ $post->id }}">
                                    @else
                                        <input type="hidden" name="id" id="id" value="">
                                    @endif
                            <div class="row">
                                <div class="col-md-8">                            
                                    <div class="form-group">
                                        <label for="title">@lang('Title')&nbsp;<span class="required">*</span></label>
                                        <textarea  v-validate="'required'" name="title" placeholder="Title..."   id="title" 
                                                class="form-control summernote {{ $errors->has('title') ? ' is-invalid' : '' }}" 
                                            >{{old('title', isset($post->title) ? $post->title: '')}}
                                        </textarea>
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="course_category_id">{{__('Course Categories') }}&nbsp;<span class="required">*</span></label>
                                        {{-- Form::select('course_category_id', $categories,
                                            old('course_category_id', isset($post->course_category_id)? $post->course_category_id:''), 
                                            ['class' => $errors->has('course_category_id')?
                                        'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) --}}
                                        <select class="select2" multiple="multiple" data-placeholder="{{ __('Select Categories') }}" 
                                                style="width: 100%;" name="course_categories[]" selected="">
                                                @if(isset($post->course_categories))
                                                    @foreach($post->course_categories as $cat)
                                                        <option value="{{$cat}}" selected="selected">{{$categories[$cat]}}</option>
                                                        <?php unset($categories[$cat]); ?>
                                                    @endforeach 
                                                @endif
                                                @foreach($categories as $idx=>$category)
                                                    <option value="{{$idx}}">{{$category}}</option>
                                                @endforeach                                       
                                        </select>
                                        {!! $errors->first('course_categories', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('course_categories')" class="invalid-feedback">@{{ errors.first('course_categories') }}</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="course_level_id">{{__('Course Level') }}&nbsp;<span class="required">*</span></label>
                                        
                                            {!! Form::select('course_level_id', $levels, old('course_level_id', 
                                                isset($post->course_level_id)? $post->course_level_id:''), 
                                                ['class' => $errors->has('course_level_id')?
                                                'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) !!}
                                            {!! $errors->first('course_level_id', '<div class="invalid-feedback">:message</div>') !!}
                                            <div v-show="errors.has('course_level_id')" class="invalid-feedback">@{{ errors.first('course_level_id') }}</div>
                                      
                                    </div>

                                    <div class="form-group">
                                        <label for="course_type_id">{{__('Course Type') }}&nbsp;<span class="required">*</span></label>
                                        @if( isset($post) && \App\Repositories\CourseRepository::shouldCrudButtonsDisabled($post) )
                                            <small class="text-muted">&nbsp;<kbd>@lang('Cannot edit as the course already had course takers')</kbd></small>
                                                {!! Form::select('course_type_id', $types, old('course_type_id', 
                                                isset($post->course_type_id)? $post->course_type_id:''), 
                                                ['class' => 'form-control disabled', 'disabled' => true  ]) !!}                                          
                                            <input type="hidden" name="course_type_id" value="{{ isset($post->course_type_id)? $post->course_type_id:'' }}" />
                                        @else 
                                            @if( auth()->user()->isAdmin() || auth()->user()->isUnescoManager() )
                                                {!! Form::select('course_type_id', $types, old('course_type_id', 
                                                    isset($post->course_type_id)? $post->course_type_id:''), 
                                                    ['class' => $errors->has('course_type_id')?
                                                    'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) !!}
                                                {!! $errors->first('course_type_id', '<div class="invalid-feedback">:message</div>') !!}
                                                <div v-show="errors.has('course_type_id')" class="invalid-feedback">@{{ errors.first('course_type_id') }}</div>
                                            @else
                                                {!! Form::select('course_type_id', $types, 2, 
                                                    ['class' => 'form-control disabled', 'disabled' => true  ]) !!}                                          
                                                <input type="hidden" name="course_type_id" value="2" />    
                                            @endif
                                        @endif
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="objective">@lang('Objective')&nbsp;<span class="required">*</span></label>
                                        <textarea  v-validate="'required'" name="objective" placeholder="Objective..."   
                                            id="objective" class="form-control summernote {{ $errors->has('objective') ? ' is-invalid' : '' }}" 
                                        >{{old('objective', isset($post->objective) ? $post->objective: '')}}</textarea>
                                        {!! $errors->first('objective', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('objective')" class="invalid-feedback">@{{ errors.first('objective') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">@lang('Description')&nbsp;<span class="required">*</span></label>
                                        <textarea  v-validate="'required'" name="description" placeholder="Description..."   
                                            id="description" class="form-control summernote {{ $errors->has('description') ? ' is-invalid' : '' }}" 
                                        >{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                                        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="objective">@lang('Learning Outcome')</label>
                                        <textarea name="learning_outcome" placeholder="Learning Outcome..."   
                                            id="learning_outcome" class="form-control summernote {{ $errors->has('learning_outcome') ? ' is-invalid' : '' }}" 
                                        >{{old('learning_outcome', isset($post->learning_outcome) ? $post->learning_outcome: '')}}</textarea>
                                        {!! $errors->first('learning_outcome', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('learning_outcome')" class="invalid-feedback">@{{ errors.first('learning_outcome') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="downloadable_option">{{__('Downloadable Option') }}&nbsp;<span class="required">*</span></label>
                                        
                                            {!! Form::select('downloadable_option', $downloadable_options, old('downloadable_option', 
                                                    isset($post->downloadable_option)? $post->downloadable_option:
                                                ''), ['class' => $errors->has('downloadable_option')?
                                                'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) !!}
                                            {!! $errors->first('downloadable_option', '<div class="invalid-feedback">:message</div>') !!}
                                            <div v-show="errors.has('downloadable_option')" class="invalid-feedback">
                                                @{{ errors.first('downloadable_option') }}
                                            </div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">                                           
                                            <input id="is-display-video" name="is_display_video" type="checkbox" 
                                                value="{{  (isset($post)) ? $post->is_display_video : 0 }}"                                               
                                                    {{ isset($post) && $post->is_display_video > 0 ? 'checked' : ''}}                                             
                                                class="form-check-input {{$errors->has('is_display_video') ? 'is-invalid' : '' }}">  
                                            <label for="is-display-video" class="form-check-label">
                                                @lang('Will display a featured video in course detail?')
                                            </label>                                      
                                            {!! $errors->first('is_display_video', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                    </div>
                                     
                                    <div class="form-group video-link-div">
                                        <label for="video-link">
                                            @lang('YouTube Video Link')&nbsp;<span class="required">*</span>
                                            &nbsp;
                                            <span class="tooltip-info text-info" data-toggle="tooltip" data-placement="right" 
                                                title="@lang('Please provide the video link from YouTube only!')">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        </label>
                                        <input v-validate="'required'" type="text" placeholder="@lang('YouTube Video Link..')" 
                                                name="video_link" id="video-link" v-validate="'required|max:255'" 
                                                class="form-control{{ $errors->has('video_link') ? ' is-invalid' : '' }}"
                                                value="{{ old('video_link', isset($post->video_link) ? $post->video_link: '') }}">
                                            {!! $errors->first('video_link', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('video_link')" class="invalid-feedback">@{{ errors.first('video_link') }}</div>
                                    </div> 
                                         
                                    <div class="form-group">
                                        <label for="url_link" class="">@lang('Url Link')</label>
                                        <input v-validate="'url'" type="text" placeholder="Url Link.." name="url_link" id="url_link"
                                            class="form-control{{ $errors->has('url_link') ? ' is-invalid' : '' }}" v-validate="'required|max:255'" 
                                            value="{{ old('url_link', isset($post->url_link) ? $post->url_link: '') }}">
                                        {!! $errors->first('url_link', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('url_link')" class="invalid-feedback">@{{ errors.first('url_link') }}</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cover_image">{{ __('Cover Image') }}&nbsp;<span class="required">*</span>
                                        </label>
                                        @if(isset($post->id))
                                            {{ Form::file('cover_image',
                                            ['class' => $errors->has('cover_image') ? 'form-control is-invalid' : 'form-control', 
                                            'v-validate' => "'image|size:5120'"]) }}
                                                <div class="pt-1">{{ __('Uploaded Image') }}&nbsp;-&nbsp;
                                                    @if ($post->getThumbnailPath())
                                                        <a target="_blank" href="{{ asset($coverThumb) }}">
                                                            <img class="thumb-img" src="{{ asset($coverThumb) }}">
                                                        </a>
                                                        @php 
                                                            $resourceFromMedia = $post->getMedia('course_cover_image');
                                                            $resourceFile = count($resourceFromMedia) ? $resourceFromMedia[0]->id : null; 
                                                        @endphp
                                                        @if($resourceFile)
                                                            <a onclick="return confirm('Are you sure you want to delete?')"
                                                                href="{{ route('member.media.destroy', $resourceFile) }}" class="text-danger">
                                                                <i class="fas fa-trash"></i> @lang('Remove')
                                                            </a>
                                                        @endif
                                                        <!-- @forelse($post->getMedia('course_cover_image') as $image)
                                                            <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                                                <img class="img-fluid" src="{{ asset($post->getThumbnailPath()) }}">
                                                            </a>
                                                            @empty
                                                        @endforelse                                               -->
                                                    @endif
                                                </div>
                                        @else
                                            {{ Form::file('cover_image',
                                                ['class' => $errors->has('cover_image') ? 'form-control is-invalid' : 'form-control' ] ) }} 
                                            <!-- 'v-validate' => "'required|image|size:5120'"   -->
                                            <div v-show="errors.has('cover_image')" class="invalid-feedback">@{{ errors.first('cover_image') }}</div>
                                            {!! $errors->first('cover_image', '<div class="invalid-feedback">:message</div>') !!}
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="resource_file" class="">{{ __('Resource File') }}</label>
                                        @if(isset($post->id))
                                            {{ Form::file('resource_file',
                                            ['class' => $errors->has('resource_file') ? 'form-control is-invalid' : 'form-control', 
                                            'v-validate' => "'ext:zip,rar,docx,pdf|size:5242880'"]) }}
                                        <small>.zip, .rar, .docx and .pdf</small>
                                        <div style="padding: 10px 0px;">
                                            @foreach($post->getMedia('course_resource_file') as $resource)
                                                <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                                <a onclick="return confirm('Are you sure you want to delete?')"
                                                    href="{{ route('member.media.destroy', $resource->id) }}" class="text-danger">
                                                    <i class="fas fa-trash"></i> @lang('Remove')
                                                </a>
                                            @endforeach
                                        </div>
                                        @else
                                            {{ Form::file('resource_file', ['class' => $errors->has('resource_file') ? 
                                                'form-control is-invalid' : 'form-control', 'v-validate' => "'ext:zip,rar,docx,pdf|size:5242880'"]) }}
                                            <small>.zip, .rar, .docx and .pdf</small>
                                        @endif
                                        <div v-show="errors.has('resource_file')" class="invalid-feedback">@{{ errors.first('resource_file') }}</div>
                                        {!! $errors->first('resource_file', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>                        

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user_type">{{ __('Accessible Right') }}</label>
                                        @foreach ($userTypes as $key => $value)
                                            <div class="form-check">
                                                @if (isset($post) && $post->privacies)
                                                    @php
                                                        $privacies = array();
                                                        $post_privacies = $post->privacies;
                                                        // $privacies = $post->privacies->pluck('user_type')->toArray();
                                                        foreach ($post_privacies as $post_privacy) {
                                                            $privacies[] = $post_privacy->user_type;
                                                        }
                                                    @endphp

                                                    {{ Form::checkbox('user_type[]', $key, in_array($key, $privacies)? true : false, ['id' => 'user_type_' . $key,
                                                    'class' => $errors->has('user_type')? 'form-check-input is-invalid' : 'form-check-input', 
                                                    'v-validate' => "'required'", 'onclick' =>  in_array($key, $default_rights) ? "return false;" : "return true;"] ) }}

                                                @else 
                                                    {{ Form::checkbox('user_type[]', $key, in_array($key, $default_rights), ['id' => 'user_type_' . $key,
                                                    'class' => $errors->has('user_type')? 'form-check-input is-invalid' : 
                                                    'form-check-input', 'v-validate' => "'required'"
                                                    , 'onclick' =>  in_array($key, $default_rights) ? "return false;" : "return true;"]) }}
                                                @endif
                                                <label class="form-check-label" title="{{ $value }}"> {{ Form::label('user_type_' . $key, $value) }} </label>
                                                @if ($loop->last)
                                                    {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                                                    <div v-show="errors.has('user_type[]')" class="invalid-feedback">Please select at least one Right.</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="allow_feedback">{{ __('Allow Feedback?') }}</label>
                                        <div class="row">
                                            <div class="form-check form-check-inline">
                                                    {{ Form::radio('allow_feedback', 1, (isset($post->allow_feedback) && $post->allow_feedback == 1 ? true : false ), 
                                                        ['id' => 'allow_feedback_yes', 'class' => 'form-check-input']) }}
                                                <label for="allow_feedback_yes" class="form-check-label">@lang('Yes')</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('allow_feedback', 0, (!isset($post->allow_feedback) || $post->allow_feedback == 0 ? true : false ), 
                                                    ['id' => 'allow_feedback_no', 'class' => 'form-check-input']) }}
                                                <label for="allow_feedback_no" class="form-check-label">@lang('No')</label>
                                            </div>
                                            {!! $errors->first('allow_feedback', '<p class="invalid-feedback">:message</p>') !!}
                                        </div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="allow_discussion">{{ __('Allow Discussion?') }}</label>
                                        <div class="row">
                                            <div class="form-check form-check-inline">
                                                    {{ Form::radio('allow_discussion', 1, (isset($post->allow_discussion) && $post->allow_discussion == 1 ? true : false ), 
                                                        ['id' => 'allow_discussion_yes', 'class' => 'form-check-input']) }}
                                                <label for="allow_discussion_yes" class="form-check-label">@lang('Yes')</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('allow_discussion', 0, (!isset($post->allow_discussion) 
                                                    || $post->allow_discussion == 0 ? true : false ), 
                                                    ['id' => 'allow_discussion_no', 'class' => 'form-check-input']) }}
                                                <label for="allow_discussion_no" class="form-check-label">@lang('No')</label>
                                            </div>
                                            {!! $errors->first('allow_discussion', '<p class="invalid-feedback">:message</p>') !!}
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label>{{ __('Estimated Time To Complete') }}&nbsp;<span class="required">*</span></label>
                                        @php
                                            $selectedEstimatedUnit = 0;
                                            if(isset($post->estimated_duration_unit)) {
                                                switch($post->estimated_duration_unit) {
                                                    case 'day(s)': $selectedEstimatedUnit = 1;break;
                                                    case 'week(s)': $selectedEstimatedUnit = 2;break;
                                                    case 'month(s)': $selectedEstimatedUnit = 3;break;
                                                    case 'year(s)': $selectedEstimatedUnit = 4;break;
                                                    default: $selectedEstimatedUnit = 0; //hour(s)
                                                }
                                            }     
                                        @endphp
                                        <div class="row">
                                            @if( isset($post) && \App\Repositories\CourseRepository::shouldCrudButtonsDisabled($post) )
                                                <small class="text-muted">&nbsp;<kbd>@lang('Cannot edit as the course already had course takers')</kbd></small>
                                                <div class="col-8">                                       
                                                    <input type="number" name="estimated_duration" id="estimated-duration" disabled class="form-control "                                                
                                                        value="{{ old('estimated_duration', isset($post->estimated_duration) ? $post->estimated_duration: '') }}"> 
                                                    <input type="hidden" name="estimated_duration" 
                                                        value="{{ isset($post->estimated_duration) ? $post->estimated_duration: '' }}" />
                                                </div>
                                                <div class="col-4">                                                   
                                                    {!! Form::select('estimated_duration_unit', $estimatedUnits, 
                                                        old('estimated_duration_unit', $selectedEstimatedUnit), 
                                                        ['class' => 'form-control disabled', 'disabled' => "true" ]) !!}
                                                    <input type="hidden" name="estimated_duration_unit" 
                                                        value="{{ old('estimated_duration_unit', $selectedEstimatedUnit) }}" />
                                                </div>     
                                            @else
                                                <div class="col-8">
                                                    <input v-validate="'required'" type="number" placeholder="@lang('Estimated Time')" 
                                                        name="estimated_duration" id="estimated-duration"
                                                        class="form-control{{ $errors->has('estimated_duration') ? ' is-invalid' : '' }}"
                                                        value="{{ old('estimated_duration', isset($post->estimated_duration) ? $post->estimated_duration: '') }}">
                                                        <div v-show="errors.has('estimated_duration')" class="invalid-feedback">@{{ errors.first('estimated_duration') }}</div>
                                                        {!! $errors->first('estimated_duration', '<div class="invalid-feedback">:message</div>') !!} 
                                                </div>
                                                <div class="col-4">                                                   
                                                    {!! Form::select('estimated_duration_unit', $estimatedUnits, 
                                                        old('estimated_duration_unit', $selectedEstimatedUnit), 
                                                        ['class' => $errors->has('estimated_duration_unit')?
                                                        'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) !!}
                                                    {!! $errors->first('estimated_duration_unit', 
                                                        '<p class="invalid-feedback">:message</p>') !!}
                                                </div>    
                                            @endif                                                                               
                                        </div>                                                                
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Grace Period To Notify(In Days)') }}&nbsp;
                                            <span class="required">*</span></label>
                                        <input v-validate="'required'" type="number" placeholder="@lang('Grace Period To Notify(In Days)')" 
                                                    name="grace_period_to_notify" id="grace_period_to_notify"
                                                    class="form-control{{ $errors->has('grace_period_to_notify') ? ' is-invalid' : '' }}"
                                                    value="{{ old('	grace_period_to_notify', isset($post->grace_period_to_notify) ? $post->grace_period_to_notify: 1) }}">
                                                {!! $errors->first('grace_period_to_notify', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('grace_period_to_notify')" class="invalid-feedback">@{{ errors.first('grace_period_to_notify') }}</div>                                                                                                      
                                    </div> 
                                    <div class="form-group">
                                        <label for="lang">{{__('Language') }}</label>
                                        {!! Form::select('lang', \App\Models\Course::LANGUAGES, old('lang', 
                                            isset($post->lang)? $post->lang:''), 
                                            ['class' => $errors->has('lang') ? 'form-control is-invalid' : 'form-control' ]) !!}
                                        {!! $errors->first('lang', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('lang')" class="invalid-feedback">@{{ errors.first('lang') }}</div>
                                    </div> 
                                    @if (isset($post))
                                        @if($canPublish)
                                            @if(count($post->lectures))
                                           
                                                <div class="form-group">
                                                    <label>{{ __('Published?') }}</label>
                                                    <div class="form-check form-check-inline">
                                                        {{ Form::radio('is_published', 1, (isset($post->is_published) && $post->is_published == 1 ? true : false ), 
                                                            ['id' => 'is_published_yes', 'class' => 'form-check-input']) }}
                                                        <label for="is_published_yes" class="form-check-label">@lang('Yes')</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        {{ Form::radio('is_published', 0, (!isset($post->is_published) || $post->is_published == 0 ? true : false ), 
                                                            ['id' => 'is_published_no', 'class' => 'form-check-input']) }}
                                                        <label for="is_published_no" class="form-check-label">@lang('No')</label>
                                                    </div>
                                                    {!! $errors->first('is_published', '<p class="invalid-feedback">:message</p>') !!}
                                                </div> <!-- need to preserve the saved value -->
                                            @else
                                                <div class="form-group">
                                                    <label>
                                                        <span class="tooltip-info" data-toggle="tooltip" data-placement="right" 
                                                        title="@lang('After having a lecture, course can be published!')">
                                                        {{ __('Published?') }}</span>
                                                    </label>
                                                    <div class="form-check form-check-inline disabled">
                                                        {{ Form::radio('is_published', 1,  false, 
                                                            ['id' => 'is_published_yes', 'class' => 'form-check-input disabled', 'disabled' => 'disabled' ] ) }}
                                                        <label for="is_published_yes" class="form-check-label">@lang('Yes')</label>
                                                    </div>
                                                    <div class="form-check form-check-inline disabled">
                                                        {{ Form::radio('is_published', 0,  true , 
                                                            ['id' => 'is_published_no', 'class' => 'form-check-input disabled', 'disabled' => 'disabled' ]) }}
                                                        <label for="is_published_no" class="form-check-label">@lang('No')</label>
                                                    </div>                  
                                                </div>
                                            @endif
                                        @else 
                                            <input type="hidden" name="is_published" value="{{ isset($post->is_published) ? $post->is_published : 0 }}" >            
                                        @endif
                                        
                                    @else
                                        <div class="form-group">
                                                <label>
                                                    <span class="tooltip-info" data-toggle="tooltip" data-placement="right" 
                                                    title="@lang('After saving and having a lecture, couse can be published!')">
                                                    {{ __('Published?') }}</span>
                                                </label>
                                                <div class="form-check form-check-inline disabled">
                                                    {{ Form::radio('is_published', 1,  false, 
                                                        ['id' => 'is_published_yes', 'class' => 'form-check-input disabled', 'disabled' => 'disabled' ] ) }}
                                                    <label for="is_published_yes" class="form-check-label">@lang('Yes')</label>
                                                </div>
                                                <div class="form-check form-check-inline disabled">
                                                    {{ Form::radio('is_published', 0,  true , 
                                                        ['id' => 'is_published_no', 'class' => 'form-check-input disabled', 'disabled' => 'disabled' ]) }}
                                                    <label for="is_published_no" class="form-check-label">@lang('No')</label>
                                                </div>                  
                                        </div>
                                    @endif

                                    @if ( !isset($post) || ( isset($post) && $post->user_id == auth()->user()->id ) ) 
                                        <div class="form-group">
                                            <label>{{__('Course Collaborators')}}</label> 
                                            {{-- Form::select('collaborator_id',$uploadedBy, 
                                                old('collaborator_id', isset($post->collaborator_id) ? $post->collaborator_id : ''),
                                                 ['class' => $errors->has('collaborator_id')?
                                                'form-control is-invalid' : 'form-control' ]) !!} {!! $errors->first('collaborator_id', '
                                                <div class="invalid-feedback">:message</div>') 
                                            --}}
                                            <select class="select2" multiple="multiple" data-placeholder="{{ __('Select Collaborators') }}" 
                                                style="width: 100%;" name="collaborators[]" selected="">
                                                @if(isset($post->collaborators))
                                                    @foreach($post->collaborators as $col)
                                                        <option value="{{$col}}" selected="selected">{{$uploadedBy[$col]}}</option>
                                                            <?php unset($uploadedBy[$col]); ?>
                                                    @endforeach 
                                                @endif
                                                @foreach($uploadedBy as $idx=>$col)
                                                    <option value="{{$idx}}">{{$col}}</option>
                                                @endforeach                                       
                                            </select>
                                        </div>
                                    @endif 
                                    <div class="form-group">
                                        <label>{{__('Keywords for Related Resources')}}</label> 
                                        <textarea name="related_resources" placeholder="Related Resources..."   
                                                id="related_resources" class="form-control {{ $errors->has('related_resources') ? ' is-invalid' : '' }}" 
                                            >{{old('related_resources', isset($post->related_resources) ? $post->related_resources: '')}}</textarea>
                                            {!! $errors->first('related_resources', '<div class="invalid-feedback">:message</div>') !!}
                                            <div v-show="errors.has('related_resources')" class="invalid-feedback">@{{ errors.first('related_resources') }}</div>
                                    </div>
                                    @if($canApprove)
                                        <div class="form-group">
                                            <label>{{ __('Allow Edit(Collaborators)?') }}</label>
                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('allow_edit', 1, (isset($post->allow_edit) && $post->allow_edit == 1 ? true : false ), 
                                                    ['id' => 'allow_edit_yes', 'class' => 'form-check-input']) }}
                                                <label for="allow_edit_yes" class="form-check-label">@lang('Yes')</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('allow_edit', 0, (!isset($post->allow_edit) || $post->allow_edit == 0 ? true : false ), 
                                                    ['id' => 'allow_edit_no', 'class' => 'form-check-input']) }}
                                                <label for="allow_edit_no" class="form-check-label">@lang('No')</label>
                                            </div>
                                            {!! $errors->first('allow_edit', '<p class="invalid-feedback">:message</p>') !!}
                                        </div>                                      

                                        @if($canLock)
                                            <div class="form-group">
                                                <label>{{ __('Locked(prevent other collaborators from adding)?') }}</label>
                                                <div class="form-check form-check-inline">
                                                    {{ Form::radio('is_locked', 1, (isset($post->is_locked) && $post->is_locked == 1 ? true : false ), 
                                                        ['id' => 'is_locked_yes', 'class' => 'form-check-input']) }}
                                                    <label for="is_locked_yes" class="form-check-label">@lang('Yes')</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    {{ Form::radio('is_locked', 0, (!isset($post->is_locked) || $post->is_locked == 0 ? true : false ), 
                                                        ['id' => 'is_locked_no', 'class' => 'form-check-input']) }}
                                                    <label for="is_locked_no" class="form-check-label">@lang('No')</label>
                                                </div>
                                                {!! $errors->first('is_locked', '<p class="invalid-feedback">:message</p>') !!}
                                            </div>
                                        @else
                                            <input type="hidden" name="is_locked" value="0" />
                                        @endif                      
                                        <div class="form-group">
                                            <label for="approval_status">{{__('Approval Status')}}</label> 
                                            {!! Form::select('approval_status',
                                                $approvalStatus, old('approval_status', isset($post->approval_status) ? $post->approval_status: ''),
                                                 ['class' => $errors->has('approval_status')?
                                                'form-control is-invalid' : 'form-control' ]) !!} {!! $errors->first('approval_status', '
                                            <div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                    
                                    @else
                                        <input type="hidden" name="allow_edit" value="{{ isset($post->allow_edit) ? $post->allow_edit : 1 }}" >
                                        <!-- should be editable after creating the course by the teacher !-->
                                    @endif
                                        
                                </div>  
                                <div class="form-group">
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">
                                        {{ __('Save') }}
                                    </button>
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="1">
                                        {{ __('Save & Close') }}
                                    </button>
                                    @if(!isset($post->id))
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSaveNew" value="1">
                                        {{ __('Save & New') }}
                                    </button>
                                    @endif
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSaveNext" value="1">
                                        {{ __('Save & Next') }}
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
