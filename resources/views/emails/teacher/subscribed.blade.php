@component('mail::message')
# Music Teachers Aid | Premium Subscription Email

<p>Hello {{$user['first_name']}},</p>

<p>Thank you for your purchase of the Premium Subscription with Music Teachers Aid.</p>

<p>Now you can enjoy all the benefits of the Premium Account</p>

<ul>
    <li>Unlimited Students</li>
    <li>Scheduling Notifications</li>
    <li>Automatic Payments</li>
</ul>

<p>Under Account click Subscription Tab click <b>Download Invoices</b> to view the PDF invoice.</p>

@component('mail::button', ['url' => route('contact')])
    Support
@endcomponent

Happy Teaching,<br>
{{ config('app.name') }}
@endcomponent
