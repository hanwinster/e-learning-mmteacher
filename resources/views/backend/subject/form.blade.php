@extends('backend.layouts.default')

@section('title', __('Subject/ Learning Area'))

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Default Elements -->
                <div class="card">
                    <h4 class="card-title">
                        @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                    </h4>
                    <div class="card-body">
                        @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.subject.update',
                            $post->id) , 'class' => 'form-horizontal')) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route'
                            => 'admin.subject.store', 'class' => 'form-horizontal')) !!}
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="title" class="require">@lang('Title')</label>
                                    <input type="text" placeholder="Title.." name="title" id="title"
                                           class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                    {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                </div>

                                @can('publish_subject')
                                    <div class="form-group">
                                        <div><label for="published_yes" class="col-xs-12">Published</label></div>
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), ['id' => 'published_yes',
                                            'class' => 'form-check-input']) }}
                                            <label for="published_yes" class="form-check-label">Yes</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ), ['id' => 'published_no',
                                            'class' => 'form-check-input']) }}
                                            <label for="published_no" class="form-check-label">No</label>
                                        </div>
                                        {!! $errors->first('published', '
                                        <div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                @endcan
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    @if (auth()->user()->can('add_subject') || auth()->user()->can('edit_subject'))
                                        <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                                            Save
                                        </button>
                                        {{--<button class="btn btn-secondary" type="submit" name="btnApply" value="1">
                                            Apply
                                        </button>--}}
                                    @endif
                                    <a href="{{ route('admin.subject.index') }}" class="btn btn-flat">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- END Default Elements -->
        </div>
    </div>
    </div>


@stop
@section('css') @parent
@endsection

@section('js') @parent
<!-- ckeditor.js, load it only in the Category you would like to use CKEditor (it's a heavy plugin to include it with the others!) -->
<script>
    var editorURL = '/admin/filemanager'
    var options = {
        filebrowserImageBrowseUrl: editorURL + '?type=Images',
        filebrowserImageUploadUrl: editorURL + '/upload?type=Images&_token=',
        filebrowserBrowseUrl: editorURL + '?type=Files',
        filebrowserUploadUrl: editorURL + '/upload?type=Files&_token='
    };
    CKEDITOR.replace('body', options);

</script>
@endsection
