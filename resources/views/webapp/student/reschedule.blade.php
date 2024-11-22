@extends('layouts.webapp')
@section('title', 'Schedule Student')
@section('content')

    <div class="col-12">
        <h4>Edit Student Lesson</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item"><a href="{{ route('calendar.index') }}">Calendar</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ul>

        @include('partials.studentTabs', $data = ['id' => $student->id])

        <div id="app">
            <reschedule></reschedule>
        </div>


        <script src="{{ mix('js/app.js') }}"></script>
    </div>

@endsection
