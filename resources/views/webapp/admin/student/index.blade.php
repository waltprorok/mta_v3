@extends('layouts.webapp')
@section('title', 'Admin Students')
@section('content')

    <div class="col-12">
        <h4>Students</h4>
        <ul class="breadcrumb">
            {{--            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>--}}
            {{--            <li class="breadcrumb-item">Teachers</li>--}}
        </ul>

        <div id="app">
            <students></students>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

@endsection
