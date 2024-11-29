@extends('backend.layouts.default')

@if (isset($post->id)) 
    @section('title', __('Edit Assessment'))
@else 
    @section('title', __('New Assessment'))
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
                        <li class="breadcrumb-item"><a href="/{{ config('app.locale') }}/profile/course/{{$post->id}}">{{ strip_tags($post->title) }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id))  
                                {{ __('Edit Assessment') }} 
                            @else  
                                {{ __('New Assessment') }}                         
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
                                                            
                <div class="col-12 mx-auto" id="elearning_root">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                               {{ __('Edit Assessment') }}                              
                            </h5>
                        </div>
                        <div class="card-body">
                                <!-- '@submit' => 'validateBeforeSubmit'  -->
                                @if (isset($post))
                                    {!! \Form::open(array('files' => false, 'method' => 'put', 
                                        'route' => array('member.course.assessment.update', $post->id),
                                        'class' => 'form-horizontal' )) !!}                          
                                 @endif
                                    {!! Form::hidden('redirect_to', url()->previous()) !!} 
                                        <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                                    @if (isset($post->id))
                                        <input type="hidden" name="id" id="id" value="{{ $post->id }}">
                                    @endif
                            <div class="row">
                                <div class="col-12">                                                              
                                    <div class="form-group">
                                        <div>
                                            <label class="col-xs-12">
                                                {{ __('The items which will affect the certification') }}?
                                            </label>
                                        </div>                                 

                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('item_affect_certification', 1, (isset($post->item_affect_certification) &&
                                                     $post->item_affect_certification == 1 ? true : false ),
                                                    ['id' => 'item_affect_certification_assess', 'class' => 'form-check-input']) }}
                                                <label for="item_affect_certification_assess" class="form-check-label">{{ __('Assessment Score') }}</label>
                                            </div>
                                           
                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('item_affect_certification', 0, (isset($post->item_affect_certification) &&
                                                     $post->item_affect_certification == 0 ? true : false ),
                                                    ['id' => 'item_affect_certification_none', 'class' => 'form-check-input']) }}
                                                <label for="item_affect_certification_no" class="form-check-label">{{ __('Completion Only') }}</label>
                                            </div>
                                            {!! $errors->first('item_affect_certification', '<p class="help-block">:message</p>') !!}
                                    </div>
                                                                     
                                    <div class="form-group">
                                        <label for="acceptable-score-assessment" class="">@lang('Acceptable Score For Assessment')</label>
                                        <input v-validate="'required'" type="text" placeholder="@lang('Score')" name="acceptable_score_for_assessment"
                                             id="acceptable-score-assessment" class="form-control{{ $errors->has('acceptable_score_for_assessment') ? ' is-invalid' : '' }}"
                                            value="{{ old('acceptable_score_for_assessment', isset($post->acceptable_score_for_assessment) ?
                                                 $post->acceptable_score_for_assessment: '') }}">
                                        {!! $errors->first('acceptable_score_for_assessment', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('acceptable_score_for_assessment')" class="invalid-feedback">@{{ errors.first('acceptable_score_for_assessment') }}</div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">
                                        {{ __('Save') }}
                                    </button>
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="1">
                                        {{ __('Save & Close') }}
                                    </button>
                                    <a href="{{ route('member.course.show', $post->id).'#nav-assessment' }}" class="btn btn-md btn-outline-dark">{{ __('Cancel') }}</a>
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
