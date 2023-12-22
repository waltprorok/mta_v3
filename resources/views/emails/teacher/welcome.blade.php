@component('mail::message')

# Welcome to Music Teachers Aid

@component('mail::panel')

Greetings {{$user['first_name']}},

We are excited that you signed up for Music Teachers Aid.

Music Teachers Aid is built by a former music teacher and Berklee College of Music attendee.

We hope that you love our software as much as we do.

If you have any questions or concerns please use the support link after you log in under your profile.

@component('mail::button', ['url' => route('contact')])
    Support
@endcomponent

Your registered email is **{{$user['email']}}**

To unlock the full potential of the software please sign up for a <a href="{{ route('account.subscription') }}" target="_blank">Premium Account</a> to unlock all the system features.

@component('mail::button', ['url' => route('account.subscription')])
Get a Premium Account
@endcomponent

Thanks,<br/>
{{ config('app.name') }}

@endcomponent

@endcomponent
