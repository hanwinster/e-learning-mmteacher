@extends('backend.layouts.default')

@if (isset($post->id)) 
    @section('title', __('Edit Order'))
@else 
    @section('title', __('New Order'))
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
                        <li class="breadcrumb-item active">{{ __('Edit Order') }}</li>                                                 
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
                               {{ __('Edit Order') }}                              
                            </h5>
                        </div>
                        <div class="card-body">
                                <!-- '@submit' => 'validateBeforeSubmit'  -->
                                @if (isset($post))
                                    {!! \Form::open(array('files' => false, 'method' => 'put', 
                                        'route' => array('member.course.order.update', $post->id),
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
                                                {{ __('Order Type') }}?
                                            </label>
                                        </div>                                 

                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('order_type', 'default', (isset($post->order_type) &&
                                                     $post->order_type == 'default' ? true : false ),
                                                    ['id' => 'order_type', 'class' => 'form-check-input o-type']) }}
                                                <label for="order_type" class="form-check-label">{{ __('Default Order') }}</label>
                                            </div>
                                           
                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('order_type', 'flexible', (isset($post->order_type) &&
                                                     $post->order_type == 'flexible' ? true : false ),
                                                    ['id' => 'order_type', 'class' => 'form-check-input o-type']) }}
                                                <label for="order_type" class="form-check-label">{{ __('Flexible Order') }}</label>
                                            </div>
                                            {!! $errors->first('order_type', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    @php $orderType = $post->order_type ? $post->order_type : 'default'; @endphp
                                    <table id="order-table" class="table table-sm {{ $orderType === 'flexible' ? '' : 'd-none' }}">
                                        
                                        
                                            <tr>
                                                <td><label>@lang('Order')</label></td>
                                                <td><label>@lang('Section/Item')</label></td> 
                                            </tr>                                       
                                                @foreach($mainSectionsForFelxible as $idx=>$po)
                                                    @php 
                                                        $selectedArr = $post->orders && isset($post->orders[$idx]) ? array_keys($post->orders[$idx]) : null; 
                                                        $selected = $selectedArr ? $selectedArr[0] : null; 
                                                       // echo " $selected <br/>";
                                                    @endphp
                                                    @if(strpos($selected, 'assessment_') === false && 
                                                        strpos($selected, 'lla_') === false && strpos($selected, 'lq_') === false && 
                                                        strpos($selected, 'lsess_') === false && strpos($selected, 'lsum_') === false)
                                                        <tr>
                                                            <td>
                                                                <h6>{{ $idx+1 }}</h6>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">                                                                                                                           
                                                                    <select class="form-control" name="orders[]" > 
                                                                        @foreach($mainSectionsForFelxible as $key => $flex)                                                                     
                                                                            @foreach($flex as $k => $val)
                                                                                @if($k && strpos($k, 'assessment_') === false)                                                                         
                                                                                    @if($selected && $selected == $k)
                                                                                        <option value="{{$k}}" selected="selected">
                                                                                            {{  strip_tags(\App\Repositories\CourseRepository::getTitleFromValue($k,$post)) }}
                                                                                        </option>
                                                                                    @else 
                                                                                        <option value="{{$k}}">
                                                                                            {{  strip_tags(\App\Repositories\CourseRepository::getTitleFromValue($k,$post)) }}
                                                                                        </option>
                                                                                    @endif 
                                                                                @endif                                                                           
                                                                            @endforeach
                                                                        @endforeach                                                                  
                                                                    </select>                                                              
                                                                </div>
                                                            </td>
                                                        </tr> 
                                                    @endif                                        
                                                @endforeach                                           
                                    </table>
                                    <!-- TODO: to provide info of assessment if there's any -->
                                                                     
                                </div>
                                <div class="col-12 {{ $orderType === 'flexible' ? '' : 'd-none' }}" id="lecture-section-for-flexible">                                                              
                                    <div class="form-group">
                                        <div>
                                            <label class="col-xs-12">
                                                {{ __('Lecture Order Type') }}?
                                            </label>
                                        </div>                                 

                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('lecture_order_type', 'default', (isset($post->lecture_order_type) &&
                                                     $post->lecture_order_type == 'default' ? true : false ),
                                                    ['id' => 'lecture_order_type', 'class' => 'form-check-input lecture-o-type']) }}
                                                <label for="order_type" class="form-check-label">{{ __('Default Order') }}</label>
                                            </div>
                                           
                                            <div class="form-check form-check-inline">
                                                {{ Form::radio('lecture_order_type', 'flexible', (isset($post->lecture_order_type) &&
                                                     $post->lecture_order_type == 'flexible' ? true : false ),
                                                    ['id' => 'lecture_order_type', 'class' => 'form-check-input lecture-o-type']) }}
                                                <label for="lecture_order_type" class="form-check-label">{{ __('Flexible Order') }}</label>
                                            </div>
                                            {!! $errors->first('lecture_order_type', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    @php $lectureOrderType = $post->lecture_order_type ? $post->lecture_order_type : 'default'; @endphp
                                    <table id="lecture-order-table" class="table table-sm {{ $lectureOrderType === 'flexible' ? '' : 'd-none' }}">                                                                           
                                            <tr>
                                                <td><label>@lang('Order')</label></td>
                                                <td><label>@lang('Lecture Title')</label></td>
                                                <td><label>@lang('Section/Item')</label></td> 
                                            </tr>                                       
                                                @foreach($lectureSectionsForFlexible as $idxL => $lectureArr)
                                                    @foreach($lectureArr as $idx6 => $lecture)
                                                       
                                                    
                                                        <tr>
                                                            <td>
                                                                <h6>{{ $idxL+1 }}</h6>
                                                            </td>
                                                            <td>
                                                                <h6>{{ strip_tags($lectureTitles[$idxL]) }}</h6>
                                                            </td>
                                                            <td>
                                                                @for($i=0; $i < count($lecture); $i++)
                                                                @php 
                                                                    // $selectedArrLect = $post->lecture_orders && isset($post->lecture_orders[$idx]) ? 
                                                                    //                 array_keys($post->lecture_orders[$idxL]) : null; 
                                                                    $selectedLect = $post->lecture_orders ? $lecture[$i] : null;  //echo $idx6; got lectureIds
                                                                // echo " $selected <br/>";
                                                                //dd(count($lecture));exit;
                                                                @endphp
                                                                    <div class="form-group">                                                                                                                           
                                                                        <select class="form-control" name="lecture_orders[{{$idxL}}][{{$lectureIDs[$idxL]}}][]" >                                                                          
                                                                            @foreach($lecture as $idx3 => $val)    
                                                                                @if($selectedLect && $selectedLect == $val)
                                                                                    <option value="{{$val}}" selected="selected">
                                                                                        {{  strip_tags(\App\Repositories\CourseRepository::getTitleFromValue($val,$post)) }}
                                                                                    </option>
                                                                                @else 
                                                                                    <option value="{{$val}}">
                                                                                        {{  strip_tags(\App\Repositories\CourseRepository::getTitleFromValue($val,$post)) }}
                                                                                    </option>
                                                                                @endif                                                                               
                                                                                                                                                           
                                                                            @endforeach                                                                                                                                    
                                                                        </select>                                                              
                                                                    </div>
                                                                @endfor
                                                            </td>
                                                        </tr> 
                                                    @endforeach                                   
                                                @endforeach                                           
                                    </table>
                                    <!-- TODO: to provide info of assessment if there's any -->
                                                                     
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">
                                        {{ __('Save') }}
                                    </button>
                                    <button class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="1">
                                        {{ __('Save & Close') }}
                                    </button>
                                    <a href="{{ route('member.course.show', $post->id).'#nav-order' }}" class="btn btn-md btn-outline-dark">{{ __('Cancel') }}</a>
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
