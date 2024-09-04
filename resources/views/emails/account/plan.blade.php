@component('mail::message')

@component('mail::panel')

Hello {{ $user['first_name'] }},

Your subscription plan to {{ config('app.name') }} has changed to a **{{ ucfirst($plan->slug) }}** plan at **${{ $plan->cost }}**.

This message is a confirmation of recent changes to your account.

Thanks!
{{ config('app.name') }}

@endcomponent

@endcomponent
