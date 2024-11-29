@extends('backend.layouts.default')

@section('title', 'Category')

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
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.category.update', $post->id)
                    , 'class' => 'form-horizontal')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => 'admin.category.store',
                    'class' => 'form-horizontal')) !!}
                    @endif
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title" class="require">@lang('Name')</label>
                                    <input type="text" placeholder="Name.." name="name" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                            <div class="form-group">
                                <label for="slug" class="require">@lang('Slug')
                                        <i data-provide="tooltip" class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-original-title="The user friendly and part of a URL which identifies a particular Category on a website in a form readable by users. e.g., http://example.com/{about-us}"></i>
                                    </label>
                                    <input type="text" placeholder="Slug.." name="slug" id="slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" value="{{ old('slug', isset($post->slug) ? $post->slug: '') }}">
                                    {!! $errors->first('slug', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            <div class="form-group">
                                <label for="parent_id" class="col-xs-12">
                                    @lang('Parent Category')
                                </label>
                                {!! Form::select('parent_id', $categories, old('parent_id', isset($post->parent_id) ? $post->parent_id: ''), ['class' => 'form-control']) !!}
                                {!! $errors->first('parent_id', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                            <div class="form-group">
                                <label for="body" class="col-xs-12">@lang('Body')</label>
                                    <textarea class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" rows="5" name="body" id="body">{{ old('body', isset($post->body) ? $post->body: '') }}</textarea>
                                    {!! $errors->first('body', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="published">@lang('Image')</label> {{ Form::file('uploaded_file') }} @if (isset($post->file_name))
                                    <p class="help-block">
                                        <a target="_blank" href="{{ asset('assets/images/categories/'.$post->file_path.'/'.$post->file_name) }}">
                                            <img src="{{ asset('assets/images/categories-thumbnails/'.$post->file_path.'/'.$post->file_name) }}">
                                    </a>
                                    </p>
                                    @endif
                                    {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                            </div>
                            @can('publish_block')
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
                            <div class="form-group">
                                @if (auth()->user()->can('add_block') || auth()->user()->can('edit_block'))
                                    <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                                        Save
                                    </button>
                                    <button class="btn btn-secondary" type="submit" name="btnApply" value="1">
                                        Apply
                                    </button>
                                @endif
                                    <a href="{{ route('admin.category.index') }}" class="btn btn-flat">Cancel</a>
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
<script src="{{ asset('assets/backend/js/helpers/ckeditor/ckeditor.js') }}"></script>
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