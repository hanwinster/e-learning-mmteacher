@php
    $alphabets = ['A','B','C','D','E','F','G','H','I','J'];
@endphp
@foreach($alphabets as $idx => $alphabet)
<div class="row" id="tf-form">
    <div class="col-md-6">
    <div class="form-group">
        <label for="answer_{{$alphabet}}">{{$alphabet}}&nbsp;
            @if($idx < 2) 
                <span class="required">*</span>
            @endif
        </label>
        <input type="hidden" name="right_answers[{{$idx}}]" value="{{$idx}}"/>&nbsp;
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
            {!! $errors->first('right_answers[{{$idx}}]', '<div class="invalid-feedback">:message</div>') !!}
            {!! $errors->first('answers[{{$idx}}]', '<div class="invalid-feedback">:message</div>') !!}
            <div v-show="errors.has('answer_{{$alphabet}}')" class="invalid-feedback">
                @{{ errors.first('answers[$idx]') }}
            </div>
    </div>
    </div>
    </div>
@endforeach