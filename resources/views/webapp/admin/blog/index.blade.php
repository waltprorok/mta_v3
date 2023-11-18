@extends('layouts.webapp')
@section('title', 'Admin Blog')
@section('content')

    <div class="col-12">
        <h4>Blog Posts</h4>
        <ul class="breadcrumb">
            {{--            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>--}}
            {{--            <li class="breadcrumb-item">Contacts</li>--}}
        </ul>
        <div id="app">
            <blogs></blogs>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
