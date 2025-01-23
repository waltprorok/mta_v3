@extends('layouts.webapp')
@section('title', 'Reports')
@section('content')

    <div class="col-12">
        <h4>Reports</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
            <li class="breadcrumb-item active">Invoice Payments</li>
        </ul>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">Payment Totals</div>
                    <div class="card-body">
                        <div id="app">
                            <report-invoice-payments-bar></report-invoice-payments-bar>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
