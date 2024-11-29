@extends('backend.layouts.default')
@if (isset($post->id)) 
    @section('title', __('Edit User'))
@else 
    @section('title', __('New User'))
@endif
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('member.user.index')}}">{{ __('Users') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)) 
                                {{ __('Edit User') }} 
                            @else 
                                {{ __('New User') }}                         
                            @endif
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row gap-y">
                @include('layouts.form_alert') 
                <div class="col-12 mx-auto" id="elearning_root">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                @if (isset($post->id)) 
                                    {{ __('Edit User') }} 
                                @else 
                                    {{ __('New User') }}                         
                                @endif
                            </h5>
                        </div>
                        <div class="card-body"> 
                            {!! \Form::open(array('method' => 'put', 'route' =>
                           array('member.user.update', $post->id),
                            'class' => $errors->any() ? 'form-horizontal was-validated' : 'form-horizontal')) !!}

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="name" class="col-xs-12">{{ __('Name') }}</label>
                                            {{ $post->name }}
                                        </div>

                                        <div class="form-group">
                                            <label for="username" class="col-xs-12">{{ __('Username') }}</label>
                                        {{ $post->username }}
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="col-xs-12">{{ __('Email') }}</label>
                                            {{ $post->email }}
                                        </div>

                                        <div class="form-group">
                                            <label for="mobile_no" class="require">{{ __('Mobile No.') }}
                                            </label>

                                            {{ $post->mobile_no }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5>{{ __('Student/Teacher/Learner Information') }}</h5>                                    
                                        <div class="form-group {!! $errors->first('user_type', 'has-error') !!}">
                                            <label for="user_type" class="col-xs-12">{{ __('Type of Users') }}</label>
                                            {!! Form::select('user_type', $user_types, old('user_type',
                                            isset($post->user_type) ? $post->user_type : ''), ['class' => $errors->has('user_type')
                                            ? 'form-control is-invalid user_types_all' : 'form-control user_types_all']) !!}
                                            {!! $errors->first('user_type','<div class="invalid-feedback">:message</div>')
                                            !!}
                                        </div>
                                        <div class="form-group">
                                            <label for="ec_college" class="col-xs-12">{{ __('Education College') }}</label>
                                            {!! Form::select('ec_college',
                                            $ec_colleges, old('ec_college', isset($post->ec_college) ? $post->ec_college :
                                            ''), ['class' => $errors->has('ec_college')
                                            ? 'form-control is-invalid' : 'form-control']) !!}
                                            {!! $errors->first('ec_college', '<div class="invalid-feedback">:message</div>')
                                            !!}
                                        </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-md">{{ __('Update') }}</button>
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

@section('script') @parent
<script>
    $(function() {//alert("P");
		
	});
</script>
@endsection
