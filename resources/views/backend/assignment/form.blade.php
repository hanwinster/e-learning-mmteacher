@extends('backend.layouts.default')

@section('title', __('Assignment'))

@section('css')
    @parent
@stop

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Default Elements -->
            <div class="card">
                <h4 class="card-title">
                    @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                </h4>

                <div class="card-body" id="assignment_root">

                    @if (isset($post))
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.assignment.update',
                    $post->id)
                    , 'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => ['admin.assignment.store', $course->id],
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
                                <label for="title" class="require">@lang('Assignment Title')</label>
                                <textarea  v-validate="'required'" name="title" placeholder="Title.."   id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}">{{old('title', isset($post->title) ? $post->title: '')}}</textarea>
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="description">@lang('Assignment Instruction')</label>
                                <textarea name="description" id="description" rows="4" class="form-control">{{old('description', isset($post->description) ? $post->description: '')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="attached_file" class="@if(isset($post->id))  @else require @endif">{{ __('Attached File') }}</label>
                                @if(isset($post->id))
                                {{ Form::file('attached_file',
                                ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'ext:pdf,ppt,pptx,docx,xlsx,mp3,mp4'"]) }}
                                <small>.mp3, .mp4, .ppt, .pptx, .docx, .xlsx and .pdf</small>
                                <div style="padding: 10px 0px;">
                                    @foreach($post->getMedia('assignment_attached_file') as $resource)
                                        <a href="{{asset($resource->getUrl())}}"  class=""><i class="ti-clip"></i> {{ $resource->file_name }}</a>
                                    @endforeach
                                </div>
                                @else
                                {{ Form::file('attached_file', ['class' => $errors->has('attached_file') ? 'form-control is-invalid' : 'form-control', 'v-validate' => "'required|ext:pdf,ppt,pptx,docx,xlsx,mp3,mp4'"]) }}
                                @endif 
                                <small>.mp3, .mp4, .ppt, .pptx, .docx, .xlsx and .pdf</small>
                                <div v-show="errors.has('attached_file')" class="invalid-feedback">@{{ errors.first('attached_file') }}</div>
                                {!! $errors->first('attached_file', '<div class="invalid-feedback">:message</div>') !!}
                            </div>                        

                    </div>                    
                </div>

                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="btnSave" value="Save">
                     @if(!isset($post))
                    <input class="btn btn-primary" type="submit" name="btnSaveNew" value="Save & New">
                    @endif
                    <input class="btn btn-primary" type="submit" name="btnSaveClose" value="Save & Close">
                    <a href="{{ route('admin.course.show', $course->id).'#nav-assignment' }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                </div>
                </form>
            </div>
        </div>
        <!-- END Default Elements -->
    </div>
</div>
</div>
@stop

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
        })
    });
    </script>

    <script>

        new Vue({
            el: '#assignment_root',
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
@stop
