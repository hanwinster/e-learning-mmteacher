@extends('backend.layouts.default')

@section('title', __('Change Password'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Change Password') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">         
                <div class="col-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('Change Password') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="container fluid">
                                <div class="row gap-y">
                                    <div class="col-12">

                                        <form method="post" action="{{ route('member.change-password.update') }}" class="{{ $errors->any() ? 'was-validated' : '' }}">
                                            {{ csrf_field() }}
                                            
                                            <div class="form-group">
                                                <label for="password" class="require">@lang('Current Password')</label>
                                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password"
                                                    value="" placeholder=""> {!! $errors->first('password',
                                                '
                                                <div class="invalid-feedback">:message</div>') !!}
                                                <small class="form-text text-muted">{{ __('Type your current password first.') }}</small>
                                            </div>

                                            <hr>
                                            <div class="form-group">
                                                <label for="new_password" class="require">@lang('New Password')</label>
                                                <input type="password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" 
                                                name="new_password" id="new_password"
                                                    value="" placeholder=""> {!! $errors->first('new_password',
                                                '
                                                <div class="invalid-feedback">:message</div>') !!}
                                            </div>

                                            <div class="form-group">
                                                <label for="new_password_confirmation">@lang('Confirm Password')</label>
                                                <input type="password" class="form-control{{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" 
                                                    name="new_password_confirmation"
                                                    id="new_password_confirmation" value="" placeholder="">                                        
                                                    {!! $errors->first('new_password_confirmation', '
                                                <div class="invalid-feedback">:message</div>') !!}
                                            </div>

                                            <div class="form-group">
                                                <button class="btn btn-primary">{{ __('Update') }}</button>
                                            </div>

                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
