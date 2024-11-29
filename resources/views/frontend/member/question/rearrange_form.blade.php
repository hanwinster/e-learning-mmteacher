<!-- Rearrange Section Start-->
  <div id="rearrange">
      @if(isset($post))
        @php 
            $rearrangeAnswers = $post->rearrange_answer->answer; //->toArray(); 
            $arrLen = isset($post->rearrange_answer->answer) ? sizeOf($post->rearrange_answer->answer) : 0;
        @endphp
        @foreach($rearrangeAnswers as $idx => $ra) 
       
            <div class="form-group">
                <label for="answer_{{$numbers[$idx]}}" >{{ $idx + 1 }}&nbsp;
                    <span class="required">*</span></label>
                @if($idx == ($arrLen - 1)) 
                    <a class="btn btn-outline-danger btn-sm mb-2 add-rearrange-answers" 
                        id="add-rerrange-answer_{{$idx + 1}}" value="{{$idx + 1}}">
                        <span class="required"><i class="fas fa-plus"></i></span>
                    </a>    
                @endif
                <textarea v-validate="'required'"  name="answer_{{$numbers[$idx]}}" id="answer_{{$numbers[$idx]}}"
                class="form-control summernote {{ $errors->has('answer_'.$numbers[$idx]) ? ' is-invalid' : '' }}"
                >{{ old('answer_'.$numbers[$idx], $ra ) }}</textarea>
                {!! $errors->first('answer_{{$idx}}', '<div class="invalid-feedback">:message</div>') !!}
                <div v-show="errors.has('answer_{{$idx}}')" class="invalid-feedback">{{ $errors->first('answer_'.$idx) }}</div>
            </div>
        @endforeach
      @else 
        <div class="form-group">
                <label for="answer_one" >@lang('1')&nbsp;<span class="required">*</span></label>
                <textarea v-validate="'required'"  name="answer_one" id="answer_one"
                class="form-control summernote {{ $errors->has('answer_one') ? ' is-invalid' : '' }}"
                >{{ old('answer_one', '') }}</textarea>
            {!! $errors->first('answer_one', '<div class="invalid-feedback">:message</div>') !!}
            <div v-show="errors.has('answer_one')" class="invalid-feedback">{{ $errors->first('answer_one') }}</div>
        </div>
        <div class="form-group">
            <label for="answer_two" >@lang('2')&nbsp;<span class="required">*</span></label>
            <textarea v-validate="'required'"  name="answer_two" id="answer_two"
                class="form-control summernote {{ $errors->has('answer_two') ? ' is-invalid' : '' }}"
                >{{ old('answer_two',  '') }}</textarea>
            {!! $errors->first('answer_two', '<div class="invalid-feedback">:message</div>') !!}
            <div v-show="errors.has('answer_two')" class="invalid-feedback">{{ $errors->first('answer_two') }}</div>
        </div>
        <div class="form-group ">
            <label for="answer_three" >@lang('3') </label>
            <a class="btn btn-outline-danger btn-sm mb-2 add-rearrange-answers" 
                id="add-rerrange-answer_3" value="3">
                <span class="required"><i class="fas fa-plus"></i></span>
            </a>    
            <textarea name="answer_three" id="answer_three"
                class="form-control summernote {{ $errors->has('answer_three') ? ' is-invalid' : '' }}"
                >{{ old('answer_three', '') }}</textarea>
            {!! $errors->first('answer_three', '<div class="invalid-feedback">:message</div>') !!}
            <div v-show="errors.has('answer_three')" class="invalid-feedback">{{ $errors->first('answer_three') }}</div>
        </div>
      @endif
      
  </div>
  <!-- Rearrange Section End-->