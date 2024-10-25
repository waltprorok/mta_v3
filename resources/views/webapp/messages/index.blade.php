@extends('layouts.webapp')
@section('title', 'Messages')
@section('content')

    <div class="col-12">
        @if(Auth::user()->isTeacher())
        <h4>Messages</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Messages</li>
        </ul>
        @endif

        <div id="app">
            <index></index>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
