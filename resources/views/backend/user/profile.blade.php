@extends('backend.layouts.default')

@section('title', __('Change Profile'))

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="card">
                    <div class="card-body">
					{{--@if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
					@endif--}}

                        @if (isset($post))
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{ route('admin.profile.update-profile') }}">
                                @endif
                                <div class="box-body">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="form-group">
                                        <label class="control-label">Username</label>
                                        <input type="text" class="form-control" name="username" value="{{ old('username', isset($post) ? $post->username : null) }}">
                                        <p class="help-block">Type username with alpha-numeric characters, as well as dashes and underscores.</p>
                                    </div>
									<div class="form-group">
                                        <label class="control-label">Email</label>
                                        <input type="text" class="form-control" name="email" value="{{ old('email', isset($post) ? $post->email : null) }}">
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="control-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', isset($post) ? $post->name : null) }}">
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
