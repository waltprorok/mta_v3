@extends('layouts.webapp')
@section('title', 'Admin Users')
@section('content')

    <div class="col-12">
        <h4>Users</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"></li>
        </ul>

        <div id="app">
            <users></users>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
