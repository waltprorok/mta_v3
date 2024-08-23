@extends('layouts.webapp')
@section('title', 'List of Payments')
@section('content')

    <div class="col-12">
        <h4>List of Payments</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Billing</a></li>
            <li class="breadcrumb-item active"><a href="#">List of Payments</a></li>
        </ul>

        <div id="app">
            <list-of-payments></list-of-payments>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
