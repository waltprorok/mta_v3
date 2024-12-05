@extends('layouts.webapp')
@section('title', 'Schedule Student')
@section('content')

    <div class="col-12">
        <h4>Student Lesson</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ Auth::user()->isParent() ? route('parent.calendar') : route('student.calendar') }}">Calendar</a></li>
            <li class="breadcrumb-item active">Lesson</li>

        </ul>

        <div id="app">
            <lesson-cancelled></lesson-cancelled>
        </div>

        <script src="{{ mix('js/app.js') }}"></script>
    </div>

@endsection
