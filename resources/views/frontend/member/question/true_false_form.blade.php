<!-- True/False Section Start -->
<div  id="true_or_false">
    <div class="form-group">
        <input type="radio" name="true_or_false" id="answer_true" value="true" {{(isset($post->true_false_answer->answer) && $post->true_false_answer->answer == 'true' ? 'checked' : '' )}}>
        <label for="answer_true" >@lang('True')</label>
    </div>

    <div class="form-group">
        <input type="radio" name="true_or_false" id="answer_false" value="false" {{(isset($post->true_false_answer->answer) && $post->true_false_answer->answer == 'false' ? 'checked' : '' )}}>
        <label for="answer_false" >@lang('False')</label>
    </div>
    <div v-show="errors.has('true_or_false')" class="invalid-feedback">@{{ errors.first('true_or_false') }}</div>
</div>

<!-- True/False Section End -->