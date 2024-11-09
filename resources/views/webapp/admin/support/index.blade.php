@extends('layouts.webapp')
@section('title', 'Admin Support')
@section('content')

    <div class="col-12">
        <h4>Support</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"></li>
        </ul>

        <div id="app">
            <support></support>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
