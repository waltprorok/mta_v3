@component('mail::message')

## New {{ $support ? 'Support' : 'Contact' }} Message

### {{ $subject }}

@component('mail::panel')
    {{ $message }}
@endcomponent

@component('mail::button', ['url' => route('contact.index')])
    Reply to this Email
@endcomponent

@endcomponent
