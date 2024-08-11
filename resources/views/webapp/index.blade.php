@extends('layouts.webapp')
@section('title', 'Dashboard')
@section('content')

    <div class="col-12">
        <div id="app">
            <dashboard></dashboard>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
