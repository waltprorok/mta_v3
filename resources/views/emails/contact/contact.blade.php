@component('mail::message')

## New {{ $support ? 'Support' : 'Contact' }} Message

@component('mail::panel')
<b>{{ $name }}</b><br/>
{{ now()->format('M d, Y, h:iA') }}

{{ $subject }}

{{ $message }}
@endcomponent

@if($support)
@component('mail::button', ['url' => route('admin.support.index')])
    Reply to this Email
@endcomponent
@else
@component('mail::button', ['url' => route('contact.index')])
    Reply to this Email
@endcomponent
@endif

{{--@if(! $support)--}}
{{--@component('mail::button', ['url' => route('contact.index')])--}}
{{--    Reply to this Email--}}
{{--@endcomponent--}}
{{--@endif--}}

@endcomponent
