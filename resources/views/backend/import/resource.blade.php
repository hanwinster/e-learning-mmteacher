@extends('backend.layouts.default')

@section('title', __('Import Resources'))

@section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
        {!! \Form::open(array('files' => true, 'route' => 'admin.resource.save-bulk-import', 'class' => 'form-horizontal')) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="uploaded_file">@lang('Upload File')</label>

                {{ Form::file('uploaded_file') }}
                <small class="form-text text-muted">allow .xlsx file only</small>
                {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

{{--             <div class="form-group">
                <label for="batch_no">@lang('Batch No.')</label>
                    <input type="text" placeholder="Batch No." name="batch_no" id="batch_no" class="form-control" value="{{ old('batch_no', isset($post->batch_no) ? $post->batch_no: '') }}">
                    {!! $errors->first('batch_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> --}}

            <div class="form-group">
                <div class="col-sm-12">
                    <button class="btn btn-primary" type="submit" name="action">
                        {{ __('Import') }}
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-flat">Cancel</a>
                </div>
            </div>
            </form>
        </div>
    </div>

</div>
@endsection

