@extends('layouts.webapp')
@section('title', 'Invoices')
@section('content')

    <div class="col-12">
        <h4>Invoices</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Billing</a></li>
            <li class="breadcrumb-item active"><a href="#">Invoices</a></li>
        </ul>

        <div id="app">
            <invoice-list></invoice-list>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
