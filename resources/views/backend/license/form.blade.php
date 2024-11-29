@extends('backend.layouts.default')

@section('title', __('License'))

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
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.license.update',
                            $post->id) , 'class' => 'form-horizontal')) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route'
                            => 'admin.license.store', 'class' => 'form-horizontal')) !!}
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

                                <div class="form-group">
                                    <label for="description">{{ __('Description') }}</label>
                                    <textarea data-provide="summernote" data-height="200"
                                            class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                            rows="5" name="description"
                                            id="description">{{ old('description', isset($post->description) ? $post->description: '') }}</textarea>
                                    {!! $errors->first('description', '<p class="invalid-feedback">:message</p>') !!}
                                </div>

                                @can('publish_license')
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
                                    @if (auth()->user()->can('add_license') || auth()->user()->can('edit_license'))
                                        <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                                            Save
                                        </button>
                                        {{--<button class="btn btn-secondary" type="submit" name="btnApply" value="1">
                                            Apply
                                        </button>--}}
                                    @endif
                                    <a href="{{ route('admin.license.index') }}"
                                       class="btn btn-flat">Cancel</a>
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
