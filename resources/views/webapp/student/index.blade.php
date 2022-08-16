@extends('layouts.webapp')
@section('title', 'Students')
@section('content')

    <div class="col-12">
        <h4>Students</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Students</li>
        </ul>
        <div id="app">
            <student-list></student-list>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </div>

@endsection
