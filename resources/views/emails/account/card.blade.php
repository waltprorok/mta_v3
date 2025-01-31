@component('mail::message')

@component('mail::panel')

Hello {{ $user['first_name'] }},

Your credit card on file was recently changed to:

* Brand: **{{ $user['pm_type'] }}**
* Last 4 digits: **{{ $user['pm_last_four'] }}**

This message is a confirmation of recent changes to your account.

Thanks!

{{ config('app.name') }}

@endcomponent

@endcomponent
