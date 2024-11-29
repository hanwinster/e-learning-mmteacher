@extends('frontend.layouts.default')
@section('title', __('Contact Us'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
    @include('frontend.resource.partials.search')
    </header>
</div>
@endsection

@section('content')
<main class="main-content">
    <section id="section-mission" class="mt-5">
        <div class="container">


            <div class="row gap-y align-items-center">

                <div class="col-md-7 mx-auto">
                    <h1 class="mb-5">{{ __('Contact Us') }}</h1>

                    @if (isset($post->body))
                    <div>
                        {!! Blade::compileString($post->body) !!}
                    </div>
                    @endif

                    <form method="post" action="{{ route('contact-us.post') }}" class="{{ $errors->any() ? 'was-validated' : '' }}">
                                            {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name" class="require">@lang('Name')</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" value="{{ old('name') }}"
                            placeholder=""> {!! $errors->first('name', '
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>

                    <div class="form-group">
                        <label for="email" class="require">@lang('Email')</label>
                        <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" value="{{ old('email') }}"
                            placeholder=""> {!! $errors->first('email','
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>

                    <div class="form-group">
                        <label for="phone_no" class="require">@lang('Phone Number')</label>
                        <input type="text" class="form-control{{ $errors->has('phone_no') ? ' is-invalid' : '' }}" name="phone_no" id="phone_no"
                            value="{{ old('phone_no') }}" placeholder=""> {!! $errors->first('phone_no', '
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>

                    <div class="form-group">
                        <label for="organization" class="require">@lang('Organization/ Company')</label>
                        <input type="text" class="form-control{{ $errors->has('organization') ? ' is-invalid' : '' }}" name="organization" id="organization"
                            value="{{ old('organization') }}" placeholder=""> {!! $errors->first('organization', '
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>

                    <div class="form-group">
                        <label for="region_state" class="require">@lang('Regions/States')</label> {!! Form::select('region_state', \App\Models\Contact::REGIONS_STATES,
                        old('region_state'), ['class' => $errors->has('region_state') ? 'form-control is-invalid' : 'form-control']) !!}
                        {!! $errors->first('region_state', '
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>

                    <div class="form-group">
                        <label for="subject" class="require">@lang('Subject')</label>
                        <input type="text" class="form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" name="subject" id="subject" value="{{ old('subject') }}"
                            placeholder=""> {!! $errors->first('subject', '
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>

                    <div class="form-group">
                        <label for="message" class="require">@lang('Message')</label>
                        <textarea class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" name="message" id="message" rows="5">{{ old('message') }}</textarea>        {!! $errors->first('message', '
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>

                    <div class="form-group">

                        <div class="g-recaptcha" data-sitekey="6Lfcf_ocAAAAAIUINvtZfbmhRvMkQfpUQrl3SCZ4"></div>

                        {!! $errors->first('g-recaptcha-response', '
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>

                    {{--
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="g-recaptcha" data-sitekey="6Lfcf_ocAAAAAIUINvtZfbmhRvMkQfpUQrl3SCZ4"></div>
                            @if ($errors->has('g-recaptcha-response'))
                            <span class="invalid-feedback" style="display: block;">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span> @endif
                        </div>
                    </div>--}}


                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">{{ __('Send') }}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('script')
@parent
<script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
