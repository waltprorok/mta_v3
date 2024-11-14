@component('mail::message')

@component('mail::panel')

Greetings {{ $student->first_name }},

Your teacher {{ $teacher->first_name }} {{ $teacher->last_name }} has scheduled your {{ $student->instrument }} lessons for the following day(s).

<ul>
@foreach($lessons as $lesson)
    @if (isset($lesson['all_day']) && $lesson['all_day'] == true)
    <li>Closed - {{ date('l M d, Y', strtotime($lesson['start_date'])) }} | {{ $lesson['title'] }}</li>
    @else
    <li> {{ date('l M d, Y | g:ia', strtotime($lesson->start_date)) }} - {{ date('g:ia', strtotime($lesson->end_date)) }}</li>
    @endif
@endforeach
</ul>

@if($student->at_studio)
<strong>Where:</strong><br />
{{ $teacher->studio_name }} <br />
{{ $teacher->address }} {{ $teacher->address_2 }} <br />
{{ $teacher->city }}, {{ $teacher->state }} {{ $teacher->zip }}
@endif

@if($student->at_home)
<strong>Where:</strong><br />
{{ $student->address }} {{ $student->address_2 }} <br />
{{ $student->city }}, {{ $student->state }} {{ $student->zip }}
@endif


Phone: {{ $teacher->phone }} <br />
Email: {{ $teacher->email }}

@component('mail::button', ['url' => route('support')])
    Support
@endcomponent

Thanks!

{{ config('app.name') }}

@endcomponent

@endcomponent
