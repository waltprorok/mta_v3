@component('mail::message')

@component('mail::panel')

Hello {{ $user['first_name'] }},

Your subscription to {{ config('app.name') }} has been cancelled.

This message is a confirmation of recent changes to your account.

Thanks!
{{ config('app.name') }}

@endcomponent

@endcomponent
