@component('mail::message')
# Welcome to Music Teachers Aid

<p>Greetings {{$user['first_name']}},</p>

<p>You have been added to this service by your music teacher.</p>

<p>{{ config('app.name') }} is a software to allows your teacher help keep better track of lessons, scheduling, and payments.</p>

<p>Your registered email is <b>{{$user['email']}}</b></p>

<p>To login click the button</p>

@component('mail::button', ['url' => url('/password/reset')])
    Update Password
@endcomponent

<p>If you have any questions or concerns please use the support link after you log in under your profile.</p>

@component('mail::button', ['url' => route('contact')])
    Support
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
