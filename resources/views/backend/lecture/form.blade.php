@extends('backend.layouts.default')

@section('title', __('Lecture'))

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Default Elements -->
            <div class="card">
                <h4 class="card-title">
                    @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                </h4>

                <div class="card-body" id="lecture_root">

                    @if (isset($post))
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.lecture.update', $post->id)
                    , 'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => ['admin.lecture.store', $course->id],
                    'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @endif
                    {!! Form::hidden('redirect_to', url()->previous()) !!}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h5>{{$course->title}}</h5>
                                <input type="hidden" name="course_id" value="{{$course->id}}">
                            </div>    
                            <div class="form-group">
                                <label for="lecture_title" class="require">@lang('Lecture Title')</label>
                                <textarea  v-validate="'required'" name="lecture_title" placeholder="Title.."   id="lecture_title" class="form-control{{ $errors->has('lecture_title') ? ' is-invalid' : '' }}">{{old('lecture_title', isset($post->lecture_title) ? $post->lecture_title: '')}}</textarea>
                                {!! $errors->first('lecture_title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('lecture_title')" class="invalid-feedback">@{{ errors.first('lecture_title') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="attached_file" class="@if(isset($post->id))  @else require @endif">{{ __('Attached File') }}</label>
                                @if(isset($post->id))
                                {{ Form::file('attached_file',
                                ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'ext:pdf,ppt,pptx,mp3,mp4'"]) }}
                                <small>.mp3, .mp4, .ppt, .pptx and .pdf</small>
                                <div style="padding: 10px 0px;">
                                    @foreach($post->getMedia('lecture_attached_file') as $resource)
                                        <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                    @endforeach
                                </div>
                                @else
                                {{ Form::file('attached_file', ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'required|ext:pdf,ppt,pptx,mp3,mp4'"]) }}
                                <small>.mp3, .mp4, .ppt, .pptx and .pdf</small>
                                @endif 
                                <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="resource_link" class="">{{ __('Resource Link') }}</label>
                                <input v-validate="'url'" type="text" placeholder="link" name="resource_link" id="resource_link"
                                        value="{{ old('resource_link', isset($post->resource_link) ? $post->resource_link: '') }}"
                                        class="form-control{{ $errors->has('strand') ? ' is-invalid' : '' }}">
                                {!! $errors->first('resource_link', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('resource_link')" class="invalid-feedback">@{{ errors.first('resource_link') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="">@lang('Description')</label>
                                <textarea  v-validate="''" name="description" placeholder="Description..."   id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                            </div>

                    </div>                    
                </div>

                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="btnSave" value="Save">
                    <input class="btn btn-primary" type="submit" name="btnSaveClose" value="Save & Close">
                     @if(!isset($post))
                    <input class="btn btn-primary" type="submit" name="btnSaveNew" value="Save & New">
                    @endif
                    <input class="btn btn-primary" type="submit" name="btnSaveNext" value="Save & Next">
                    <a href="{{ route('admin.course.show', $course->id).'#nav-lecture' }}" class="btn btn-flat">{{ __('Cancel') }}</a>
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
            el: '#lecture_root',
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
