<!-- Fill BLank Start-->
<div>
    <div class="form-group">
       <textarea  v-validate="'required'" name="blank_answer" placeholder="Answer..."   id="blank_answer" 
       class="form-control{{ $errors->has('blank_answer') ? ' is-invalid' : '' }}">{{old('blank_answer', isset($post->blank_answer->answer) ? $post->blank_answer->answer: '')}}</textarea>
       {!! $errors->first('blank_answer', '<div class="invalid-feedback">:message</div>') !!}
       <div v-show="errors.has('blank_answer')" class="invalid-feedback">@{{ errors.first('blank_answer') }}</div>
   </div>
   <div class="form-group">
        <label>@lang('Paragrph')&nbsp;<span class="required">*</span></label>
        <div class="btn-group p-1">
            <button type="button" class="btn btn-default">
                <span class="required"><i class="fas fa-plus"></i></span>
            </button>
            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                 <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu" style="">
                <a class="dropdown-item" id="add-text-area">@lang('Text/Sentence')</a>
                <a class="dropdown-item" id="add-blank-area">@lang('Blank Value')</a>      
            </div>                                      
        </div>                                                          
    </div>
        
        
    <div id="fill_in_the_blank" class="form-group">
        <input type="hidden" name="paragraph" />
        @if(isset($post->blank_answer->paragraph))
            @foreach($post->blank_answer->paragraph as $idx => $data)
                @foreach($data as $key => $val)
                    @if(strpos($key,  'sentence') > -1) 
                        <label id="slabel{{$idx}}">@lang('Sentence')@if($idx <= 1) &nbsp;<span class="required">*</span>@endif</label> 
                            @if($idx > 1) 
                                <a class="btn btn-sm text-danger delete-input" value="sentence{{$idx}}_slabel{{$idx}}"><i class="fas fa-trash"></i></a> 
                                <textarea name="sentence[]" placeholder="Sentence..." id="sentence{{$idx}}" class="form-control mb-3">{{$val}}</textarea>
                            @else 
                                <textarea name="sentence[]" v-validate="'required'" placeholder="Sentence..." id="sentence{{$idx}}" class="form-control mb-3">{{$val}}</textarea>
                                {!! $errors->first('sentence[]', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('sentence[]')" class="invalid-feedback">@{{ errors.first('sentence[]') }}</div>
                            @endif
                        
                    @endif
                    @if(strpos($key,  'blank') > -1)
                        <label id="blabel{{$idx}}">@lang('Blank')@if($idx <= 1) &nbsp;<span class="required">*</span>@endif</label>
                            @if($idx > 1) 
                                <a class="btn btn-sm text-danger delete-input" value="blank{{$idx}}_blabel{{$idx}}"><i class="fas fa-trash"></i></a> 
                                <input type="text" name="blank[]" placeholder="Blank..." id="blank{{$idx}}" class="form-control mb-3" value="{{$val}}">
                            @else 
                                <input type="text" v-validate="'required'" name="blank[]" placeholder="Blank..." id="blank{{$idx}}" class="form-control mb-3" value="{{$val}}">
                                {!! $errors->first('blank[]', '<div class="invalid-feedback">:message</div>') !!} 
                                <div v-show="errors.has('blank[]')" class="invalid-feedback">@{{ errors.first('blank[]') }}</div>
                            @endif                       
                    @endif
                @endforeach
            @endforeach
        @else 
            
            {{-- 
                <label id="slabel1">@lang('Sentence')</label>                           
            <textarea name="sentence[]" v-validate="'required'" placeholder="Sentence..." id="sentence1" class="form-control mb-3"></textarea>
            {!! $errors->first('sentence[]', '<div class="invalid-feedback">:message</div>') !!}
            <div v-show="errors.has('sentence[]')" class="invalid-feedback">@{{ errors.first('sentence[]') }}</div> 
            --}}
        @endif      
        @if($errors->any())
            @php foreach($errors->all() as $err) {
                    echo "<div class='required'>". $err ."</div>";
                }
            @endphp
            {{-- <div class="required">{{ implode('', $errors->all(':message')) }} </div> --}}          
        @endif
    </div>
    <div class="form-group">
        <label>@lang('Optional Keywords For Blanks (separated by comma)')</label>
        <textarea  id="optional_keywords" name="optional_keywords" 
                class="form-control {{ $errors->has('optional_keywords') ? ' is-invalid' : '' }}"
                placeholder="Keywords ...">{{old('optional_keywords', isset($post->blank_answer->optional_keywords) ? $post->blank_answer->optional_keywords: '')}}</textarea>
        {!! $errors->first('optional_keywords', '<div class="invalid-feedback">:message</div>') !!}
        <div v-show="errors.has('optional_keywords')" class="invalid-feedback">@{{ errors.first('optional_keywords') }}</div>
    </div>
</div>
<!-- Fill BLank End-->