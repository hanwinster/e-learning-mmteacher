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
                        <li class="breadcrumb-item"><a href="{{ route('member.course.show', $course->id).'#nav-quiz' }}">{{ strip_tags($quiz->title) }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit Question') }} 
                            @else 
                                {{ __('New Question') }}                         
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
                <div class="col-12 mx-auto" id="lecture_root">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ __('Question') }}
                                @if (isset($post->id)) [@lang('Edit')] @else [@lang('New')] @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('member.question.update',
                                $post->id), 'class' => 'form-horizontal' )) !!}  {{-- @submit' => 'validateBeforeSubmit' --}}
                            @else
                            {!! \Form::open(array('files' => true, 'route' => ['member.question.store', $quiz->id],
                                'class' => 'form-horizontal')) !!}
                            @endif
                            {!! Form::hidden('redirect_to', url()->previous()) !!}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <!-- <h5>@lang('Quiz Title') : {{$quiz->title}}</h5> -->
                                        <input type="hidden" name="quiz_id" value="{{$quiz->id}}">
                                        <input type="hidden" name="quiz_type" id="quiz_type" value="{{$quiz->type}}">
                                    </div>  
                                    <div class="form-group">
                                        <label for="title">
                                            @if($quiz->type == \App\Models\Quiz::ASSIGNMENT)
                                                @lang('Assignment Title')&nbsp;
                                            @else
                                                @lang('Question Title')&nbsp;
                                            @endif
                                            <span class="required">*</span>&nbsp;
                                            <span class="text-warning f-500">
                                                @lang('Please provide serial numbers (e.g. (1),(2),(3)...) if you want to display them before the question')
                                            </span>
                                        </label>
                                        <textarea  v-validate="'required'" id="title" 
                                            class="form-control summernote{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                            name="title" placeholder="Title.."   >{{ old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                                    </div>
                                    @if($quiz->type !== \App\Models\Quiz::ASSIGNMENT)
                                        <div class="form-group">
                                            <label for="attached_file">{{ __('Image File') }}</label>
                                            @if(isset($post->id))
                                            {{ Form::file('attached_file',
                                            ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 
                                                'v-validate' => "'image|size:1024'"]) }}
                                                @if ($post->getThumbnailPath())
                                                <div style="padding-top: 5px;" id="file_wrap">
                                                    @forelse($post->getMedia('question_attached_file') as $image)
                                                        <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                                            <img src="{{ asset($image->getUrl('thumb')) }}">
                                                        </a>
                                                        <a href="javascript::void(0)" data-href="{{ route('member.ajax.media.destroy', $image->id) }}" 
                                                            data-id class="text-danger remove_image">@lang('Remove')</a>
                                                    @empty
                                                    @endforelse
                                                </div>
                                                @endif
                                                <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                                            @else
                                            {{ Form::file('attached_file',
                                            ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 
                                                'v-validate' => "'image|size:1024'"]) }}
                                            @endif
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="type">@lang('Quiz Type')</label>
                                        <input type="text" class="form-control" value="{{ $quiz->getQuizType() }}" disabled >
                                    </div>
                                    @if($quiz->type !== \App\Models\Quiz::ASSIGNMENT)
                                        <div class="form-group">
                                            <label>@lang('Answer/Suggested Answer')
                                                <span class="required">*</span>                                               
                                            </label>
                                        
                                            @if($quiz->type == \App\Models\Quiz::TRUE_FALSE)
                                                @include('frontend.member.question.true_false_form')
                                            @elseif($quiz->type == \App\Models\Quiz::BLANK)
                                                @include('frontend.member.question.blank_form')
                                            @elseif($quiz->type == \App\Models\Quiz::SHORT_QUESTION)
                                                @include('frontend.member.question.short_question_form')
                                            @elseif($quiz->type == \App\Models\Quiz::LONG_QUESTION)
                                                @include('frontend.member.question.long_question_form')
                                            @elseif($quiz->type == \App\Models\Quiz::MULTIPLE_CHOICE)
                                                @include('frontend.member.question.multiple_choice_form')
                                            @elseif($quiz->type == \App\Models\Quiz::REARRANGE)
                                                @include('frontend.member.question.rearrange_form')
                                            @elseif($quiz->type == \App\Models\Quiz::MATCHING)
                                                @include('frontend.member.question.matching_form')
                                            @else
                                            @endif
                                    @else
                                        @include('frontend.member.question.assignment_form')
                                    @endif
                                    <!-- Description Start -->
                                    <div class="form-group">
                                        <label for="description" class="">
                                        @if($quiz->type == \App\Models\Quiz::ASSIGNMENT)
                                            @lang('Assignment Instruction')
                                        @else 
                                            @lang('Detail Explanation')
                                        @endif
                                        </label>
                                        <textarea  v-validate="''" id="description" name="description" 
                                            class="form-control summernote {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                            placeholder="Description...">{{old('description', isset($post->description) ? 
                                                $post->description: '')}}</textarea>
                                        {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                                    </div>
                                    <!-- Description End -->                                              
                                </div>                    
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary btn-md" type="submit" name="btnSave" value="{{__('Save') }}">
                                @if(!isset($post))
                                    <input class="btn btn-primary btn-md" type="submit" name="btnSaveNew" value="{{__('Save & New') }}">
                                @endif
                                <input class="btn btn-primary btn-md" type="submit" name="btnSaveClose" value="{{__('Save & Close') }}">
                                <a href="{{ route('member.course.show', $course->id).'#nav-quiz' }}" class="btn btn-outline-dark">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')

<script type="text/javascript">
//$(document).ready(function() {
    $(function() { 
            
        var counter = @json($count); 
        var para = @json($paragraph); 
        var alphabets = @json($alphabets);    
        var numbers = @json($numbers);    
        var ele = [];
        if(para.length > 0) {
            for(let i=0; i<para.length; i++) {
                for(var key in para[i]) {
                    if(key.includes('sentence')) {
                        ele.push('sentence');
                    }
                    if(key.includes('blank')) {
                        ele.push('blank');
                    }
                }
            }
            //console.log(ele);
        }
        $('#add-text-area').on("click", function(e) {
            //alert('add text area clicked');
            let data = [];
            if(counter == 0 || ele.indexOf('sentence') == -1) { //first or not yet in the list, make a mandatory
                data.push(
                    '<label id="slabel'+counter+'">Sentence&nbsp;<span class="required">*</span></label>'+                       
                        '<textarea  name="sentence[]" v-validate="required" placeholder="Sentence..."   id="sentence'+counter+'" ' +
                    '   class="form-control mb-3"></textarea>\n' 
                );
            } else {
                data.push(
                    '<label id="slabel'+counter+'">Sentence</label>'+
                        '<a value="sentence'+counter+'_slabel'+counter+'" class="btn btn-sm text-danger delete-input" ><i class="fas fa-trash"></i></a> '+
                        '<textarea  name="sentence[]" placeholder="Sentence..."   id="sentence'+counter+'" ' +
                    '   class="form-control mb-3"></textarea>\n' 
                    );
            }
            $('#fill_in_the_blank').append(data);
            ele.push('sentence');
            counter++;
        });

        $('#add-blank-area').on("click", function(e) {
            //alert('add blank area clicked');
            let data = [];
            if(counter == 0 || ele.indexOf('blank') == -1) {
                data.push(
                    '<label id="blabel'+counter+'">Blank&nbsp;<span class="required">*</span></label>'+
                    '<input type="text"  name="blank[]" v-validate="required" placeholder="Blank..."   id="blank'+counter+'" ' +
                    '   class="form-control mb-3">\n' 
                );
            } else {
                data.push('<label id="blabel'+counter+'">Blank</label>'+
                    '<a value="blank'+counter+'_blabel'+counter+'" class="btn btn-sm text-danger delete-input" ><i class="fas fa-trash"></i></a> '+
                    '<input type="text"  name="blank[]" placeholder="Blank..."   id="blank'+counter+'" ' +
                    '   class="form-control mb-3">\n' 
                );
            }
            $('#fill_in_the_blank').append(data);
            ele.push('blank');
            counter++;
        });

        //$('.delete-input').click(function(e) {  
        $(document).on('click', '.delete-input', function(e) {
         //   console.log(' delete input ',  $(this).attr('value'), e);
            let value = $(this).attr('value');
            let ids = value.split("_");        
            let currentId =  "#" + ids[0];
            let labelId = "#"+ids[1];
            let locOfDeletedEle = ele.indexOf(currentId);
            if(locOfDeletedEle > -1) ele.splice(locOfDeletedEle, 1);
            $(currentId).remove(); 
            $(labelId).remove();
            $(this).remove();
        });

        //$('.add-multiple-answers').on("click", function(e) {
        $(document).on('click', '.add-multiple-answers', function(e) {
            let values = $(this).attr('value');
            let temp = values.split("_");
            let data = [];
                
            let nextIdx = parseInt(temp[1]) + 1;
            let nextAlphabet = alphabets[nextIdx];
            let currentIdx = parseInt(temp[1]) - 1;
            data.push(
                '<div class="form-group multiple-group-'+temp[1]+'">'+
                    '<label for="answer_'+temp[0]+'">'+temp[0]+'&nbsp;'+
                    '<input type="checkbox" name="right_answer[]" value="'+temp[0]+'" >&nbsp;'+
                    '<a class="btn btn-outline-danger btn-sm mb-2 add-multiple-answers" id="add-multiple-answer_'+temp[1]+'" '+
                        ' value="'+nextAlphabet+'_'+nextIdx+'"> '+
                            '<span class="required"><i class="fas fa-plus"></i></span>'+
                    '</a>'+ 
                    '<textarea id="answer_'+temp[0]+'" class="form-control summernote "'+
                    '    name="answer_'+temp[0]+'"></textarea>'+                
                '</div>' 
            );  
            $('#multiple_choice').append(data);
           // var div = $(data).appendTo($("#multiple_choice"));
           // div.summernote();
           let textAreaId = "#answer_"+temp[0];
           $(textAreaId).summernote();
           let eleId = "add-multiple-answer_"+currentIdx;
           let currentEle = document.getElementById(eleId); //console.log(currentEle);
           currentEle.remove();
           //$(textAreaId).disabled(true);
           //e.parentNode.removeChild(e);
        });

        $(document).on('click', '.add-rearrange-answers', function(e) {
            let currentIdx = $(this).attr('value'); 
            let data = [];
                
            let nextIdx = parseInt(currentIdx) + 1; //parseInt(temp[1]) + 1;
            let currentNum = numbers[parseInt(currentIdx)-1];
            let nextNum = numbers[currentIdx]; // coz array starts from zero
            
            data.push(
                '<div class="form-group rearrange-'+nextIdx+'">'+
                    '<label for="answer_'+nextNum+'">'+nextIdx+'&nbsp;'+
                    '<a class="btn btn-outline-danger btn-sm mb-2 add-rearrange-answers" id="add-rerrange-answer_'+nextIdx+'" value="'+nextIdx+'"> '+
                        '<span class="required"><i class="fas fa-plus"></i></span> '+
                    '</a> '+
                    '<textarea id="answer_'+nextNum+'" class="form-control summernote "'+
                    '    name="answer_'+nextNum+'"></textarea>'+                
                '</div>' 
            );  
            $('#rearrange').append(data);
          
           let textAreaId = "#answer_"+nextNum;
           $(textAreaId).summernote();
           let eleId = "add-rerrange-answer_"+currentIdx;
           let currentPlus = document.getElementById(eleId); //console.log(currentEle);
           currentPlus.remove(); 
        });

    });
//});
</script>

  
@endpush


