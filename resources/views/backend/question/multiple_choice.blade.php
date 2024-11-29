<!-- Multiple Choice Section Start-->
  <div  id="multiple_choice" class="d-none">
      {{--@if(isset($post->multiple_answers))
          @php
              $char = 'A';
          @endphp
          @forelse($post->multiple_answers as $key => $multiple_answer)
              @php
                  $name = "answer_".$char;
              @endphp
              <div class="form-group">
                  <label for="{{$name}}" class="require">@lang($char)</label>
                  <input type="radio" name="right_answer" value="{{$key}}" {{$multiple_answer->is_right_answer ? 'checked' : '' }}>
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="{{$name}}" id="{{$name}}"
                      class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
                      value="{{ old($name, $multiple_answer->answer) }}">
                  {!! $errors->first('$name', '<div class="invalid-feedback">:message</div>') !!}
                  <div v-show="errors.has('$name')" class="invalid-feedback">@{{ errors.first('$name') }}</div>
                  <input type="hidden" name="multiple_answer_array[]" value="{{$char}}">
              </div>
              @php
                  $char++;
                  $label = "answer_".$char;
              @endphp
          @empty
          @endforelse
      @else
          @php
              $char = 'A';
          @endphp
          @for ($i = 0; $i <= 4; $i++)
              @php
                  $name = "answer_".$char;
              @endphp
              <div class="form-group">
                  <label for="{{$name}}" class="require">@lang($char)</label>
                  <input type="radio" name="right_answer" value="{{$i}}">
                  <input v-validate="'required|max:255'" type="text" placeholder="" name="{{$name}}" id="{{$name}}"
                      class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
                      value="{{ old($name) }}">
                  {!! $errors->first($name, '<div class="invalid-feedback">:message</div>') !!}
                  <div v-show="errors.has('$name.')" class="invalid-feedback">@{{ errors.first('$name') }}</div>
                  <input type="hidden" name="multiple_answer_array[]" value="{{$char}}">
              </div>
              @php
                  $char++;
                  $label = "answer_".$char;
              @endphp
          @endfor 
      @endif --}}
      
     <div class="form-group">
             <label for="answer_A" class="require">@lang('A')</label>
             <input type="radio" name="right_answer" value="A" {{(isset($post->multiple_choice->right_answer) && $post->multiple_choice->right_answer == $post->multiple_choice->answer_a ? 'checked' : '' )}}>
             <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_A" id="answer_A"
                 class="form-control{{ $errors->has('answer_A') ? ' is-invalid' : '' }}"
                 value="{{ old('answer_A', isset($post->multiple_choice->answer_a) ? $post->multiple_choice->answer_a: '') }}">
             {!! $errors->first('right_answer', '<div class="invalid-feedback">:message</div>') !!}
             {!! $errors->first('answer_A', '<div class="invalid-feedback">:message</div>') !!}
             <div v-show="errors.has('answer_A')" class="invalid-feedback">@{{ errors.first('answer_A') }}</div>
         </div>
         <div class="form-group">
             <label for="answer_B" class="require">@lang('B')</label>
             <input type="radio" name="right_answer" value="B" {{(isset($post->multiple_choice) && $post->multiple_choice->right_answer == $post->multiple_choice->answer_b ? 'checked' : '' )}}>
             <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_B" id="answer_B"
                 class="form-control{{ $errors->has('answer_B') ? ' is-invalid' : '' }}"
                 value="{{ old('answer_B', isset($post->multiple_choice->answer_b) ? $post->multiple_choice->answer_b: '') }}">
             {!! $errors->first('answer_B', '<div class="invalid-feedback">:message</div>') !!}
             <div v-show="errors.has('answer_B')" class="invalid-feedback">@{{ errors.first('answer_B') }}</div>
         </div>
         <div class="form-group">
             <label for="answer_C" class="require">@lang('C') </label>
             <input type="radio" name="right_answer" value="C" {{(isset($post->multiple_choice) && $post->multiple_choice->right_answer == $post->multiple_choice->answer_c ? 'checked' : '' )}}>
             <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_C" id="answer_C"
                 class="form-control{{ $errors->has('answer_C') ? ' is-invalid' : '' }}"
                 value="{{ old('answer_C', isset($post->multiple_choice->answer_c) ? $post->multiple_choice->answer_c: '') }}">
             {!! $errors->first('answer_C', '<div class="invalid-feedback">:message</div>') !!}
             <div v-show="errors.has('answer_C')" class="invalid-feedback">@{{ errors.first('answer_C') }}</div>
         </div>
         <div class="form-group">
             <label for="answer_D" class="require">@lang('D')  </label>
             <input type="radio" name="right_answer" value="D" {{(isset($post->multiple_choice) && $post->multiple_choice->right_answer == $post->multiple_choice->answer_d ? 'checked' : '' )}}>
             <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_D" id="answer_D"
                 class="form-control{{ $errors->has('answer_D') ? ' is-invalid' : '' }}"
                 value="{{ old('answer_D', isset($post->multiple_choice->answer_d) ? $post->multiple_choice->answer_d: '') }}">
             {!! $errors->first('answer_D', '<div class="invalid-feedback">:message</div>') !!}
             <div v-show="errors.has('answer_D')" class="invalid-feedback">@{{ errors.first('answer_D') }}</div>
         </div>
         <div class="form-group">
             <label for="answer_E" class="require">@lang('E')</label>
             <input type="radio" name="right_answer" value="E" {{(isset($post->multiple_choice) && $post->multiple_choice->right_answer == $post->multiple_choice->answer_e ? 'checked' : '' )}}>
             <input v-validate="'required|max:255'" type="text" placeholder="" name="answer_E" id="answer_E"
                 class="form-control{{ $errors->has('answer_E') ? ' is-invalid' : '' }}"
                 value="{{ old('answer_E', isset($post->multiple_choice->answer_e) ? $post->multiple_choice->answer_e: '') }}">
             {!! $errors->first('answer_E', '<div class="invalid-feedback">:message</div>') !!}
             <div v-show="errors.has('answer_E')" class="invalid-feedback">@{{ errors.first('answer_E') }}</div>
         </div>                                
  </div>
  <!-- Multiple Choice Section End-->