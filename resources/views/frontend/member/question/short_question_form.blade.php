<!-- Short Question Start-->
    <div  id="short_question">
        <div class="form-group">
            <textarea  v-validate="'required'" name="short_answer" placeholder="Answer..."   id="short_answer" class="form-control{{ $errors->has('short_answer') ? ' is-invalid' : '' }}">{{old('short_answer', isset($post->short_answer->answer) ? $post->short_answer->answer: '')}}</textarea>
           {!! $errors->first('short_answer', '<div class="invalid-feedback">:message</div>') !!}
           <div v-show="errors.has('short_answer')" class="invalid-feedback">@{{ errors.first('short_answer') }}</div>
        </div>
    </div>
    <!-- Short Question End-->