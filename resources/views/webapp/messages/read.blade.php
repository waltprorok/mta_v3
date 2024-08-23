@extends('layouts.webapp')
@section('title', 'Read Message')
@section('content')

    <div class="col-12">
        <h4>Messages</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('message.inbox') }}">Messages</a></li>
            <li class="breadcrumb-item active">Read</li>
        </ul>

        <div id="app">
            <read></read>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
