@component('mail::message')

# New Contact Message

### {{ $subject }}

@component('mail::panel')
    {{ $message }}
@endcomponent

@component('mail::button', ['url' => route('contact.index')])
    Reply to this Email
@endcomponent

**{{ config('app.name') }}**

@endcomponent
