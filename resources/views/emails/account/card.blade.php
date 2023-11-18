@component('mail::message')

# Your Credit Card on file was recently updated

@component('mail::panel')

Hello {{ $user['first_name'] }},

Thank you for choosing {{ config('app.name') }}!

This message is a confirmation of recent changes to your account.

Your credit card on file was recently changed to:

* Brand: **{{ $user['card_brand'] }}**

* Last 4 digits: **{{ $user['card_last_four'] }}**

@endcomponent

**{{ config('app.name') }}**
@endcomponent
