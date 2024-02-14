@component('mail::message')

@component('mail::panel')

Hello {{ $user['first_name'] }},

Your email was recently changed to **{{ $user['email'] }}**

This message is a confirmation of recent changes to your account.

Thanks!
- {{ config('app.name') }}

@endcomponent

@endcomponent
