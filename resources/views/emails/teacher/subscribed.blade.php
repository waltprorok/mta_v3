@component('mail::message')

@component('mail::panel')

Hello {{ $user['first_name'] }},

Thank you for your purchase of the **Premium** Subscription with Music Teachers Aid.

Now you can enjoy all the benefits of the Premium Account:

* Unlimited Students
* Lesson Scheduling Email Notifications
* Auto Schedule Student Lessons Each Month
* Invoicing and Payment Tracking

Under Account click Subscription Tab click **Download Invoices** to view the PDF invoice.

@component('mail::button', ['url' => route('support')])
    Support
@endcomponent

Happy Teaching!

{{ config('app.name') }}

@endcomponent

@endcomponent
