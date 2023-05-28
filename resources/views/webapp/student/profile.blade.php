@extends('layouts.webapp')
@section('title', 'Student Profile')
@section('content')

    <div class="col-12">
        <h4>Student Profile</h4>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ul>

            <div id="app">
                <profile></profile>
            </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

@endsection
