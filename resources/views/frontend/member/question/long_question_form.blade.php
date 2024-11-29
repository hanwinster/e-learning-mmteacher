<!-- Long Question Start-->
    <div  id="long_question">
        <div class="form-group">
            <textarea  v-validate="'required'" name="long_answer" placeholder="Answer..."   id="long_answer" 
            class="form-control summernote {{ $errors->has('long_answer') ? ' is-invalid' : '' }}">{{ old('answer', isset($post->long_answer->answer) ? 
                $post->long_answer->answer: '')}}</textarea>
           {!! $errors->first('long_answer', '<div class="invalid-feedback">:message</div>') !!}
           <div v-show="errors.has('long_answer')" class="invalid-feedback">@{{ errors.first('long_answer') }}</div>
        </div>
        <div class="form-group">
            <label>
                {{ __("Completion/Marking as 'Completed' Option") }}&nbsp;<span class="required">*</span>
            </label>
            <div class="form-check">
                {{ Form::radio('passing_option', \App\Models\LongAnswer::PASSING_OPTION_1, ( isset($post->long_answer->passing_option) &&
                        $post->long_answer->passing_option == \App\Models\LongAnswer::PASSING_OPTION_1 ? true : false ), 
                        ['id' => 'passing_option', 'class' => 'form-check-input', 'v-validate' => "'required'" ]) }}
                <label for="passing_option" class="form-check-label">{{ __('After providing answer by course taker') }}</label>
            </div>
            <div class="form-check">
                {{ Form::radio('passing_option', \App\Models\LongAnswer::PASSING_OPTION_2, ( isset($post->long_answer->passing_option) &&
                        $post->long_answer->passing_option == \App\Models\LongAnswer::PASSING_OPTION_2 ? true : false ), 
                        ['id' => 'passing_option', 'class' => 'form-check-input', 'v-validate' => "'required'" ]) }}
                <label for="passing_option" class="form-check-label">{{ __('After sending feedback by course owner') }}</label>
            </div>
            <div class="form-check">
                {{ Form::radio('passing_option', \App\Models\LongAnswer::PASSING_OPTION_3, ( isset($post->long_answer->passing_option) &&
                        $post->long_answer->passing_option == \App\Models\LongAnswer::PASSING_OPTION_3 ? true : false ), 
                        ['id' => 'passing_option', 'class' => 'form-check-input', 'v-validate' => "'required'" ]) }}
                <label for="passing_option" class="form-check-label">{{ __("After setting 'Pass' by course owner") }}</label>
            </div>
            {!! $errors->first('passing_option', '<p class="help-block">:message</p>') !!}        
            <div v-show="errors.has('passing_option')" class="invalid-feedback">@{{ errors.first('passing_option') }}</div>  
        </div>    
    </div>
<!-- Long Question End-->