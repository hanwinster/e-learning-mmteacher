@php
    $alphabets = ['A','B','C','D','E','F','G','H','I','J'];
@endphp
@foreach($alphabets as $idx => $alphabet)
<div class="row" id="matching-form">
    <div class="col-md-6">
        <div class="form-group">
            <label for="answer_{{$alphabet}}">{{$alphabet}}&nbsp;
                @if($idx < 2) 
                    <span class="required">*</span>
                @endif
            </label>         
            @if($idx < 2) 
                <textarea v-validate="'required'" id="answer_{{$alphabet}}" class="form-control summernote 
                    {{ $errors->has('answers[$idx]') ? ' is-invalid' : '' }}" 
                    name="answers[{{$idx}}]">{{ old('answers[$idx]', 
                                        isset($post->answers[$idx]) ? $post->answers[$idx]: '') }}
                </textarea>
            @else
                <textarea id="answer_{{$alphabet}}" class="form-control summernote 
                    {{ $errors->has('answers[$idx]') ? ' is-invalid' : '' }}" 
                    name="answers[{{$idx}}]">{{ old('answers[$idx]', 
                                        isset($post->answers[$idx]) ? $post->answers[$idx]: '') }}
                </textarea>
            @endif
                {!! $errors->first('answers[{{$idx}}]', '<div class="invalid-feedback">:message</div>') !!}
                <div v-show="errors.has('answers[{{$idx}}]')" class="invalid-feedback">
                    @{{ errors.first('answers[$idx]') }}
                </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="answer_{{$alphabet}}">{{$idx+1}}&nbsp;
                @if($idx < 2) 
                    <span class="required">*</span>
                @endif
            </label>         
            @if($idx < 2) 
                <textarea v-validate="'required'" rows="5" id="right_answers_{{$alphabet}}" class="form-control 
                    {{ $errors->has('right_answers[$idx]') ? ' is-invalid' : '' }}" 
                    name="right_answers[{{$idx}}]">{{ old('answers[$idx]', 
                                isset($post->right_answers[$idx]) ? $post->right_answers[$idx]: '') }}
                </textarea>
            @else
                <textarea id="right_answers_{{$alphabet}}" rows="5" class="form-control  
                    {{ $errors->has('right_answers[$idx]') ? ' is-invalid' : '' }}" 
                    name="right_answers[{{$idx}}]">{{ old('right_answers[$idx]', 
                                isset($post->right_answers[$idx]) ? $post->right_answers[$idx]: '') }}
                </textarea>
            @endif
                {!! $errors->first('right_answers[{{$idx}}]', '<div class="invalid-feedback">:message</div>') !!}
                <div v-show="errors.has('right_answers[$idx]')" class="invalid-feedback">
                    @{{ errors.first('right_answers[$idx]') }}
                </div>
        </div>
    </div>
</div>
@endforeach