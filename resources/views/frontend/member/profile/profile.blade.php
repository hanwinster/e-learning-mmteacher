@extends('backend.layouts.default')
@section('title', __('Profile'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Profile') }}</li>
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
                            <h4>{{ __('Profile') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid gap-y">
                                {{--<form method="post" action="{{ route('member.profile.update') }}" class="form-horizontal
                                {{ $errors->any() ? 'was-validated' : '' }}" accept-charset="UTF-8"
                                enctype="multipart/form-data">--}}

                                {!! \Form::open(array('files' => true, 'method' => 'post', 'route' =>
                                array('member.profile.update'),
                                'class' => $errors->any() ? 'form-horizontal was-validated' : 'form-horizontal')) !!}

                                {{-- csrf_field() --}}

                                <div class="row">
                                    <div class="col-12 col-lg-6">

                                        <div class="form-group">
                                            <label for="name" class="col-xs-12 require">@lang('Name')</label>
                                            <input type="text" placeholder="Name.." name="name" id="name"
                                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                value="{{ old('name', isset($post->name) ? $post->name: '') }}">
                                            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>

                                        <div class="form-group">
                                            <label for="username" class="col-xs-12 require">@lang('Username')</label>
                                            <input type="text" placeholder="Username.." name="username" id="username"
                                                class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                                value="{{ old('username', isset($post->username) ? $post->username : '') }}">
                                            {!! $errors->first('username', '<div class="invalid-feedback">:message</div>')
                                            !!}
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="col-xs-12 require">@lang('Email')</label>
                                            <input type="text" placeholder="Email.." name="email" id="email"
                                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                value="{{ old('email', isset($post->email) ? $post->email: '') }}">
                                            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>

                                        <div class="form-group">
                                            <label for="mobile_no" class="require">@lang('Mobile No.')
                                                <span class="tooltip-info text-info" data-provide="tooltip" data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-original-title="Mobile number starting with Country code. e.g., Please enter +959123456789 for 09123456789">
                                                    <i class="fa fa-info-circle"></i>
                                                 </span>
                                            </label>

                                            <input type="text" placeholder="Mobile No..." name="mobile_no" id="mobile_no"
                                                class="form-control{{ $errors->has('mobile_no') ? ' is-invalid' : '' }}"
                                                value="{{ old('mobile_no', isset($post->mobile_no) ? $post->mobile_no : '') }}">
                                            {!! $errors->first('mobile_no', '<div class="invalid-feedback">:message</div>')
                                            !!}
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
                                                <input class="custom-control-input" type="hidden"
                                                    name="notification_channel[]" value="email">
                                                <input class="custom-control-input" type="checkbox"
                                                    name="notification_channel[]" value="sms" {{ isset($post->notification_channel)
                                                && $post->notification_channel == 'sms' ? 'checked=checked' : '' }}>
                                                <label class="custom-control-label">{{ __('SMS') }}</label>
                                            </div> -->

                                            {!! $errors->first('notification_channel', '
                                            <div class="invalid-feedback">:message</div>') !!}
                                        </div>

                                        <!-- @if ($post->type == App\User::TYPE_STUDENT_TEACHER)
                                        <div class="form-group {!! $errors->first('subscribe_to_new_resources', 'has-error') !!}">
                                            <label for="notification_methoad"
                                                class="col-form-label text-md-right">{{ __('Notifications') }}</label>

                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox"
                                                    name="subscribe_to_new_resources" value="1"
                                                    {{ isset($post->subscribe_to_new_resources)
                                                        && $post->subscribe_to_new_resources == 1 ? 'checked=checked' : '' }}
                                                    >
                                                <label class="custom-control-label">{{ __('Subscribe to new resources.') }}</label>
                                            </div>

                                            {!! $errors->first('subscribe_to_new_resources', '
                                            <div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        @endif -->

                                        <div class="form-group">
                                            <div class="form-group col-xs-12">
                                                <label for="published">@lang('Profile Image')</label>
                                                {{ \Form::file('profile_image', ['class' => $errors->has('profile_image')
                                                ? 'is-invalid' : '']) }} {!! $errors->first('profile_image', '
                                                <div class="invalid-feedback">:message</div>') !!}
                                            </div>

                                            @if (isset($post)) @php $images = $post->getMedia('profile');
                                            //optional($post->getMedia('profile')->first()); //
                                            @endphp

                                            <div class="form-group col-xs-12">
                                                @foreach($images as $image)
                                                <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                                    <img src="{{ asset($image->getUrl('thumb')) }}">
                                                </a>

                                                <a class="col-md-3"
                                                    onclick="return confirm('Are you sure you want to delete?')"
                                                    href="{{ route('member.media.destroy', $image->id) }}">@lang('Remove')</a>
                                                @endforeach
                                            </div>

                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <h5>{{ __('Student/Teacher/Staff Information') }}</h5>
                                        <div class="form-group {!! $errors->first('user_type', 'has-error') !!}">
                                            <label for="user_type" class="col-xs-12">{{ __('Type of Users') }}</label>
                                            @if(!$hideUserTypeAndEducationCollege)
                                                {!! Form::select('user_type', $user_types, old('user_type',
                                                isset($post->user_type) ? $post->user_type : ''), ['class' => 'form-control
                                                user_types']) !!}
                                                {!! $errors->first('user_type','<div class="invalid-feedback">:message</div>')
                                                !!}
                                            @else
                                                {!! Form::select('user_type', $user_types, old('user_type',
                                                    isset($post->user_type) ? $post->user_type : ''), ['class' => 'form-control
                                                    user_types', 'disabled' => 'disabled']) !!}
                                                
                                            @endif
                                        </div>
                                        @if(!$hideEc)
                                            <div class="form-group">
                                                <label for="ec_college" class="col-xs-12">{{ __('Education College') }}</label>
                                                @if(!$hideUserTypeAndEducationCollege)
                                                    {!! Form::select('ec_college',
                                                        $ec_colleges, old('ec_college', isset($post->ec_college) ? $post->ec_college :
                                                        ''), ['class' => $errors->has('ec_college')
                                                        ? 'form-control is-invalid' : 'form-control']) !!}
                                                    {!! $errors->first('ec_college', '<div class="invalid-feedback">:message</div>')
                                                    !!}
                                                @else
                                                    {!! Form::select('ec_college',
                                                        $ec_colleges, old('ec_college', isset($post->ec_college) ? $post->ec_college :
                                                        ''), ['class' => $errors->has('ec_college')
                                                        ? 'form-control is-invalid' : 'form-control', 'disabled' => 'disabled']) !!}                                                  
                                                @endif
                                            </div>
                                        
                                            <div class="form-group">
                                                <label for="suitable_for_ec_year" class="col-xs-12">@lang('Year of Study/Teaching')</label>

                                                @php
                                                    $years = \App\Repositories\ResourceRepository::getEducationCollegeYears();
                                                @endphp

                                                @foreach ($years as $key => $year)

                                                <div class="custom-control custom-checkbox">

                                                    @if (isset($post))

                                                    @php
                                                        $ec_years = array();
                                                        if($post->suitable_for_ec_year) {
                                                            if (strpos($post->suitable_for_ec_year, ',') !== false) {
                                                                $ec_years = explode(',', $post->suitable_for_ec_year);
                                                            } else {
                                                                $ec_years[] = $post->suitable_for_ec_year;
                                                            }
                                                        }

                                                    @endphp

                                                    {{ Form::checkbox('suitable_for_ec_year[]', $key, in_array($key, $ec_years)? true : false,
                                                        ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')? 'custom-control-input is-invalid' :
                                                            'custom-control-input'] ) }}

                                                    @else

                                                    {{ Form::checkbox('suitable_for_ec_year[]', $key, '', ['id' => 'ecy_' . $key, 
                                                        'class' => $errors->has('suitable_for_ec_year')? 'custom-control-input is-invalid' : 'custom-control-input']) }}

                                                    @endif

                                                    {{ Form::label('ecy_' . $key, __($year) , ['class' => 'custom-control-label']) }}

                                                    {!! $errors->first('suitable_for_ec_year', '<div class="invalid-feedback">
                                                        :message</div>') !!}
                                                </div>
                                                @endforeach


                                            </div>
                                        @endif

                                        <div class="subject-div form-group" style="display: none">
                                            <label for="subjects" class="col-xs-12">@lang('Subject(s)/ Learning Area(s) that
                                                you teach')</label>


                                            @foreach ($subjects as $subject)
                                            <div class="custom-control custom-checkbox">
                                                @if (isset($post))

                                                @php
                                                $subjects = array();
                                                //dd($post->subjects);
                                                if($post->subjects) {

                                                $post_subjects = $post->subjects;
                                                foreach ($post_subjects as $post_subject) {
                                                $subjects[] = $post_subject->id;
                                                }
                                                }
                                                @endphp

                                                {{ Form::checkbox('subjects[]', $subject->id, in_array($subject->id, $subjects)? true : false, ['id' => 'sub_' . $subject->id, 'class' => $errors->has('subjects')? 'custom-control-input is-invalid' : 'custom-control-input'] ) }}

                                                @else

                                                {{-- Form::checkbox('subjects[]', $subject->id, '', ['id' => 'sub_' . $subject->id]) --}}

                                                @endif

                                                {{ Form::label('sub_' . $subject->id, $subject->title, ['class' => 'custom-control-label']) }}
                                            </div>
                                            @endforeach

                                            {!! $errors->first('subjects', '<div class="invalid-feedback">:message</div>')
                                            !!}
                                        </div>

                                        
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-md">{{ __('Update') }}</button>
                                        <a href="{{ route('member.dashboard') }}" class="btn btn-md btn-outline-dark">{{ __('Cancel') }}</a>
                                    </div>
                                </div>

                            </div>
                            </form>

                        </div>
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
    $(function() { //alert("P");
		//$('.subject-div').hide();
		if($('.user_types').val() == 'education_college_teaching_staff') {
			$('.subject-div').show();
		} else {
			$('.subject-div').hide();
		}

		$('.user_types').change(function(){//alert("K");
			if($('.user_types').val() == 'education_college_teaching_staff') {
				$('.subject-div').show();
			} else {
				$('.subject-div').hide();
			}
		});
	});
</script>
@endsection
