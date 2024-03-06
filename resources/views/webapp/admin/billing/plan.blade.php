@extends('layouts.webapp')
@section('title', 'Plans')
@section('content')

    <div class="col-12">
        <h4>Billing</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Plans</li>
        </ul>

        <div id="app">
            <plans></plans>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
