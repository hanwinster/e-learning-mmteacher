@extends('frontend.layouts.default')

@section('title', str_limit(strip_tags($course->title), 30) . ' - '. __('Courses'))

@section('content')
<section class="page-section learning-area" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-3 border-shadow-box">
                @if($course->order_type == 'default')
                    @include('frontend.courses.partials.course-default')
                @else 
                    @include('frontend.courses.partials.course-flexible')
                @endif
            </div>
            <div class="col-12 col-md-9">
                @include('frontend.layouts.form_alert')
               
                <div class="row">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">{{ __('Courses') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.my-courses') }}">{{ __('My Courses') }}</a></li>
                            <li class="breadcrumb-item active">{{ strip_tags($course->title) }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-1"></div>
                    <div class="col-12 col-lg-10 course-content-area">
                            <div class ="row px-2">
                                <div class="col-12">
                                    <h5 class="mt-2 mb-3">
                                        @lang('Survey questions for course evaluation')
                                    </h5>
                                    <p class="mt-2 mb-3">
                                        {{__('Note:')}}&nbsp;&nbsp;
                                        {{__('This survey is to receive your feedback on the course taken and the feedback will be used solely to improve the quality of course in future.')}}
                                        {{__('It will take 5 minutes of your time to answer the following questions and we greatly appreciate your honest responses.')}}
                                    </p>
                                </div>
                                
                            </div> 
                                @if (isset($post) && isset($post->id))
                                    {!! \Form::open(array('method' => 'PUT', 'route' => array('courses.update-evaluation', $post->id) )) !!}
                                @else
                                    {!! \Form::open(array('method' => 'POST', 'route' => 'courses.create-evaluation' )) !!}
                                @endif
                                <div class ="row px-2"> 
                                    <div class="col-12">
                                        @foreach($evaluationQs as $idx => $evaluation)
                                            <div class="form-group form-eva-align">
                                                <label for="question_".{{$idx}} class="d-block">
                                                    {{$idx+1 }}.&nbsp;
                                                    @php $isMm = App::getLocale() == 'my-MM' ? true : false; @endphp
                                                    @if($isMm)
                                                        {{ $evaluation->question_mm ? $evaluation->question_mm : $evaluation->question }}
                                                    @else
                                                        {{$evaluation->question}}
                                                    @endif
                                                       
                                                </label>
                                                @if ( $evaluation->type == 'agree_disagree' ) 
                                                    @for($i = 5; $i > 0; $i--)
                                                        <div class="form-check form-check-inline"> 
                                                            @php $nameA = "feedbacks[$idx]"; @endphp
                                                            <input type="radio" name="feedbacks[{{$idx}}]" id='eva_ans_{{$idx}}_{{$i}}'
                                                                    value="{{$i}}" {{ old( $nameA, isset($post->feedbacks[$idx]) && $post->feedbacks[$idx] == $i  ? 'checked' : '' ) }} >
                                                            <label for='eva_ans_{{$idx}}_{{$i}}' class="form-check-label">
                                                                {{ __($agreeLevels[$i]) }}
                                                            </label>
                                                        </div>
                                                    @endfor

                                                @elseif ( $evaluation->type == 'excellent_poor' )
                                                    @for($i = 5; $i > 0; $i--)
                                                        <div class="form-check form-check-inline"> 
                                                            <input type="radio" name="feedbacks[{{$idx}}]" id='eva_ans_{{$idx}}_{{$i}}'
                                                                    value="{{$i}}" {{ (isset($post->feedbacks[$idx]) && 
                                                                        $post->feedbacks[$idx] == $i ? 'checked' : '' ) }} >
                                                            <label for='eva_ans_{{$idx}}_{{$i}}' class="form-check-label">
                                                                {{ __($excellentLevels[$i]) }}
                                                            </label>
                                                        </div>
                                                    @endfor                                                
                                                @elseif ( $evaluation->type == 'likely_unlikely' )
                                                    @for($i = 5; $i > 0; $i--)
                                                        <div class="form-check form-check-inline"> 
                                                            <input type="radio" name="feedbacks[{{$idx}}]" id='eva_ans_{{$idx}}_{{$i}}'
                                                                    value="{{$i}}" {{ (isset($post->feedbacks[$idx]) && 
                                                                        $post->feedbacks[$idx] == $i ? 'checked' : '' ) }} >
                                                            <label for='eva_ans_{{$idx}}_{{$i}}' class="form-check-label">
                                                                {{ __($likelyLevels[$i]) }}
                                                            </label>
                                                        </div>
                                                    @endfor
                                                @elseif ( $evaluation->type == 'device_options' )
                                                    @for($i = 1; $i < 5; $i++)
                                                        <div class="form-check form-check-inline"> 
                                                            <input type="radio" name="feedbacks[{{$idx}}]" id='eva_ans_{{$idx}}_{{$i}}'
                                                                    value="{{$i}}" {{ (isset($post->feedbacks[$idx]) && 
                                                                        $post->feedbacks[$idx] == $i ? 'checked' : '' ) }} >
                                                            <label for='eva_ans_{{$idx}}_{{$i}}' class="form-check-label">
                                                                {{ __($deviceOptions[$i]) }}
                                                            </label>
                                                        </div>
                                                    @endfor
                                                @else  

                                                    <textarea name="feedbacks[{{$idx}}]" class="form-control"
                                                    >@if(isset($post)) {{$post->feedbacks[$idx] }} @endif</textarea>                 
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>                                
                                </div>                    
                                <div class="text-right mr-5 mt-3">
                                    <a href="{{ isset($previousSection) ? $previousSection : '#'  }}" class="btn btn-outline-primary btn-sm p-2 mb-2 }}">
                                        &lt;&nbsp;@lang('Previous') 
                                    </a>                   
                                    
                                    <a href="#" class="btn btn-outline-primary btn-sm p-2 mb-2 disabled">@lang('Next')></a>
                                    
                                    @if($isSubmitted)
                                        <button class="btn btn-primary disabled btn-sm p-2 mb-2" 
                                            >@lang('Submitted')&nbsp;<i class="fas fa-check"></i>
                                        </button>
                                    @else
                                        <input type="hidden" name="course_id" value="{{$course->id}}">
                                        <button name="submit-eva" value="1" class="btn btn-primary btn-sm p-2 mb-2" 
                                            type="submit">@lang('Save & Return Later')
                                        </button>   
                                        <button name="submit-eva" value="2" class="btn btn-primary btn-sm p-2 mb-2" 
                                            type="submit">@lang('Submit')
                                        </button>                                 
                                    @endif
                                                    
                                </div>
                            </form>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
