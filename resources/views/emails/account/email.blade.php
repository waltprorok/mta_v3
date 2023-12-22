@component('mail::message')

# Changes Have Been Made To Your Account

@component('mail::panel')

Hello {{ $user['first_name'] }},

Thank you for choosing {{ config('app.name') }}!

This message is a confirmation of recent changes to your account.

Your email was recently changed to **{{ $user['email'] }}**

@endcomponent

@endcomponent
