@extends('layouts.webapp')
@section('title', 'Calendar')
@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css"/>

    <div class="col-12">
        <h4>Calendar</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('calendar.index') }}">Calendar</a></li>
        </ul>

        <div class="card">
            <div class="card-body"

                    {!! $calendar->calendar() !!}

                    {!! $calendar->script() !!}

            </div>
        </div>
    </div>

@endsection
