<!-- Rearrange Section Start-->
  <div id="rearrange" class="d-none">
      <div class="form-group">
          <label for="answer_one" class="require">@lang('1')</label>
          <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_one" id="answer_one"
             class="form-control{{ $errors->has('answer_one') ? ' is-invalid' : '' }}"
             value="{{ old('answer_one', isset($post->rearrange_answer->answer[0]) ? $post->rearrange_answer->answer[0]: '') }}">
         {!! $errors->first('answer_A', '<div class="invalid-feedback">:message</div>') !!}
         <div v-show="errors.has('answer_one')" class="invalid-feedback">@{{ errors.first('answer_one') }}</div>
     </div>
     <div class="form-group">
         <label for="answer_two" class="require">@lang('2')</label>
         <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_two" id="answer_two"
             class="form-control{{ $errors->has('answer_two') ? ' is-invalid' : '' }}"
             value="{{ old('answer_two', isset($post->rearrange_answer->answer[1]) ? $post->rearrange_answer->answer[1]: '') }}">
         {!! $errors->first('answer_two', '<div class="invalid-feedback">:message</div>') !!}
         <div v-show="errors.has('answer_two')" class="invalid-feedback">@{{ errors.first('answer_two') }}</div>
     </div>
     <div class="form-group">
         <label for="answer_three" class="require">@lang('3') </label>
         <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_three" id="answer_three"
             class="form-control{{ $errors->has('answer_three') ? ' is-invalid' : '' }}"
             value="{{ old('answer_three', isset($post->rearrange_answer->answer[2]) ? $post->rearrange_answer->answer[2]: '') }}">
         {!! $errors->first('answer_three', '<div class="invalid-feedback">:message</div>') !!}
         <div v-show="errors.has('answer_three')" class="invalid-feedback">@{{ errors.first('answer_three') }}</div>
     </div>
     <div class="form-group">
         <label for="answer_four" class="require">@lang('4')  </label>
         <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_four" id="answer_four"
             class="form-control{{ $errors->has('answer_four') ? ' is-invalid' : '' }}"
             value="{{ old('answer_four', isset($post->rearrange_answer->answer[3]) ? $post->rearrange_answer->answer[3]: '') }}">
         {!! $errors->first('answer_four', '<div class="invalid-feedback">:message</div>') !!}
         <div v-show="errors.has('answer_four')" class="invalid-feedback">@{{ errors.first('answer_four') }}</div>
     </div>
     <div class="form-group">
         <label for="answer_five" class="require">@lang('5')</label>
         <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_five" id="answer_five"
             class="form-control{{ $errors->has('answer_five') ? ' is-invalid' : '' }}"
             value="{{ old('answer_five', isset($post->rearrange_answer->answer[4]) ? $post->rearrange_answer->answer[4]: '') }}">
         {!! $errors->first('answer_five', '<div class="invalid-feedback">:message</div>') !!}
         <div v-show="errors.has('answer_five')" class="invalid-feedback">@{{ errors.first('answer_five') }}</div>
      </div>
  </div>
  <!-- Rearrange Section End-->