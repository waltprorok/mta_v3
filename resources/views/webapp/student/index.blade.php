@extends('layouts.webapp')
@section('title', 'Students')
@section('content')

    <div class="col-12">
        <h4>Students</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item active">List</li>
        </ul>

        <div id="app">
            <student-list></student-list>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
