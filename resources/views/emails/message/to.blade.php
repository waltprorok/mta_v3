@component('mail::message')

<h2>Incoming Messaging:</h2>

<p>Hello {{$user['first_name']}},</p>

{{$request['message']}}

@endcomponent
