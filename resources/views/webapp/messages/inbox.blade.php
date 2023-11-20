@extends('layouts.webapp')
@section('title', 'Messages')
@section('content')

    <div class="col-12">
        <h4>Messages</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Inbox</li>
        </ul>

        <div id="app">
            <inbox></inbox>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
