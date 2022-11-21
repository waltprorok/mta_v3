@component('mail::message')
# Welcome to Music Teachers Aid

<p>Hey {{$user['first_name']}},</p>

<p>We are excited that you signed up for Music Teachers Aid.</p>

<p>Music Teachers Aid is built by a former music teacher and Berklee College of Music attendee.</p>

<p>We hope that you love our software as much as we do.</p>

<p>If you have any questions or concerns please use the support link after you log in under your profile.</p>

@component('mail::button', ['url' => route('contact')])
    Support
@endcomponent

<p>Your registered email is <b>{{$user['email']}}</b></p>

<p>To unlock the full potential of the software please sign up for a <a href="{{ route('account.subscription') }}" target="_blank">Premium Account</a> to unlock all the system features.</p>

@component('mail::button', ['url' => route('account.subscription')])
Get a Premium Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
