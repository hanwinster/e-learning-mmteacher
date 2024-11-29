@extends('backend.layouts.default')

@section('title', 'Block')

@section('content')
    <div class="content">
        @if (isset($post))
        {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.block.update', $post->id)
    , 'class' => 'form-horizontal')) !!}
        @else
        {!! \Form::open(array('files' => true, 'route' => 'admin.block.store', 'class'
    => 'form-horizontal')) !!}
        @endif
        <div class="row">
            <div class="col-md-8">
                <!-- Default Elements -->
                <div class="card">
                    <h4 class="card-title">
                        @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                    </h4>
                    <div class="card-body">

                        <div class="form-group">
                            <label for="title" class="require">@lang('Title')</label>
                            <input type="text" placeholder="Title.." name="title" id="title"
                                   class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                   value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                        <div class="form-group">
                            <label for="body" class="col-xs-12">@lang('Body')</label>
                            <textarea data-provide="ckeditor"
                                      class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}"
                                      rows="5" name="body"
                                      id="body">{{ old('body', isset($post->body) ? $post->body: '') }}</textarea>
                            {!! $errors->first('body', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                        <div class="form-group">
                            <label for="published">@lang('Image')</label> {{ Form::file('uploaded_file') }} @if (isset($post->file_name))
                                <p class="help-block">
                                    <a target="_blank"
                                       href="{{ asset('assets/images/pages/'.$post->file_path.'/'.$post->file_name) }}">
                                        <img src="{{ asset('assets/images/pages-thumbnails/'.$post->file_path.'/'.$post->file_name) }}">
                                    </a>
                                </p>
                            @endif
                            {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
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
                                {!! $errors->first('published', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        @endcan
                        <div class="form-group">
                            @if (auth()->user()->can('add_block') || auth()->user()->can('edit_block'))
                                <button class="btn btn-primary" type="submit" name="btnSave" value="1">Save
                                </button>
                                {{--<button class="btn btn-secondary" type="submit" name="btnApply" value="1">Apply
                                </button>--}}
                            @endif
                            <a href="{{ route('admin.block.index') }}" class="btn btn-flat">Cancel</a>
                        </div>

                    </div>
                </div>
                <!-- END Default Elements -->
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-title">
                        <h4>Block Attributes</h4>
                    </div>
                    <div class="card-body">
                        <p>
                            Last Updated: {{ isset($post->updated_at) ? $post->updated_at : '' }}
                        </p>
                        <div class="form-group required {!! $errors->first('region', 'has-error') !!}">
                            <label for="region" class="col-xs-12">
                                @lang('Region')
                            </label> {{ Form::select('region', $regions, isset($post->region) ? $post->region
                        : '', ['class' => 'form-control']) }} {!! $errors->first('region', '
                        <div class="invalid-feedback">:message</div>') !!}
                        </div>
                        <div class="form-group required {!! $errors->first('conditions', 'has-error') !!}">
                            <label for="conditions" class="col-xs-12">
                                @lang('Conditions')
                            </label>
                            <textarea placeholder="Conditions.." name="conditions" id="conditions" class="form-control"
                                      rows="5">{{ old('conditions', isset($post->conditions) ? $post->conditions: '') }}</textarea> {!! $errors->first('position', '
                        <div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group required {!! $errors->first('conditions', 'has-error') !!}">
                            <label class="col-xs-12">Hide Title</label>
                            <br>
                            <input type="radio" name="hide_title" id="hide_title_yes"
                                   value="1" {{ (isset($post) && $post->hide_title == 1) ? 'checked': '' }} >
                            <label for="hide_title_yes">Yes</label>
                            <input type="radio" name="hide_title" id="hide_title_no" value="0" {{ (isset($post) && $post->hide_title ==
                        0) ? 'checked': '' }}>
                            <label for="hide_title_no">No</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>


@stop
@section('css')
@parent
@endsection

@section('js')
@parent
<!-- ckeditor.js, load it only in the page you would like to use CKEditor (it's a heavy plugin to include it with the others!) -->
<script>
    var editorURL = '/{{ config('cms.backend_uri') }}/laravel-filemanager'
    var options = {
        filebrowserImageBrowseUrl: editorURL + '?type=Images',
        filebrowserImageUploadUrl: editorURL + '/upload?type=Images&_token=',
        filebrowserBrowseUrl: editorURL + '?type=Files',
        filebrowserUploadUrl: editorURL + '/upload?type=Files&_token='
    };
    CKEDITOR.replace('body', options);
</script>
@endsection