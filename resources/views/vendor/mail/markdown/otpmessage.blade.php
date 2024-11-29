@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    Your OTP code is {{ $user->verification_code }}. 
    Please copy and paste it in the text box 
    <a href="{{ route('auth.verify.get_otp', ['id' => $user->id]) }}">
        {{ route('auth.verify.get_otp', ['id' => $user->id]) }}
    </a> to verify your account.

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent
