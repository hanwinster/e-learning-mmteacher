@extends('backend.layouts.default')

@if (isset($post->id)) 
    @section('title', __('Edit Assessment Question & Answers'))
@else 
    @section('title', __('New Assessment Questions'))
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
                                {{ __('Edit Assessment Question & Answers') }} 
                            @else 
                                {{ __('New Assessment Question & Answers') }}                         
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
                                                            
                <div class="col-12 mx-auto" id="elearning_root">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                @if (isset($post->id)) 
                                    {{ __('Edit Assessment Question & Answers') }} 
                                @else 
                                    {{ __('New Assessment Question & Answers') }}                         
                                @endif                     
                            </h5>
                        </div>
                        <div class="card-body">
                                <!-- '@submit' => 'validateBeforeSubmit'  -->
                                @if (isset($post))
                                    {!! \Form::open(array( 'method' => 'put', 'route' => 
                                        array('member.course.assessment-qa.update',$post->id), 'class' => 'form-horizontal' )) !!} <!-- '@submit' => 'validateBeforeSubmit' -->
                                @else
                                    {!! \Form::open(array( 'route' => ['member.course.assessment-qa.store', $course->id],
                                        'class' => 'form-horizontal', 'method' => 'post' )) !!}
                                @endif
                                    {!! Form::hidden('redirect_to', url()->previous()) !!}                      
                                        <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                                    @if (isset($post->id))
                                        <input type="hidden" name="id" id="id" value="{{ $post->id }}">
                                    @endif
                                    <input type="hidden" name="course_id" id="course-id" value="{{ $course->id }}">
                            <div class="row">
                                <div class="col-12">                                                              
                                    <div class="form-group">
                                        <label for="title">@lang('Question Title')&nbsp;
                                            <span class="required">*</span>&nbsp;
                                            <span class="text-warning f-500">
                                                @lang('Please provide serial numbers (e.g. (1),(2),(3)...) if you want to display them before the question')
                                            </span>
                                        </label>
                                        <textarea  v-validate="'required'" id="assessment-q" 
                                            class="form-control summernote{{ $errors->has('question') ? ' is-invalid' : '' }}"
                                            name="question" placeholder="@lang('Question')"
                                            >{{ old('question', isset($post->question) ? $post->question: '')}}</textarea>
                                        {!! $errors->first('question', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('question')" class="invalid-feedback">@{{ errors.first('question') }}</div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="title">@lang('Question Order')&nbsp;
                                            <span class="required">*</span>
                                        </label>
                                        <input type="number"  v-validate="'required'" id="assessment-order" 
                                            class="form-control {{ $errors->has('order') ? ' is-invalid' : '' }}"
                                            name="order" value="{{ old('order', isset($post->order) ? $post->order: '')}}" />
                                        {!! $errors->first('order', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('order')" class="invalid-feedback">@{{ errors.first('order') }}</div>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="type" class="col-xs-12 require">
                                            {{__('Question Type') }}&nbsp;<span class="required">*</span>
                                        </label>
                                        @if(isset($post->type))
                                        {!! Form::select('type', $types, old('type', isset($post->type)? $post->type:
                                            ''), ['class' => $errors->has('type')?
                                            'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'", 'id'=>'assessment-type', 'disabled' => true ]) !!}
                                            <input type="hidden" name="type" id="type" value="{{$post->type}}">
                                        @else
                                            {!! Form::select('type', $types, old('type', isset($post->type)? $post->type:
                                            ''), ['class' => $errors->has('type')?
                                            'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'", 'id'=>'assessment-type' ]) !!}
                                        @endif
                                        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('type')" class="invalid-feedback">@{{ errors.first('type') }}</div>
                                    </div> 
                                    <div class="form-group">
                                        @if(isset($post->type))
                                            @if($post->type == '')
                                                <label for="title">@lang('Answers')&nbsp;
                                                    <span class="required">*</span>&nbsp; 
                                                </label>
                                            @else 

                                            @endif
                                        @else
                                            <label for="title">@lang('Please save Question Type first and then enter answers')&nbsp;
                                                <span class="required">*</span>&nbsp; 
                                            </label>
                                        @endif
                                    </div>
                                    @if(isset($post->type))
                                        <div id="answers-wrapper" class="text-left">
                                        
                                            @if($post->type == \App\Models\AssessmentQuestionAnswer::TRUE_FALSE) 
                                                <div id="assess-true_false" class="">                                       
                                                    @include('frontend.member.assessment.partials.true_false_form', [ $post ]) 
                                                </div>
                                            @elseif($post->type == \App\Models\AssessmentQuestionAnswer::MULTIPLE_CHOICE) 
                                                <div id="assess-multiple_choice" class="">        
                                                    @include('frontend.member.assessment.partials.multiple_choice_form', [ $post ])  
                                                </div>
                                            @elseif($post->type == \App\Models\AssessmentQuestionAnswer::MATCHING)
                                                <div id="assess-rearrange" class="">  
                                                    <p class="text-warning">@lang('Please provide the answers in correct pairs!')</p>    
                                                    @include('frontend.member.assessment.partials.matching_form', [ $post ])  
                                                </div>
                                            @elseif($post->type == \App\Models\AssessmentQuestionAnswer::REARRANGE)
                                                <div id="assess-matching" class="">
                                                    <p class="text-warning">@lang('Please provide the answers in correct order!')</p>            
                                                    @include('frontend.member.assessment.partials.rearrange_form', [ $post ])  
                                                </div>
                                            @else
                                                <div id="assess-long-answer" class="">
                                                    <p class="text-warning">@lang('Please provide the suggested answer!')</p>            
                                                    @include('frontend.member.assessment.partials.long_answer_form', [ $post ])  
                                                </div>
                                            @endif
                                            </div>        
                                    @endif 
                                        
                                    </div>
                                    
                                </div>
                                
                                <div class="form-group mt-3">
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">
                                        {{ __('Save') }}
                                    </button>
                                    @if(isset($post))
                                        <input class="btn btn-primary" type="submit" name="btnSaveNew" value="{{ __('Save & New') }}">
                                        <button class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="1">
                                            {{ __('Save & Close') }}
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('member.course.show', $course->id).'#nav-assessment' }}" class="btn btn-md btn-outline-dark">{{ __('Cancel') }}</a>
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

