@extends('layouts.webapp')
@section('title', 'Student Profile')
@section('content')

    <div class="col-12">
        <h4>Student Profile</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ul>

        @include('partials.studentTabs', $data = ['id' => $student->id])

        <div id="app">
            <profile v-bind:student="{{ $studentJson }}"></profile>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
