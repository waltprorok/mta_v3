@component('mail::message')

# Changes Have Been Made To Your Account

@component('mail::panel')

Hello {{ $user['first_name'] }},

Thank you for choosing {{ config('app.name') }}!

This message is a confirmation of recent changes to your account.

Your password was recently changed.

If you did not change it, please reset the password to protect your account.

@component('mail::button', ['url' => url('/password/reset')])
    Reset Password
@endcomponent

**{{ config('app.name') }}**

@endcomponent

@endcomponent
