@component('mail::message')

@component('mail::panel')

Hello {{$user['first_name']}},

{!! $request['body'] !!}

@endcomponent

@endcomponent
