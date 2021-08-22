@extends('layouts.webapp')
@section('title', 'Invoices')
@section('content')

    <div class="col-md-12">
        <h4>Invoices</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Profile</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('account.subscription') }}">Subscription</a></li>
        </ul>

        @include('partials.accountTabs')
        <div class="card-header bg-light">Download Invoices</div>
        <div class="card">
            <table class="table table-striped">
                <thead class="thead">
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                        <td>{{ $invoice->total() }}</td>
                        <td><a href="{{ route('subscription.invoice', $invoice->id) }}" class="btn btn-sm btn-outline-primary">Download</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <hr/>
            <div class="card-body">
                <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>

@endsection
