@extends('backend.layouts.default')

@section('title', __('Change Password'))

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="card">
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (isset($post))
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.profile.update-password') }}">
                        @endif
						
                                <div class="box-body">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="form-group">
                                        <label class="control-label">Current Password</label>
                                        <input type="password" class="form-control" name="current_password">
                                        <p class="help-block">Type your current password first.</p>
                                    </div>
									
                                    <hr>
                                    <div class="form-group">
                                        <label class="control-label">New Password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
									
                                    <div class="form-group">
                                        <label class="control-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="btnSave" id="btnSave">
                                        {{ __('Change') }}
                                    </button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
