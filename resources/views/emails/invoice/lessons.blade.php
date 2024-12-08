@component('mail::message')

@component('mail::panel')

Dear {{ $invoice['student']['first_name'] }},

Please find the attached invoice <strong>{{ 'Invoice_MTA_' . $invoice['id'] }}.pdf</strong>

@component('mail::button', ['url' => route('support')])
    Support
@endcomponent

Thank you. As always, we appreciate your business.

{{ config('app.name') }}

@endcomponent

@endcomponent

