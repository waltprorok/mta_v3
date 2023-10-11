@extends('layouts.webapp')
@section('title', 'Lessons')
@section('content')

    <div class="col-12">
        <h4>Lessons</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Students</a></li>
            <li class="breadcrumb-item active">Lessons</li>
        </ul>

        <div id="app">
            <lessons></lessons>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

@endsection
