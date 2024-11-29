 <!-- Matching Section Start -->
  <div id="matching">
    @if(isset($post))
      @php
        $match_answer_array = $post->matching_answer->answer;
      @endphp
        @if(array_key_exists(0, $match_answer_array) && $match_answer_array[0]['name_first'] == 'A')    
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_A">@lang('A')&nbsp;<span class="required">*</span></label>
                    <textarea v-validate="'required'"   name="matching_A" id="matching_A"
                       class="form-control summernote {{ $errors->has('matching_A') ? ' is-invalid' : '' }}"
                       >{{ old('matching_A', isset($match_answer_array[0]['first']) ? $match_answer_array[0]['first']: '') }}</textarea>
                   {!! $errors->first('matching_A', '<div class="invalid-feedback">:message</div>') !!}
                   <div v-show="errors.has('matching_A')" class="invalid-feedback">@{{ errors.first('matching_A') }}</div>
               </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="matching_One">@lang('1')&nbsp;<span class="required">*</span></label>
                    <textarea v-validate="'required'"  name="matching_One" id="matching_One"
                       class="form-control {{ $errors->has('matching_One') ? ' is-invalid' : '' }}"
                       >{{ old('matching_One', isset($match_answer_array[0]['second']) ? strip_tags($match_answer_array[0]['second']): '') }}</textarea>
                   {!! $errors->first('matching_One', '<div class="invalid-feedback">:message</div>') !!}
                   <div v-show="errors.has('matching_One')" class="invalid-feedback">@{{ errors.first('matching_One') }}</div>
               </div>
            </div>
          </div>
        @endif

        @if(array_key_exists(1, $match_answer_array) && $match_answer_array[1]['name_first'] == 'B')    
          <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matching_B">@lang('B')&nbsp;<span class="required">*</span></label>
                        <textarea v-validate="'required'"   name="matching_B" id="matching_B"
                           class="form-control summernote {{ $errors->has('matching_B') ? ' is-invalid' : '' }}"
                           >{{ old('matching_B', isset($match_answer_array[1]['first']) ? $match_answer_array[1]['first']: '') }}</textarea>
                       {!! $errors->first('matching_B', '<div class="invalid-feedback">:message</div>') !!}
                       <div v-show="errors.has('matching_B')" class="invalid-feedback">@{{ errors.first('matching_B') }}</div>
                   </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matching_Two">@lang('2')&nbsp;<span class="required">*</span></label>
                        <textarea v-validate="'required'"   name="matching_Two" id="matching_Two"
                           class="form-control {{ $errors->has('matching_Two') ? ' is-invalid' : '' }}"
                           >{{ old('matching_Two', isset($match_answer_array[1]['second']) ? strip_tags($match_answer_array[1]['second']): '') }}</textarea>
                       {!! $errors->first('matching_Two', '<div class="invalid-feedback">:message</div>') !!}
                       <div v-show="errors.has('matching_Two')" class="invalid-feedback">@{{ errors.first('matching_Two') }}</div>  
                   </div>
                </div>
            </div>
        @endif

        @if(array_key_exists(2, $match_answer_array) && $match_answer_array[2]['name_first'] == 'C')    
          <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matching_C">@lang('C')</label>
                        <textarea  name="matching_C" id="matching_C"
                           class="form-control summernote {{ $errors->has('matching_C') ? ' is-invalid' : '' }}"
                           >{{ old('matching_C', isset($match_answer_array[2]['first']) ? $match_answer_array[2]['first']: '') }}</textarea>
                       {!! $errors->first('matching_C', '<div class="invalid-feedback">:message</div>') !!}
                       <div v-show="errors.has('matching_C')" class="invalid-feedback">@{{ errors.first('matching_C') }}</div>
                   </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matching_Three">@lang('3')</label>
                        <textarea name="matching_Three" id="matching_Three"
                           class="form-control {{ $errors->has('matching_Three') ? ' is-invalid' : '' }}"
                           >{{ old('matching_Three', isset($match_answer_array[2]['second']) ? strip_tags($match_answer_array[2]['second']): '') }}</textarea>
                       {!! $errors->first('matching_Three', '<div class="invalid-feedback">:message</div>') !!}
                       <div v-show="errors.has('matching_Three')" class="invalid-feedback">@{{ errors.first('matching_Three') }}</div>
                   </div>
                </div>
            </div>
        @endif

        @if(array_key_exists(3, $match_answer_array) && $match_answer_array[3]['name_first'] == 'D')    
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_D" class="">@lang('D')</label><span class="ml-1"> * </span>
                    <textarea  name="matching_D" id="matching_D"
                       class="form-control summernote "
                       >{{ old('matching_D', isset($match_answer_array[3]['first']) ? $match_answer_array[3]['first']: '') }}</textarea>
               </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_Four" class="">@lang('4')</label> <span class="ml-1"> * </span>
                    <textarea  name="matching_Four" id="matching_Four"
                       class="form-control "
                       >{{ old('matching_Four', isset($match_answer_array[3]['second']) ? strip_tags($match_answer_array[3]['second']) : '') }}</textarea>
               </div>
            </div>
          </div>
        @else
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_D" class="">@lang('D')</label><span class="ml-1"> * </span>
                    <textarea  name="matching_D" id="matching_D"
                       class="form-control summernote "
                       >{{ old('matching_D') }}</textarea>
               </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_Four" class="">@lang('4')</label> <span class="ml-1"> * </span>
                    <textarea  name="matching_Four" id="matching_Four"
                       class="form-control"
                       >{{ old('matching_Four') }}</textarea>
               </div>
            </div>
          </div>
        @endif

        @if(array_key_exists(4, $match_answer_array) && $match_answer_array[4]['name_first'] == 'E')    
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_E" class="">@lang('E')</label><span class="ml-1"> * </span>
                    <textarea  name="matching_E" id="matching_E"
                       class="form-control summernote " >{{ old('matching_E', isset($match_answer_array[4]['first']) ?
                         $match_answer_array[4]['first']: '') }}</textarea>
               </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_Five" class="">@lang('5')</label><span class="ml-1"> * </span>
                    <textarea  name="matching_Five" id="matching_Five"
                       class="form-control " >{{ old('matching_Five', isset($match_answer_array[4]['second']) ?
                        strip_tags($match_answer_array[4]['second']): '') }}</textarea>
               </div>
            </div>
          </div>
        @else
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_E" class="">@lang('E')</label><span class="ml-1"> * </span>
                    <textarea  name="matching_E" id="matching_E"
                       class="form-control summernote " >{{ old('matching_E') }}</textarea>
               </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="matching_Five" class="">@lang('5')</label><span class="ml-1"> * </span>
                    <textarea  name="matching_Five" id="matching_Five"
                       class="form-control " >{{ old('matching_Five') }}</textarea>
               </div>
            </div>
          </div>
        @endif

    @else
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_A">@lang('A')&nbsp;<span class="required">*</span></label>
                  <textarea v-validate="'required'" name="matching_A" id="matching_A"
                     class="form-control summernote {{ $errors->has('matching_A') ? ' is-invalid' : '' }}"
                     >{{ old('matching_A') }}</textarea>
                 {!! $errors->first('matching_A', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_A')" class="invalid-feedback">@{{ errors.first('matching_A') }}</div>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_One">@lang('1')&nbsp;<span class="required">*</span></label>
                  <textarea v-validate="'required'" name="matching_One" id="matching_One"
                     class="form-control {{ $errors->has('matching_One') ? ' is-invalid' : '' }}"
                     >{{ old('matching_One') }}</textarea>
                 {!! $errors->first('matching_One', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_One')" class="invalid-feedback">@{{ errors.first('matching_One') }}</div>
             </div>
          </div>
      </div>  
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_B">@lang('B')&nbsp;<span class="required">*</span></label>
                  <textarea v-validate="'required'" name="matching_B" id="matching_B"
                     class="form-control summernote {{ $errors->has('matching_B') ? ' is-invalid' : '' }}"
                     >{{ old('matching_B') }}</textarea>
                 {!! $errors->first('matching_B', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_B')" class="invalid-feedback">@{{ errors.first('matching_B') }}</div>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_Two">@lang('2')&nbsp;<span class="required">*</span></label>
                  <textarea v-validate="'required'" name="matching_Two" id="matching_Two"
                     class="form-control {{ $errors->has('matching_Two') ? ' is-invalid' : '' }}"
                     >{{ old('matching_Two') }}</textarea>
                 {!! $errors->first('matching_Two', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_Two')" class="invalid-feedback">@{{ errors.first('matching_Two') }}</div>  
             </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_C">@lang('C')</label>
                  <textarea name="matching_C" id="matching_C"
                     class="form-control summernote {{ $errors->has('matching_C') ? ' is-invalid' : '' }}"
                     >{{ old('matching_C') }}</textarea>
                 {!! $errors->first('matching_C', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_C')" class="invalid-feedback">@{{ errors.first('matching_C') }}</div>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_Three">@lang('3')</label>
                  <textarea  name="matching_Three" id="matching_Three"
                     class="form-control {{ $errors->has('matching_Three') ? ' is-invalid' : '' }}"
                     >{{ old('matching_Three') }}</textarea>
                 {!! $errors->first('matching_Three', '<div class="invalid-feedback">:message</div>') !!}
                 <div v-show="errors.has('matching_Three')" class="invalid-feedback">@{{ errors.first('matching_Three') }}</div>
             </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_D">@lang('D')</label><span class="ml-1"> * </span>
                  <textarea  name="matching_D" id="matching_D"
                     class="form-control summernote " >{{ old('matching_D') }}</textarea>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_Four">@lang('4')</label><span class="ml-1"> * </span>
                  <textarea  name="matching_Four" id="matching_Four"
                     class="form-control " >{{ old('matching_Four') }}</textarea>
             </div>
          </div>
      </div>
      <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_E" class="">@lang('E')</label><span class="ml-1"> * </span>
                  <textarea  name="matching_E" id="matching_E"
                     class="form-control summernote " >{{ old('matching_E') }}</textarea>
             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                  <label for="matching_Five" class="">@lang('5')</label><span class="ml-1"> * </span>
                  <textarea  name="matching_Five" id="matching_Five"
                     class="form-control " >{{ old('matching_Five') }}</textarea>
             </div>
          </div>
      </div>
    @endif
 </div>
 <!-- Matching Section end