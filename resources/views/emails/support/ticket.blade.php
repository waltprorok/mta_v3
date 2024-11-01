@component('mail::message')

Thank you for reaching out to our support team!
A human will assist you as soon as possible.

Future replies will occur through email.

You can expect a response within 2 business days.
Please whitelist Musicteachersaid.com to keep our response from being captured as S.P.A.M.

We look forward to helping you!

<hr style="border-bottom: dotted 1px #000" />

<b>{{ $name }}</b><br/>
{{ now()->format('M d, Y, h:iA') }}

@component('mail::panel')
{{ $subject }}<br/>
{{ $message }}
@endcomponent

@endcomponent
