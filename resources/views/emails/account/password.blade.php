@component('mail::message')

@component('mail::panel')

Hello {{ $user['first_name'] }},

Your {{ config('app.name') }} account's password was recently changed.

This message is a confirmation of recent changes to your account.

If you did not change it, please reset the password to protect your account.

@component('mail::button', ['url' => url('/password/reset')])
    Reset Password
@endcomponent

Thanks!

{{ config('app.name') }}

@endcomponent

@endcomponent


