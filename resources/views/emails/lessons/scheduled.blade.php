@component('mail::message')

@component('mail::panel')

Greetings {{$student->first_name }},

Your teacher {{ $teacher->first_name }} {{ $teacher->last_name }} has scheduled your {{ $student->instrument }} lessons for the following days.

@foreach($lessons as $lesson)
* {{ date('l, F j | g:i a', strtotime($lesson->start_date)) }} to {{ date('g:i a', strtotime($lesson->end_date)) }}
@endforeach

{{ $teacher->studio_name }} <br />
{{ $teacher->address }} {{ $teacher->address_2 }} <br />
{{ $teacher->city }}, {{ $teacher->state }} {{ $teacher->zip }}

Phone: {{ $teacher->phone }} <br />
Email: {{ $teacher->email }}

@component('mail::button', ['url' => route('support')])
    Support
@endcomponent

Thanks!

{{ config('app.name') }}

@endcomponent

@endcomponent
