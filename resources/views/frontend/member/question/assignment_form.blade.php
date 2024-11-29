<div class="form-group">
    <label for="attached_file">
        {{ __('Attached File') }}
        @if(!isset($post->id)) 
            <span class="required">*</span>
        @endif
    </label>
    @if(isset($post->id))
    {{ Form::file('attached_file',
        ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 
        'v-validate' => "'ext:pdf,ppt,pptx,docx,xlsx,mp3,mp4'"]) }}
        <small>.mp3, .mp4, .ppt, .pptx, .docx, .xlsx and .pdf</small>
        <div style="padding: 10px 0px;">
            @foreach($post->getMedia('assignment_attached_file') as $resource)
                <a href="{{asset($resource->getUrl())}}" class="">
                    <i class="fas fa-file"></i> {{ $resource->file_name }}
                </a>&nbsp;
                <a onclick="return confirm('Are you sure you want to delete?')"
                    href="{{ route('member.media.destroy', $resource->id) }}" class="text-danger">
                    <i class="fas fa-trash"></i> @lang('Remove')
                </a>
                <br/>
            @endforeach
        </div>
    @else
        {{ Form::file('attached_file', ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 
            'form-control', 'v-validate' => "'required|ext:pdf,ppt,pptx,docx,xlsx,mp3,mp4'"]) }}
        <small>.mp3, .mp4, .ppt, .pptx, .docx, .xlsx and .pdf</small>
    @endif
        <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
        {!! $errors->first('attached_file', '<div class="invalid-feedback">:message</div>') !!}
</div>