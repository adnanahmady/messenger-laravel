@component('mail::message')
# {{ ucfirst(__('thank you for trusting us')) }}

{!! ucfirst(__("wellcome to <strong>:app-name</strong>", [
    'app-name' => config('app.name')
])) !!}
{{ $user->email }}

{{ ucfirst(
    __('your otp for login / register to your account is :otp', ['otp' => $user->otp])
) }}

@component('mail::button', ['url' => config('front.verify.user')])
{{ ucfirst(__('go back to :app-name', ['app-name' => config('app.name')])) }}
@endcomponent
@endcomponent
