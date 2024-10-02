@extends('layouts.webapp')
@section('title', 'Billing Rates')
@section('content')

    <div class="col-12">
        <h4>Studio Settings</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Billing Rates</li>
        </ul>
        @include('partials.teacherTabs')

        <div id="app">
            <billing-rate></billing-rate>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
