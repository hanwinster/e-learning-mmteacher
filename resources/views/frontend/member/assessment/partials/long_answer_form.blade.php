<!-- Long Answer Section Start -->
@php //echo 'post is '.isset($post->right_answers[0]); exit; @endphp
    <div class="form-group">
        <textarea  v-validate="'required'" name="right_answers[0]" placeholder="Answer..."   id="long_answer" 
            class="form-control summernote {{ $errors->has('right_answers[0]') ? ' is-invalid' : '' }}">{{ old('right_answers[0]', isset($post->right_answers[0]) ? 
                $post->right_answers[0] : '')}}</textarea>
           {!! $errors->first('right_answers[0]', '<div class="invalid-feedback">:message</div>') !!}
        <div v-show="errors.has('right_answers[0]')" class="invalid-feedback">@{{ errors.first('right_answers[0]') }}</div>
    </div>

    <div class="form-group">
            <label>
                {{ __("Completion/Marking as 'Completed' Option") }}&nbsp;<span class="required">*</span>
            </label>
            <div class="form-check">
                {{ Form::radio('passing_option', \App\Models\LongAnswer::PASSING_OPTION_1, ( isset($post->passing_option) &&
                        $post->passing_option == \App\Models\LongAnswer::PASSING_OPTION_1 ? true : false ), 
                        ['id' => 'passing_option', 'class' => 'form-check-input', 'v-validate' => "'required'" ]) }}
                <label for="passing_option" class="form-check-label">{{ __('After providing answer by course taker') }}</label>
            </div>
            <div class="form-check">
                {{ Form::radio('passing_option', \App\Models\LongAnswer::PASSING_OPTION_2, ( isset($post->passing_option) &&
                        $post->passing_option == \App\Models\LongAnswer::PASSING_OPTION_2 ? true : false ), 
                        ['id' => 'passing_option', 'class' => 'form-check-input', 'v-validate' => "'required'" ]) }}
                <label for="passing_option" class="form-check-label">{{ __('After sending feedback by course owner') }}</label>
            </div>
            <div class="form-check">
                {{ Form::radio('passing_option', \App\Models\LongAnswer::PASSING_OPTION_3, ( isset($post->passing_option) &&
                        $post->passing_option == \App\Models\LongAnswer::PASSING_OPTION_3 ? true : false ), 
                        ['id' => 'passing_option', 'class' => 'form-check-input', 'v-validate' => "'required'" ]) }}
                <label for="passing_option" class="form-check-label">{{ __("After setting 'Pass' by course owner") }}</label>
            </div>
            {!! $errors->first('passing_option', '<p class="help-block">:message</p>') !!}        
            <div v-show="errors.has('passing_option')" class="invalid-feedback">@{{ errors.first('passing_option') }}</div>  
    </div>
    
    <input type="hidden" name="answers[0]" value="false" />
<!-- Long Answer Section End -->