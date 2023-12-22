@component('mail::message')

# Your subscription plan has changed

@component('mail::panel')

Hello {{ $user['first_name'] }},

Thank you for choosing {{ config('app.name') }}!

This message is a confirmation of recent changes to your account.

Your subscription plan to {{ config('app.name') }} has changed to a **{{ ucfirst($plan->slug) }}** plan at **${{ $plan->cost }}**.

@endcomponent

@endcomponent
