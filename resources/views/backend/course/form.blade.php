@extends('backend.layouts.default')

@section('title', __('Course'))

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Default Elements -->
            <div class="card">
                <h4 class="card-title">
                    @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                </h4>

                <div class="card-body" id="elibrary_root">

                    @if (isset($post))
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.course.update',
                    $post->id)
                    , 'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => 'admin.course.store',
                    'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @endif
                    {!! Form::hidden('redirect_to', url()->previous()) !!}

                    @if (isset($post->id))
                    <input type="hidden" name="id" id="id" value="{{ $post->id }}">
                    @else
                    <input type="hidden" name="id" id="id" value="">
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title" class="require">@lang('Title')</label>
                                <textarea  v-validate="'required'" name="title" placeholder="Title..."   id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">{{old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="course_category_id"
                                    class="col-xs-12 require">{{__('Course Category') }}</label>
                                {!! Form::select('course_category_id',
                                $categories, old('course_category_id', isset($post->course_category_id)? $post->course_category_id:
                                ''), ['class' => $errors->has('course_category_id')?
                                'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) !!}
                                {!! $errors->first('course_category_id', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('course_category_id')" class="invalid-feedback">@{{ errors.first('course_category_id') }}</div>
                            </div>

                            <div class="form-group">
                                <label for="level_id" class="col-xs-12 require">{{__('Level') }}</label>
                                {!! Form::select('level_id', $levels, old('level_id', isset($post->level_id)? $post->level_id:
                                    ''), ['class' => $errors->has('level_id')?
                                    'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) !!}
                                {!! $errors->first('level_id', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('level_id')" class="invalid-feedback">@{{ errors.first('level_id') }}</div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="require">@lang('Description')</label>
                                <textarea  v-validate="'required'" name="description" placeholder="Description..."   id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                            </div>

                            <div class="form-group">
                                <label for="url_link" class="">@lang('Url Link')</label>
                                <input v-validate="'url'" type="text" placeholder="Url Link.." name="url_link" id="url_link"
                                    class="form-control{{ $errors->has('url_link') ? ' is-invalid' : '' }}"
                                    value="{{ old('url_link', isset($post->url_link) ? $post->url_link: '') }}">
                                {!! $errors->first('url_link', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('url_link')" class="invalid-feedback">@{{ errors.first('url_link') }}</div>
                            </div>  

                            <div class="form-group">
                                <label for="downloadable_option" class="col-xs-12 require">{{__('Downloadable Option') }}</label>
                                {!! Form::select('downloadable_option', $downloadable_options, old('downloadable_option', isset($post->downloadable_option)? $post->downloadable_option:
                                    ''), ['class' => $errors->has('downloadable_option')?
                                    'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) !!}
                                {!! $errors->first('downloadable_option', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('downloadable_option')" class="invalid-feedback">@{{ errors.first('downloadable_option') }}</div>
                            </div>                      

                            <div class="form-group">
                                <label for="cover_image" class="@if(isset($post->id))  @else require @endif">{{ __('Cover Image') }}</label>
                                @if(isset($post->id))                                
                                {{ Form::file('cover_image',
                                ['class' => $errors->has('cover_image') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'image|size:5120'"]) }}
                                    @if ($post->getThumbnailPath())
                                    <div style="padding-top: 5px;">
                                        @forelse($post->getMedia('course_cover_image') as $image)
                                            <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                                <img src="{{ asset($image->getUrl('thumb')) }}">
                                            </a>
                                        @empty
                                        @endforelse
                                    </div>
                                    @endif
                                @else
                                {{ Form::file('cover_image',
                                ['class' => $errors->has('cover_image') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'required|image|size:5120'"]) }}
                                <div v-show="errors.has('cover_image')" class="vee-validate-invalid-feedback">@{{ errors.first('cover_image') }}</div>
                                @endif
                            </div> 
                            <div class="form-group">
                                <label for="resource_file" class="">{{ __('Resource File') }}</label>
                                @if(isset($post->id))
                                {{ Form::file('resource_file',
                                ['class' => $errors->has('resource_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'ext:zip,rar,docx,pdf|size:5242880'"]) }}
                                <small>.zip, .rar, .docx and .pdf</small>
                                <div style="padding: 10px 0px;">
                                    @foreach($post->getMedia('course_resource_file') as $resource)
                                        <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                    @endforeach
                                </div>
                                @else
                                {{ Form::file('resource_file', ['class' => $errors->has('resource_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'ext:zip,rar,docx,pdf|size:5242880'"]) }}
                                <small>.zip, .rar, .docx and .pdf</small>
                                @endif 
                                <div v-show="errors.has('resource_file')" class="invalid-feedback">@{{ errors.first('resource_file') }}</div>
                            </div>                       

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="user_type" class="require">{{ __('Accessible Right') }}</label>
                            @foreach ($userTypes as $key => $value)
                                <div class="form-check">
                                    @if (isset($post) && $post->privacies)
                                        @php
                                            $privacies = array();
                                            $post_privacies = $post->privacies;
                                            // $privacies = $post->privacies->pluck('user_type')->toArray();

                                            foreach ($post_privacies as $post_privacy) {

                                                $privacies[] = $post_privacy->user_type;
                                            }
                                        @endphp

                                    {{ Form::checkbox('user_type[]', $key, in_array($key, $privacies)? true : false, ['id' => 'user_type_' . $key,
                                    'class' => $errors->has('user_type')? 'form-check-input is-invalid' : 'form-check-input', 'v-validate' => "'required'"] ) }}

                                    @else

                                    {{ Form::checkbox('user_type[]', $key, in_array($key, $default_rights), ['id' => 'user_type_' . $key,
                                    'class' => $errors->has('user_type')? 'form-check-input is-invalid' : 'form-check-input', 'v-validate' => "'required'"
                                    , 'onclick' =>  in_array($key, $default_rights) ? "return false;" : "return true;"]) }}

                                    @endif
                                    <label class="form-check-label" title="{{ $value }}"> {{ Form::label('user_type_' . $key, $value) }} </label>
                                    @if ($loop->last)
                                    {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                                    <div v-show="errors.has('user_type[]')" class="invalid-feedback">Please select at least one Right.</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($canPublish)
                        <div class="form-group">
                            <div>{{ __('Published?') }}</div>
                            <div class="form-check form-check-inline">
                                {{ Form::radio('is_published', 1, (isset($post->is_published) && $post->is_published == 1 ? true : false ), ['id' => 'is_published_yes', 'class' => 'form-check-input']) }}
                                <label for="is_published_yes" class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                {{ Form::radio('is_published', 0, (!isset($post->is_published) || $post->is_published == 0 ? true : false ), ['id' => 'is_published_no', 'class' => 'form-check-input']) }}
                                <label for="is_published_no" class="form-check-label">No</label>
                            </div>
                            {!! $errors->first('is_published', '<p class="invalid-feedback">:message</p>') !!}
                        </div>
                        @endif
                        @if($canApprove)
                            <div class="form-group">
                                <div>{{ __('Allow Edit?') }}</div>
                                <div class="form-check form-check-inline">
                                    {{ Form::radio('allow_edit', 1, (isset($post->allow_edit) && $post->allow_edit == 1 ? true : false ), ['id' => 'allow_edit_yes', 'class' => 'form-check-input']) }}
                                    <label for="allow_edit_yes" class="form-check-label">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    {{ Form::radio('allow_edit', 0, (!isset($post->allow_edit) || $post->allow_edit == 0 ? true : false ), ['id' => 'allow_edit_no', 'class' => 'form-check-input']) }}
                                    <label for="allow_edit_no" class="form-check-label">No</label>
                                </div>
                                {!! $errors->first('allow_edit', '<p class="invalid-feedback">:message</p>') !!}
                            </div>

                            @if($canLock)
                            <div class="form-group">
                                <div>{{ __('Locked?') }}</div>

                                <div class="form-check form-check-inline">
                                    {{ Form::radio('is_locked', 1, (isset($post->is_locked) && $post->is_locked == 1 ? true : false ), ['id' => 'is_locked_yes', 'class' => 'form-check-input']) }}
                                    <label for="is_locked_yes" class="form-check-label">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    {{ Form::radio('is_locked', 0, (!isset($post->is_locked) || $post->is_locked == 0 ? true : false ), ['id' => 'is_locked_no', 'class' => 'form-check-input']) }}
                                    <label for="is_locked_no" class="form-check-label">No</label>
                                </div>
                                {!! $errors->first('is_locked', '<p class="invalid-feedback">:message</p>') !!}
                            </div>  
                            @endif                      
                        
                            <div class="form-group">
                                <label for="approval_status" class="col-xs-12 ">{{__('Approval Status')}}</label> {!! Form::select('approval_status',
                                $approvalStatus, old('approval_status', isset($post->approval_status) ? $post->approval_status: ''), ['class' => $errors->has('approval_status')?
                                'form-control is-invalid' : 'form-control' ]) !!} {!! $errors->first('approval_status', '
                                <div class="invalid-feedback">:message</div>') !!}
                            </div>
                        @endif
                            
                    </div>                    
                </div>

                <div class="form-group">
                    <div class="btn-group">
                        <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                    {{ __('Save') }}
                    </button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <div class="dropdown-menu">
                        <button class="dropdown-item" style="cursor: pointer; width: 100%" type="submit" name="btnSaveClose" value="1">
                            {{ __('Save & Close') }}
                        </button>
                        @if(!isset($post->id))
                        <button class="dropdown-item"  style="cursor: pointer; width: 100%"  type="submit" name="btnSaveNew" value="1">
                            {{ __('Save & New') }}
                        </button>
                        @endif
                        <button class="dropdown-item"  style="cursor: pointer; width: 100%"  type="submit" name="btnSaveNext" value="1">
                            {{ __('Save & Next') }}
                        </button>
                        </div>
                    </div>

                    <a href="{{ route('admin.course.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                </div>
                </form>
            </div>
        </div>
        <!-- END Default Elements -->
    </div>
</div>
</div>
@stop

@section('css')
@parent
@endsection

@section('js')
@parent
    <script src="https://unpkg.com/vee-validate@latest"></script>
<script>
$(document).ready(function() {
    $('#description').summernote({
        height: 200,
        toolbar: [
            // [groupName, [list of button]],
            ['fontname', ['fontname']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            // ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
    });
});
</script>

<script>

        new Vue({
            el: '#elibrary_root',
            data: {
                messages: {

                },
            },
            //components: [commodity_component],

            mounted() {
            },
            methods: {
                validateBeforeSubmit: function(e) {
                    this.$validator.validateAll().then((result) => {
                        console.log(result)

                        if (result) {
                            // eslint-disable-next-line
                            return true;
                        }
                        e.preventDefault();
                    });
                }
            }

        });
        </script>
    @endsection
