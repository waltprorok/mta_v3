@component('mail::message')
## New {{ ucfirst($type) }} Message

@component('mail::panel')
<b>{{ $data->name }}</b><br/>
{{ now()->format('M d, Y | g:i a') }}

<strong>Subject:</strong> {{ $data->subject }}

{{ $data->message }}
@endcomponent

@if(!empty($data->support))
@component('mail::button', ['url' => route('admin.support.index')])
    Reply to this Email
@endcomponent
@else
@component('mail::button', ['url' => route('contact.index')])
    Reply to this Email
@endcomponent
@endif

@endcomponent
