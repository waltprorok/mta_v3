@extends('layouts.webapp')
@section('title', 'Invoices')
@section('content')

    <div class="col-md-12">
        <h2>Invoices</h2>
        <div class="card">
            <table class="table" id="myTable">
                <thead class="thead-dark">
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
                        <td><a href="/account/invoices/{{ $invoice->id }}">Download</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
