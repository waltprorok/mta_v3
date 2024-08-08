@component('mail::message')

@component('mail::panel')

Dear {{ $invoice['student']['first_name'] }},

Thank you for your business.

Attached you will find our invoice {{ 'Invoice_MTA_' . $invoice['id'] }}

@component('mail::button', ['url' => route('contact')])
    Support
@endcomponent

Thanks!<br/>
- {{ config('app.name') }}

@endcomponent

@endcomponent

