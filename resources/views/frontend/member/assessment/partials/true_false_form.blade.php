<!-- True/False Section Start -->
@php //echo 'post is '.isset($post->right_answers[0]); exit; @endphp
    <div class="form-group">
        <input type="radio" name="right_answers[0]" id="ranswer_true" value="true" 
            {{(isset($post->right_answers[0]) && $post->right_answers[0] == 'true' ? 'checked' : '' )}}>
        <label for="ranswer_true" >@lang('True')</label>
        
    </div>

    <div class="form-group">
        <input type="radio" name="right_answers[0]" id="ranswer_false" value="false" 
            {{(isset($post->right_answers[0]) && $post->right_answers[0] == 'false' ? 'checked' : '' )}}>
        <label for="ranswer_false" >@lang('False')</label>
        
    </div>
    
    <div class="form-group">
        <input type="radio" name="right_answers[0]" id="ranswer_none" value="none" 
            {{(isset($post->right_answers[0]) && $post->right_answers[0] == 'none' ? 'checked' : '' )}}>
        <label for="ranswer_none" >@lang('None of the above')</label>
        
    </div>
    <input type="hidden" name="answers[0]" value="false" />
    <div v-show="errors.has('true_or_false')" class="invalid-feedback">@{{ errors.first('true_or_false') }}</div>
<!-- True/False Section End -->