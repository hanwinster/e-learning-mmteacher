<!-- Contact Section-->
<section class="page-section bg-secondary text-white d-none" id="contact">
    <div class="container">
        <!-- Contact Section Heading-->
        <h2 class="page-section-heading text-center text-white mb-0">
            {{__('Contact us') }}  
        </h2>
        <!-- <divider color="text-white" bg-color="bg-white"></divider> -->
        <!-- Contact Section Form-->
        <div class="row justify-content-center">
            <div class="col-lg-4 col-xl-5">
                @if (isset($contact->body))
                {!! Blade::compileString($contact->body) !!}
                @endif
            </div>
            <div class="col-lg-8 col-xl-7">
                <form id="contactForm" action="{{ route('contact-us.post') }}" 
                    method="post" class="{{ $errors->any() ? 'was-validated' : '' }}">
                    <!-- data-sb-form-api-token="API_TOKEN" -->
                    {{ csrf_field() }}
                    <!-- Name input-->
                    <div class="form-floating mb-2">
                        <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" 
                            value="{{ old('name') }}" placeholder="{{__('Enter your name...') }}"/>
                        <label for="name">{{__('Name') }}<span class="text-red">*</span></label>
                        <div class="invalid-feedback">
                            {{__('Name is required.') }}
                        </div>
                    </div>
                    <!-- Email address input-->
                    <div class="form-floating mb-2">
                        <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                            name="email" id="email" value="{{ old('email') }}" 
                            placeholder="name@example.com" data-sb-validations="required,email" />
                        <label for="email">{{__('Email Address') }}<span class="text-red">*</span></label>
                        <div class="invalid-feedback" data-sb-feedback="email:required">
                            {{__('An email is required.') }}
                        </div>
                        <div class="invalid-feedback" data-sb-feedback="email:email">
                            {{__('Email is not valid.') }}
                        </div>
                    </div>
                    <!-- Phone number input-->
                    <div class="form-floating mb-2">
                        <input class="form-control" type="text" 
                            class="form-control {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" 
                            name="phone_no" id="phone_no" value="{{ old('phone_no') }}" placeholder="(123) 456-7890"/>
                        <label for="phone">{{__('Mobile No.') }}</label>
                        <div class="invalid-feedback" data-sb-feedback="phone:required">
                            {{__('A mobile number is required.') }}
                        </div>
                    </div>
                    <!-- Message input-->
                    <div class="form-floating mb-2">
                        <textarea class="form-control t-area" id="message" type="text" 
                            placeholder="{{__('Enter your message here...') }}">
                        </textarea>
                        <label for="message">{{__('Message') }}</label>
                        <div class="invalid-feedback" data-sb-feedback="message:required">
                            {{__('A message is required.') }}
                        </div> 
                    </div>
                    <div class="form-group mb-2">
                        <div class="g-recaptcha" data-sitekey="6Lf9C78eAAAAAIq8pjr3Lw7k-bhFXBOiAVi_-aj5"></div>
                            {!! $errors->first('g-recaptcha-response', '
                        <div class="invalid-feedback">:message</div>') !!}
                    </div>
                    <!-- Submit Button-->
                    <button class="btn btn-outline-primary btn-lg" id="submitButton" type="submit">
                        {{__('Send') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@section('script')
@parent
<script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
