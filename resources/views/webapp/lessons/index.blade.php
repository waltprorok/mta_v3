@extends('layouts.webapp')
@section('title', 'Lessons')
@section('content')

    <div class="col-12">
        <h4>Lessons</h4>
        <ul class="breadcrumb">
            @if(! Auth::user()->isAdmin())
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                <li class="breadcrumb-item active">Lessons</li>
            @endif
        </ul>

        <div id="app">
            <lessons></lessons>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
