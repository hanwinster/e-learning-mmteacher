@extends('backend.layouts.default')

@section('title', __('Keyword'))

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
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.keyword.update',
                    $post->id) ,
                    'class' => 'form-horizontal')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => 'admin.keyword.store',
                    'class' => 'form-horizontal')) !!}
                    @endif

                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="keyword" class="require">@lang('Keyword')</label>
                                <input type="text" placeholder="Keyword.." name="keyword" id="keyword"
                                    class="form-control{{ $errors->has('keyword') ? ' is-invalid' : '' }}"
                                    value="{{ old('keyword', isset($post->keyword) ? $post->keyword: '') }}">
                                {!! $errors->first('keyword', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                            @can('publish_keyword')
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
                                @if (auth()->user()->can('add_keyword') || auth()->user()->can('edit_keyword'))
                                <button class="btn btn-primary" type="submit" name="btnSave" value="1">Save
                                </button>
                                @endif
                                <a href="{{ route('admin.keyword.index') }}" class="btn btn-flat">Cancel</a>
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
