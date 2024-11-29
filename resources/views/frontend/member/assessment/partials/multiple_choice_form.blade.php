@php
    $alphabets = ['A','B','C','D','E','F','G','H','I','J'];
@endphp
@foreach($alphabets as $idx => $alphabet)
<div class="row" id="mc-form">
    <div class="col-md-12">
<div class="form-group">
    <label for="answer_{{$alphabet}}">{{$alphabet}}&nbsp;
        @if($idx < 2) <span class="required">*</span>
            @endif
    </label>
    <input type="checkbox" name="right_answers[]" value="{{$alphabet}}" 
        {{ ( isset($post->right_answers) && in_array($alphabet, $post->right_answers)  ? 'checked' : '' ) }}>&nbsp;
        {!! $errors->first('right_answers[{{$idx}}]', '<div class="invalid-feedback">:message</div>') !!}
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
        <div v-show="errors.has('answer_{{$alphabet}}')" class="invalid-feedback">
            @{{ errors.first('answers[$idx]') }}
        </div>
</div>
</div>
</div>
@endforeach