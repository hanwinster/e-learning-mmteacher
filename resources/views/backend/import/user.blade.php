@extends('backend.layouts.default')

@section('title', __('Import Users'))

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">                           
                            {{  __('Import Users') }}                           
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('layouts.form_alert')
                <div class="col-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{ __('Import Users') }}
                            </h4>
                        </div>
                       
                        <div class="card-body">
                            {!! \Form::open(array('files' => true, 'route' => 'admin.user.save-bulk-import', 'class' => 'form-horizontal')) !!}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="form-group">
                                    <label for="uploaded_file">@lang('Upload File')</label>

                                    {{ Form::file('uploaded_file') }}
                                        <small class="form-text text-muted">@lang('Allow .xlsx file only')</small>
                                    {!! $errors->first('uploaded_file', '<div class="invalid-feedback">:message</div>') !!}
                                    
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary btn-md" type="submit" name="action">
                                            {{ __('Import') }}
                                        </button>
                                        <a href="{{ route('member.dashboard') }}" class="btn btn-outline-dark">@lang('Cancel')</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

