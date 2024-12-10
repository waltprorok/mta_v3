@component('mail::message')

@component('mail::panel')

{{ 'Lesson for ' . $lesson['title'] . ' has been ' . $lesson['status'] . ' on this date: '
    . \Carbon\Carbon::parse($lesson->start_date)->format('D, M d, Y g:i')
    . ' - ' . \Carbon\Carbon::parse($lesson->end_date)->format('g:i a') . '.' }}

<br />

{{ config('app.name') }}

@endcomponent

@endcomponent
