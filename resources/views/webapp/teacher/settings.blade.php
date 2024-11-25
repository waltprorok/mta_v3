@extends('layouts.webapp')
@section('title', 'Settings')
@section('content')

    <div class="col-12">
        <h4>Studio Settings</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Settings</li>
        </ul>
        @include('partials.teacherTabs')

        <div id="app">
            <settings></settings>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
