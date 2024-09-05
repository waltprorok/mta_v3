@component('mail::message')

@component('mail::panel')

Hello {{$user['first_name']}},

Thank you for your purchase of the **Premium** Subscription with Music Teachers Aid.

Now you can enjoy all the benefits of the Premium Account:

* Unlimited Students
* Scheduling Notifications
* Automatic Payments

Under Account click Subscription Tab click **Download Invoices** to view the PDF invoice.

@component('mail::button', ['url' => route('contact')])
    Support
@endcomponent

Happy Teaching!<br/>
{{ config('app.name') }}

@endcomponent

@endcomponent
