@component('mail::message')

## New {{ $support ? 'Support' : 'Contact' }} Message

@component('mail::panel')
<b>{{ $name }}</b><br/>
{{ now()->format('M d, Y, h:iA') }}

{{ $subject }}<br/>
{{ $message }}
@endcomponent

@component('mail::button', ['url' => route('contact.index')])
    Reply to this Email
@endcomponent

@endcomponent
