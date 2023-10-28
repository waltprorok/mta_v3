@component('mail::message')

# Welcome to Music Teachers Aid

Greetings {{$user['first_name']}},

You have been added to this service by your music teacher.

{{ config('app.name') }} is a software to allows your teacher help keep better track of lessons, scheduling, and payments.

Your registered email is **{{$user['email']}}**

To login click the button

@component('mail::button', ['url' => url('/password/reset')])
    Update Password
@endcomponent

If you have any questions or concerns please use the support link after you log in under your profile.

@component('mail::button', ['url' => route('contact')])
    Support
@endcomponent


Thanks,<br/>
{{ config('app.name') }}

@endcomponent
