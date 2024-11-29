<!-- Multiple Choice Section Start-->
  <div  id="multiple_choice">
    
     @if(isset($post))
         @php
            $multi_answers = $post->multiple_answers->toArray();           
         @endphp
            @foreach($multi_answers as $idx => $ma)
                @if( array_key_exists($idx, $multi_answers) && $multi_answers[$idx]['name'] == $alphabets[$idx] )  
                    <div class="form-group multiple-group-{{$idx}}">
                        <label for="answer_{{$alphabets[$idx]}}">{{$alphabets[$idx]}})&nbsp;
                        @if($idx < 2)    <span class="required"> * </span></label> @endif
                        <input type="checkbox" name="right_answer[]" value="{{$alphabets[$idx]}}" 
                            {{ (isset($multi_answers[$idx]['is_right_answer']) && $multi_answers[$idx]['is_right_answer'] == true  ? 'checked' : '' ) }} >
                        @if($idx === count($multi_answers)-1)
                            <a class="btn btn-outline-danger btn-sm mb-2 add-multiple-answers" id="add-multiple-answer_{{$idx}}"
                                 value="{{ $alphabets[$idx + 1] }}_{{ $idx + 1 }}">
                                <span class="required"><i class="fas fa-plus"></i></span>
                            </a>
                        @endif
                        <textarea  v-validate="'required'" id="answer_{{$alphabets[$idx]}}" 
                            class="form-control summernote {{ $errors->has('answer_$alphabets[$idx]') ? ' is-invalid' : '' }}"
                            name="answer_{{$alphabets[$idx]}}">{{ old('answer_$alphabets[$idx]', isset($multi_answers[$idx]['answer']) ?
                             $multi_answers[$idx]['answer']: '') }}</textarea>
                        {!! $errors->first('answer_{{$alphabets[$idx]}}', '<div class="invalid-feedback">:message</div>') !!}
                        <div v-show="errors.has('answer_{{$alphabets[$idx]}}')" class="invalid-feedback">@{{ errors.first('answer_$alphabets[$idx]') }}</div>
                    </div>              
                @endif
            @endforeach
    @else
        @for($i = 0; $i < 3; $i++)
            <div class="form-group multiple-group-{{$i}}">
                @if($i < 2)
                    <label for="answer_{{$alphabets[$i]}}">{{$alphabets[$i]}}&nbsp;<span class="required"> * </span></label>
                    <input type="checkbox" name="right_answer[]" value="{{$alphabets[$i]}}" >                     
                    <textarea  v-validate="'required'" id="answer_{{$alphabets[$i]}}" class="form-control summernote {{ $errors->has('answer_$alphabets[$i]') ? ' is-invalid' : '' }}"
                            name="answer_{{$alphabets[$i]}}">{{ old('answer_$alphabets[$i]') }}</textarea>
                    {!! $errors->first('right_answer', '<div class="invalid-feedback">:message</div>') !!}
                    {!! $errors->first('answer_$alphabets[$i]', '<div class="invalid-feedback">:message</div>') !!}
                    <div v-show="errors.has('answer_{{$alphabets[$i]}}')" class="invalid-feedback">@{{ errors.first('answer_$alphabets[$i]') }}</div>
                @else 
                    <label for="answer_{{$alphabets[$i]}}">{{$alphabets[$i]}}</label>
                    <input type="checkbox" name="right_answer[]" value="{{$alphabets[$i]}}" >
                    <a class="btn btn-outline-danger btn-sm mb-2 add-multiple-answers" id="add-multiple-answer_{{$i}}"
                            value="{{ $alphabets[3] }}_{{ 3 }}">
                        <span class="required"><i class="fas fa-plus"></i></span>
                    </a>          
                    <textarea  id="answer_{{$alphabets[$i]}}" class="form-control summernote {{ $errors->has('answer_$alphabets[$i]') ? ' is-invalid' : '' }}"
                            name="answer_{{$alphabets[$i]}}">{{ old('answer_$alphabets[$i]') }}</textarea>
                    {!! $errors->first('right_answer', '<div class="invalid-feedback">:message</div>') !!}
                    {!! $errors->first('answer_$alphabets[$i]', '<div class="invalid-feedback">:message</div>') !!}
                    <div v-show="errors.has('answer_{{$alphabets[$i]}}')" class="invalid-feedback">@{{ errors.first('answer_$alphabets[$i]') }}</div>     
                @endif
                
            </div>
        @endfor
         
    @endif
  </div>

  <!-- Multiple Choice Section End