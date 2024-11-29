@extends('backend.layouts.default')

@section('title', __('Contact Message'))

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.course-category.index') }}">{{ __('Course Categories') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id)){{ __('Edit Course Category') }}
                            @else {{ __('Add A Course Category') }}
                            @endif
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
                <div class="col-md-12">
                    <!-- Default Elements -->
                    <div class="card">
                        <h4 class="card-title">
                            @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                        </h4>
                        <div class="card-body">
                            @if (isset($post))
                                {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.contact.update',
                                $post->id) , 'class' => 'form-horizontal')) !!}
                            @else
                                {!! \Form::open(array('files' => true, 'route'
                                => 'admin.contact.store', 'class' => 'form-horizontal')) !!}
                            @endif
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label for="subject" >@lang('Subject')&nbsp;<span class="required">*</span></label>
                                        <input readonly type="text" placeholder="Subject.." name="subject" id="subject"
                                            class="form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}"
                                            value="{{ old('subject', isset($post->subject) ? $post->subject: '') }}">
                                        {!! $errors->first('subject', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="message">@lang('Message')&nbsp;<span class="required">*</span></label>
                                        <textarea readonly
                                                class="form-control {{ $errors->has('message') ? ' is-invalid' : '' }}"
                                                rows="5" name="message"
                                                id="message">{{ old('message', isset($post->message) ? $post->message: '') }}</textarea>
                                        {!! $errors->first('message', '<p class="invalid-feedback">:message</p>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="name" >@lang('Name')&nbsp;<span class="required">*</span></label>
                                        <input readonly type="text" placeholder="Name.." name="name" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="email" >@lang('Email')&nbsp;<span class="required">*</span></label>
                                        <input readonly type="text" placeholder="Email.." name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            value="{{ old('email', isset($post->email) ? $post->email: '') }}">
                                            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="phone_no">@lang('Phone No.')</label>
                                        <input readonly type="text" placeholder="Phone No." name="phone_no" 
                                            id="phone_no" class="form-control{{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                            value="{{ old('phone_no', isset($post->phone_no) ? $post->phone_no: '') }}">
                                            {!! $errors->first('phone_no', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="organization" >@lang('Organization')</label>
                                        <input readonly type="text" placeholder="Organization" name="organization" 
                                            id="organization" class="form-control{{ $errors->has('organization') ? ' is-invalid' : '' }}"
                                            value="{{ old('organization', isset($post->organization) ? $post->organization: '') }}">
                                            {!! $errors->first('organization', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="region_state" >@lang('Regions/States')</label>
                                        {!! Form::select('region_state', \App\Models\Contact::REGIONS_STATES,
                                            old('region_state', isset($post->region_state) ? $post->region_state: ''), 
                                            ['class' => $errors->has('region_state') ? 'form-control is-invalid' : 
                                                'form-control', 'readonly' => 'readonly']) !!} 
                                                {!! $errors->first('region_state', '
                                                    <div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <div><label for="published_yes" class="col-xs-12">{{ __('Status') }}</label></div>
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('status', 0, (!isset($post->status) || $post->status == 0 ? true : false ), ['id' => 'published_no', 'class'
                                            => 'form-check-input']) }}
                                            <label for="published_no" class="form-check-label">Pending</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('status', 1, (isset($post->status) && $post->status == 1 ? true : false ), ['id' => 'published_yes',
                                            'class' => 'form-check-input']) }}
                                            <label for="published_yes" class="form-check-label">Closed</label>
                                        </div>

                                        {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        @if (auth()->user()->can('add_contact') || auth()->user()->can('edit_contact'))
                                            <button class="btn btn-primary btn-md" type="submit" name="btnSave" value="1">
                                                {{ __('Save') }}
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.contact.index') }}"
                                            class="btn btn-outline-dark btn-md">{{ ('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END Default Elements -->
            </div>
        </div>
    </section>
</div>
@stop
