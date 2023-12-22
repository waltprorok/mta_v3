@component('mail::message')

# Your subscription has been cancelled

@component('mail::panel')

Hello {{ $user['first_name'] }},

Thank you for choosing {{ config('app.name') }}!

This message is a confirmation of recent changes to your account.

Your subscription to {{ config('app.name') }} has been cancelled.

@endcomponent

@endcomponent
