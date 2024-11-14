@extends('layouts.webapp')
@section('title', 'Student')
@section('content')

    <div class="col-12">
        <h4>Schedule Student</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item active">Schedule</li>
        </ul>

        @include('partials.studentTabs', $data = ['id' => $student->id])

        <div id="app">
            <lesson></lesson>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
