@extends('backend.layouts.default')

@section('title', __('User'))

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('Users') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (isset($post->id))
                                {{ __('Edit User') }}
                            @else 
                                {{ __('Add User') }}
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
                <div class="col-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4 class="card-title">
                                @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                            </h4>
                        </div>

                        <div class="card-body">
                            @if (isset($post))
                            {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.user.update',
                            $post->id), 'class' => 'form-horizontal')) !!}
                            @else
                            {!! \Form::open(array('files' => true, 'route' => 'admin.user.store', 'class' => 'form-horizontal'))
                            !!}
                            @endif
                            {{ csrf_field() }}
                            @php 
                                $isAdmin = isset($post->id) && $post->type == 'admin' ? true : false;
                                $isManager = isset($post->id) && $post->type == 'manager' ? true : false;
                                $isTeacher = isset($post->id) && $post->type == 'teacher_educator' ? true : false;
                                $isStudent = isset($post->id) && $post->type == 'student_teacher' ? true : false;
                                $isJournalist = isset($post->id) && $post->type == 'journalist' ? true : false;
                                $isLearner = isset($post->id) && $post->type == 'independent_learner' ? true : false;
                            @endphp
                            <div class="row">
                                <div class="col-sm-8">

                                    <div class="form-group">
                                        <label for="name" class="col-xs-12">@lang('Name')&nbsp;
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" placeholder="Name.." name="name" id="name"
                                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="username" class="col-xs-12">@lang('Username')&nbsp;
                                            <span class="required">*</span></label>
                                        <input type="text" placeholder="Username.." name="username" id="username"
                                            class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                            value="{{ old('username', isset($post->username) ? $post->username : '') }}">
                                        {!! $errors->first('username', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-xs-12">@lang('Email')&nbsp;
                                            <span class="required">*</span></label>
                                        <input type="text" placeholder="Email.." name="email" id="email"
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            value="{{ old('email', isset($post->email) ? $post->email: '') }}">
                                        {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="mobile_no">@lang('Mobile No.')
                                            <!-- <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                                data-placement="top"
                                                data-original-title="Mobile number starting with Country code. e.g., 
                                                Please enter +959123456789 for 09123456789"></i> -->
                                        </label>

                                        <input type="text" placeholder="Mobile No..." name="mobile_no" id="mobile_no"
                                            class="form-control{{ $errors->has('mobile_no') ? ' is-invalid' : '' }}"
                                            value="{{ old('mobile_no', isset($post->mobile_no) ? $post->mobile_no : '') }}">
                                        {!! $errors->first('mobile_no', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group {!! $errors->first('account_type', 'has-error') !!}">
                                        <label for="user_type" class="col-xs-12">@lang('Account Type')
                                            <span class="required">*</span>
                                        </label>
                                        @php 
                                            $accSelected = ''; $accSelectedVal = 0;
                                            if(isset($post->account_type)) {
                                                switch($post->account_type) {
                                                    case 1: $accSelected = 'Teacher'; $accSelectedVal = 1;break;
                                                    case 2: $accSelected = 'Learner'; $accSelectedVal = 2;break;
                                                    case 3: $accSelected = 'Manager'; $accSelectedVal = 3;break;
                                                    case 4: $accSelected = 'Admin'; $accSelectedVal = 4; break;
                                                    default: $accSelected = 'Select Account Type'; $accSelectedVal = 0;break;
                                                }
                                            }                                        
                                        @endphp
                                        <select class="form-control" id="account-type_adm" name="account_type" required>
                                            <option value="{{$accSelectedVal }}" selected>{{ __($accSelected) }}</option> 
                                            <option value="1">{{ __('Teacher') }}</option>
                                            <option value="2">{{ __('Learner') }}</option>
                                            <option value="3">{{ __('Manager') }}</option>
                                            <option value="4">{{ __('Admin') }}</option>
                                        </select>
                                        {!! $errors->first('account_type', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                   @php 
                                        $edcTypes = \App\Repositories\UserRepository::getTeacherUserTypes();
                                        $isUserTypeEdc =  isset($post) && in_array($post->user_type , array_keys($edcTypes)) ? true : false;
                                        // dd(in_array($post->user_type , array_keys($edcTypes)));exit;
                                   @endphp
                                   @if(isset($post))
                                        @if($isUserTypeEdc)
                                            <div class="form-group {!! $errors->first('user_type', 'has-error') !!} user-type-teacher">
                                                <label for="user_type" class="col-xs-12">@lang('Type of EDC Users') <span class="required">*</span></label>
                                                {!! Form::select('user_type', \App\Repositories\UserRepository::getTeacherUserTypes(), 
                                                    old('user_type', isset($post->user_type) ?
                                                    $post->user_type : ''), ['class' => 'form-control user_types_teacher']) !!}
                                                {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                                            </div>
                                        @else
                                            <div class="form-group {!! $errors->first('user_type', 'has-error') !!} user-type-all">
                                                <label for="user_type" class="col-xs-12">@lang('Type of Users') <span class="required">*</span></label>
                                                {!! Form::select('user_type', \App\Repositories\UserRepository::getUserTypes(), 
                                                    old('user_type', isset($post->user_type) ?
                                                    $post->user_type : ''), ['class' => 'form-control user_types_all']) !!}
                                                {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                                            </div>
                                        @endif
                                   @else
                                    <div class="form-group {!! $errors->first('user_type', 'has-error') !!} user-type-teacher">
                                            <label for="user_type" class="col-xs-12">@lang('Type of EDC Users') <span class="required">*</span></label>
                                            {!! Form::select('user_type', \App\Repositories\UserRepository::getTeacherUserTypes(), 
                                                old('user_type', isset($post->user_type) ?
                                                $post->user_type : ''), ['class' => 'form-control user_types_teacher']) !!}
                                            {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>

                                        <div class="form-group {!! $errors->first('user_type', 'has-error') !!} user-type-all d-none">
                                            <label for="user_type" class="col-xs-12">@lang('Type of Users') <span class="required">*</span></label>
                                            {!! Form::select('user_type', \App\Repositories\UserRepository::getUserTypes(), 
                                                old('user_type', isset($post->user_type) ?
                                                $post->user_type : ''), ['class' => 'form-control user_types_all']) !!}
                                            {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>        

                                   @endif
                                    

                                    <div class="form-group ec-div">
                                        <label for="ec_college" class="col-xs-12">@lang('Education College')</label>
                                        {!! Form::select('ec_college', $ec_colleges, old('ec_college', isset($post->ec_college)
                                        ? $post->ec_college : ''), ['class' => $errors->has('ec_college') ? 'form-control
                                        is-invalid' : 'form-control']) !!}
                                        {!! $errors->first('ec_college', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                                                   
                                    <div class="form-group affiliation-div {{!$isJournalist ? 'd-none' : '' }}">
                                        <label for="affiliation" class="col-xs-12">
                                            @lang('Affiliation')&nbsp;<span class="required">*</span>
                                        </label>
                                        <input type="text" placeholder="@lang('Affiliation')" name="affiliation" id="affiliation"
                                            class="form-control{{ $errors->has('affiliation') ? ' is-invalid' : '' }}"
                                            value="{{ old('affiliation', isset($post->affiliation) ? $post->affiliation: '') }}">
                                        {!! $errors->first('affiliation', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group position-div {{!$isJournalist ? 'd-none' : '' }}">
                                        <label for="position" class="col-xs-12">
                                            @lang('Position')&nbsp;<span class="required">*</span>
                                        </label>
                                        <input type="text" placeholder="@lang('Position')" name="position" id="position"
                                            class="form-control{{ $errors->has('affiliation') ? ' is-invalid' : '' }}"
                                            value="{{ old('position', isset($post->position) ? $post->position: '') }}">
                                        {!! $errors->first('position', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group position-div {{ $isLearner || $isAdmin || $isManager  ? '' : 'd-none' }}">
                                        <label for="organization" class="col-xs-12">
                                            @lang('Organization')&nbsp;
                                        </label>
                                        <input type="text" placeholder="@lang('Organization')" name="position" id="position"
                                            class="form-control{{ $errors->has('organization') ? ' is-invalid' : '' }}"
                                            value="{{ old('organization', isset($post->organization) ? $post->organization: '') }}">
                                        {!! $errors->first('organization', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group {!! $errors->first('type', 'has-error') !!}">
                                        <label for="type" class="col-xs-12">@lang('Accessible Right')&nbsp;
                                            <span class="required">*</span></label>
                                        {!! Form::select('type', $privilege_types, old('type', isset($post->type) ? $post->type
                                        : ''), ['class' => $errors->has('type') ? 'form-control is-invalid' : 'form-control'])
                                        !!}
                                        {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <div class="form-group {!! $errors->first('notification_channel', 'has-error') !!}">
                                        <label for="notification_methoad"
                                            class="col-form-label text-md-right">{{ __('Notification Channel') }}</label>

                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" checked="" disabled="" type="checkbox"
                                                name="notification_channel[]" value="email">
                                            <label class="custom-control-label">{{ __('Email') }} (Default)</label>
                                        </div>

                                        <!-- <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="hidden" name="notification_channel[]"
                                                value="email">
                                            <input class="custom-control-input" type="checkbox" name="notification_channel[]"
                                                value="sms"
                                                {{ isset($post->notification_channel) && $post->notification_channel == 'sms' ? 'checked=checked' : '' }}>
                                            <label class="custom-control-label">{{ __('SMS') }}</label>
                                        </div> -->

                                        {!! $errors->first('notification_channel', '<div class="invalid-feedback">:message</div>
                                        ') !!}
                                    </div>

                                @if (isset($post))
                                <div class="alert alert-info">
                                    <i class="fa fa-info"></i> @lang('If you want to reset the password, please type Password and
                                    Confirm Password. If not, please leave the both fields blank')
                                </div>
                                @endif

                                <div class="form-group">
                                    <label for="password" class="col-xs-12">@lang('Password')&nbsp;
                                            <span class="required">*</span></label>
                                    <input type="password" placeholder="Password.." name="password" id="password"
                                        class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" value="">
                                    {!! $errors->first('password', '
                                    <div class="invalid-feedback">:message</div>') !!}
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation" class="col-xs-12">@lang('Confirm Password')&nbsp;
                                            <span class="required">*</span></label>
                                    <input type="password" placeholder="Confirm Password.." name="password_confirmation"
                                        id="password_confirmation" class="form-control" value="">
                                    {!! $errors->first('password_confirmation', '<div class="invalid-feedback">:message</div>')
                                    !!}
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm" type="submit" name="btnSave" value="1">{{ __('Save') }}</button>
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-outline-dark btn-sm">{{ __('Cancel') }}</a>
                                </div>
                            </div>

                            <div class="col-sm-4">
                            <!-- <h5><b>{{ __('Roles') }}</b></h5>
                            <div class='form-group'>
                                @foreach ($roles as $role)

                                    <div class="custom-control custom-radio">

                                        @if (isset($post) && $post->roles)
                                        {{ Form::radio('roles', $role->id, $post->roles->contains('id', $role->id), ['id' => 'role_' . $role->id, 'class' => $errors->has('roles')? 'custom-control-input is-invalid' : 'custom-control-input'] ) }}
                                        @else
                                        {{ Form::radio('roles', $role->id, '', ['id' => 'role_' . $role->id, 'class' => $errors->has('roles')? 'custom-control-input is-invalid' : 'custom-control-input']) }}
                                        @endif
                                        {{ Form::label('role_' . $role->id, $role->name, ['class' => 'custom-control-label']) }}
                                    </div>
                                @endforeach
                            </div> -->
                                <h5>{{__('Gender')}}&nbsp;<span class="text-red">*</span></h5>
                                <div class='form-group'>
                                    
                                
                                        <div class="custom-control custom-radio">                                                                                                                             
                                                    <input class="{{ $errors->has('gender')? 'custom-control-input is-invalid' : 'custom-control-input'}}" 
                                                        type="radio" name="gender" value="male" id="u-male"
                                                    {{ ( isset($post) && $post->gender == 'male') ? 'checked' : ''}}>
                                                    <label for="u-male" class="custom-control-label">
                                                        {{__('Male')}}
                                                    </label>
                                        </div>       
                                        <div class="custom-control custom-radio">           
                                                    <input class="{{ $errors->has('gender')? 'custom-control-input is-invalid' : 'custom-control-input'}}"
                                                    type="radio" name="gender" value="female" id="u-female"
                                                    {{ ( isset($post) && $post->gender == 'female') ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="u-female">
                                                        {{__('Female')}}
                                                    </label>
                                                    </div>       
                                        <div class="custom-control custom-radio">              
                                                    <input class="{{ $errors->has('gender')? 'custom-control-input is-invalid' : 'custom-control-input'}}" 
                                                    type="radio" name="gender" value="others" id="u-others"
                                                    {{ ( isset($post) && $post->gender == 'others') ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="u-others">
                                                        {{__('Others')}}
                                                    </label>
                                        </div>      
                                        {!! $errors->first('gender', '<div class="invalid-feedback">:message</div>')!!}  
                                    
                                </div> 
                                <div class="form-group">
                                    <label for="suitable_for_ec_year" class="col-xs-12">@lang('Year of study/teaching')</label>

                                    @php
                                        $years = \App\Repositories\ResourceRepository::getEducationCollegeYears();
                                    @endphp

                                    @foreach ($years as $key => $year)

                                    <div class="custom-control custom-checkbox">

                                        @if (isset($post))
                                        @php
                                        $ec_years = array();
                                        if($post->suitable_for_ec_year)
                                        {
                                            if (strpos($post->suitable_for_ec_year, ',') !== false) {
                                            $ec_years = explode(',', $post->suitable_for_ec_year);
                                            }
                                            else $ec_years[] = $post->suitable_for_ec_year;
                                            }

                                        @endphp

                                        {{ Form::checkbox('suitable_for_ec_year[]', $key, in_array($key, $ec_years)? true : false, ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')? 'custom-control-input is-invalid' : 'custom-control-input'] ) }}

                                        @else

                                        {{ Form::checkbox('suitable_for_ec_year[]', $key, '', ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')? 'custom-control-input is-invalid' : 'custom-control-input']) }}

                                        @endif

                                        {{ Form::label('ecy_' . $key, $year, ['class' => 'custom-control-label']) }}

                                        {!! $errors->first('suitable_for_ec_year', '<div class="invalid-feedback">:message</div>')
                                        !!}
                                    </div>
                                    @endforeach


                                </div>

                                <div class="subject-div form-group">
                                    <label for="subjects" class="col-xs-12">@lang('Subject(s)/ Learning Area(s) that you
                                        teach')</label>

                                    @foreach ($subjects as $subject)

                                    <div class="custom-control custom-checkbox">

                                        @if (isset($post))

                                        @php
                                        $subjects = array();
                                        if($post->subjects) {

                                        $post_subjects = $post->subjects;
                                        foreach ($post_subjects as $post_subject) {
                                        $subjects[] = $post_subject->id;
                                        }
                                        }
                                        @endphp

                                        {{ Form::checkbox('subjects[]', $subject->id, in_array($subject->id, $subjects)? true : false, ['id' => 'sub_' . $subject->id, 'class' => $errors->has('subjects')? 'custom-control-input is-invalid' : 'custom-control-input'] ) }}

                                        @else

                                        {{ Form::checkbox('subjects[]', $subject->id, '', ['id' => 'sub_' . $subject->id, 'class' => $errors->has('subjects')? 'custom-control-input is-invalid' : 'custom-control-input']) }}

                                        @endif

                                        {{ Form::label('sub_' . $subject->id, $subject->title, ['class' => 'custom-control-label']) }}<br>
                                    </div>
                                    @endforeach

                                    {!! $errors->first('subjects', '<div class="invalid-feedback">:message</div>') !!}
                                </div>

                                <div class="form-group {!! $errors->first('profile_image', 'has-error') !!}">
                                    <div class="form-group col-xs-12">
                                        <label for="published">@lang('Profile Image')</label>

                                        {{ \Form::file('profile_image', ['class' => $errors->has('profile_image') ? 'form-control is-invalid' : 'form-control']) }}

                                        {!! $errors->first('profile_image', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    @if (isset($post))
                                    @php
                                    $images = $post->getMedia('profile'); //optional($post->getMedia('profile')->first()); //
                                    @endphp

                                    <div class="form-group col-xs-12">
                                        @foreach($images as $image)
                                        <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                            <img src="{{ asset($image->getUrl('thumb')) }}">
                                        </a>

                                        <a class="col-md-3" onclick="return confirm('Are you sure you want to delete?')"
                                            href="{{ route('member.media.destroy', $image->id) }}">@lang('Remove')</a>
                                        @endforeach
                                    </div>

                                    @endif
                                </div>
                                <div class="form-group {!! $errors->first('is_unesco_mgr', 'has-error') !!}">
                                        <label class="col-xs-12">@lang('Is UNESCO Manager')?</label>

                                        <div class="custom-control custom-checkbox">
                                            {{ Form::checkbox('is_unesco_mgr', 1, (!empty($post->is_unesco_mgr) ? 1 : 0 ), 
                                                ['id' => 'is-unesco-mgr', 'class' => "custom-control-input"]) }}
                                            <label for="is-unesco-mgr" class="custom-control-label">@lang('UNESCO Manager')</label>
                                        </div>
                                        {!! $errors->first('is_unesco_mgr', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                                @can('verify_user')
                                    <div class="form-group {!! $errors->first('verified', 'has-error') !!}">
                                        <label class="col-xs-12">@lang('Verified')?</label>

                                        <div class="custom-control custom-checkbox">
                                            {{ Form::checkbox('verified', 1, (!empty($post->verified) ? 1 : 0 ), 
                                                ['id' => 'verified', 'class' => "custom-control-input"]) }}
                                            <label for="verified" class="custom-control-label">@lang('Verified')</label>
                                        </div>
                                        {!! $errors->first('verified', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                @endcan
                                
                                @can('approve_user')
                                    <div class="form-group">
                                        <label for="approved" class="col-xs-12 require">{{__('Approval Status')}}</label>
                                        {!! Form::select('approved', $approvalStatus,
                                        old('approved', isset($post->approved) ? $post->approved: ''),
                                        ['placeholder' => '-Approval Status-', 'class' => $errors->has('approved')?
                                        'form-control
                                        is-invalid' : 'form-control' ]) !!}
                                        {!! $errors->first('approved', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                @endcan
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop

@section('js')
@parent
<script>
$(function() {
    //$('.subject-div').hide();
    if($('.user_types_teacher').val() == 'education_college_teaching_staff') {
        $('.subject-div').show();
    } else {
        $('.subject-div').hide();
    }

    $('.user_types_teacher').change(function(){
        if($('.user_types').val() == 'education_college_teaching_staff') {
            $('.subject-div').show();
        } else {
            $('.subject-div').hide();
        }
        });
    });
</script>
@endsection
