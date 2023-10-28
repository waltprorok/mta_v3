@component('mail::message')

# Incoming Messaging

@component('mail::panel')

Hello {{$user['first_name']}},

{{$request['message']}}

@endcomponent

@endcomponent
