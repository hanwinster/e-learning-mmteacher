@extends('backend.layouts.default')

@section('title', __('Link Report'))

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
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.link-report.update',
                            $post->id) , 'class' => 'form-horizontal')) !!}
                        @else
                            {!! \Form::open(array('files' => true, 'route'
                            => 'admin.link-report.store', 'class' => 'form-horizontal')) !!}
                        @endif
                        <input type="hidden" name="resource_id" value="{{ $post->resource_id }}">
                        <input type="hidden" name="report_type" value="{{ $post->report_type }}">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="form-group">
                                    <label>{{ __('Resource') }}</label>
                                    <div>
                                        {{ $post->resource->title ?? '' }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('User') }}</label>
                                    <div>
                                        {{ $post->user->name ?? '' }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Report Type') }}</label>
                                    <div>
                                        {{ $post->getTypeName() ?? '' }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Reported At') }}</label>
                                    <div>
                                        {{ $post->created_at }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Updated At') }}</label>
                                    <div>
                                        {{ $post->updated_at }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="comment">{{ __('Comment') }}</label>
                                    <textarea
                                            class="form-control {{ $errors->has('comment') ? ' is-invalid' : '' }}"
                                            rows="5" name="comment"
                                            id="comment">{{ old('comment', isset($post->comment) ? $post->comment: '') }}</textarea>
                                    {!! $errors->first('comment', '<p class="invalid-feedback">:message</p>') !!}
                                </div>

                                <div class="form-group">
                                    <div><label for="published_yes" class="col-xs-12">{{ __('Status') }}</label></div>
                                    <div class="form-check form-check-inline">
                                        {{ Form::radio('status', 0, (!isset($post->status) || $post->status == 0 ? true : false ), ['id' => 'published_no', 'class'
                                        => 'form-check-input']) }}
                                        <label for="published_no" class="form-check-label">{{ __('Pending') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        {{ Form::radio('status', 1, (isset($post->status) && $post->status == 1 ? true : false ), ['id' => 'published_yes', 'class'
                                        => 'form-check-input']) }}
                                        <label for="published_yes" class="form-check-label">{{ __('Closed') }}</label>
                                    </div>

                                    {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    @can('edit_link_report')
                                    <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                                        {{ __('Save') }}
                                    </button>
                                    @endcan
                                    <a href="{{ route('admin.link-report.index') }}"
                                       class="btn btn-flat">{{ __('Cancel') }}</a>
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
