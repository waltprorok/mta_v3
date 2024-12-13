<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="{{ public_path('/webapp/css/pdf.css') }}" type="text/css" media="all"/>
</head>
<body>
<table class="w-full">
    <tr>
        <td class="w-half">
            @if ($invoice->student->getTeacher->logo)
                <img src="{{ public_path('/storage/teacher/'. $invoice->student->getTeacher->logo) }}" alt="business logo" width="180px">
            @else
                <img src="{{ public_path('webapp/img/logo-mta1.png') }}" alt="mta logo">
            @endif
        </td>
        <td class="w-half">
            <h4 style="text-align: right;">Invoice: {{ $invoice->id }}</h4>
            <div style="text-align: right;">Issue Date: {{ $invoice->created_at->format('m/d/Y') }}</div>
            <div style="text-align: right;">Due Date: {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('m/d/Y') : $invoice->created_at->endOfMonth()->format('m/d/Y') }}</div>
            <div style="text-align: right;">Status: {{ $invoice->is_paid ? 'Paid' : 'Not Paid' }}</div>
        </td>
    </tr>
</table>

<div class="margin-top">
    <table class="w-full">
        <tr>
            <th style="text-align: left">Billed By:</th>
            <th style="text-align: left">Billed To:</th>
        </tr>
        <tr>
            <td class="w-half">
                <br>
                <div><h4>{{ $invoice->student->getTeacher->studio_name }}</h4></div>
                <div>{{ $invoice->student->getTeacher->first_name }}&nbsp;{{ $invoice->student->getTeacher->last_name }}</div>
                <div>{{ $invoice->student->getTeacher->address }}</div>
                <div>{{ $invoice->student->getTeacher->city }}, {{ $invoice->student->getTeacher->state }} {{ $invoice->student->getTeacher->zip }}</div>
                <br>
                <div>{{ $invoice->student->getTeacher->email }}</div>
            </td>

            <td class="w-half">
                @if ($invoice->student->parent)
                    <div>{{ $invoice->student->parent->first_name }} {{ $invoice->student->parent->last_name }}</div>
                @else
                    <div>{{ $invoice->student->first_name }}&nbsp;{{ $invoice->student->last_name }}</div>
                @endif
                @if ($invoice->student->address)
                    <div>{{ $invoice->student->address }}</div>
                    <div>{{ $invoice->student->city }}, {{ $invoice->student->state }} {{ $invoice->student->zip }}</div>
                @endif
                <br>
                <div>{{ $invoice->student->email }}</div>
                @if ($invoice->student->parent)
                    <div>{{ $invoice->student->parent->email }} </div>
                @endif
            </td>
        </tr>
    </table>
</div>

<hr>

<div class="margin-top">
    <table class="products">
        <tr>
            <th>Status</th>
            <th>Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Interval</th>
            <th>Billing Rate</th>
        </tr>
        @foreach($invoice['lessons'] as $item)
            <tr class="items">
                <td>{{ $item->status }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ Carbon\Carbon::parse($item->start_date)->format('D, M d, Y') }}</td>
                <td>{{ Carbon\Carbon::parse($item->start_date)->format('g:i') }} - {{ Carbon\Carbon::parse($item->end_date)->format('g:i a') }}</td>
                <td>{{ $item->interval }} minutes</td>
                <td>{{ ucfirst($item->billingRate->type) }}</td>
            </tr>
        @endforeach
    </table>
</div>

<hr/>

<div class="total">
    <table>
        <tr>
            <td>Subtotal</td>
            <td>${{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Discount</td>
            <td>${{ number_format($invoice->discount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>${{ number_format($invoice->total, 2) }}</strong></td>
        </tr>
        <tr>
            <td>Balance Due</td>
            <td>${{ number_format($invoice->balance_due, 2) }}</td>
        </tr>
    </table>
    @if($invoice->is_paid && $invoice->balance_due == 0)
        <div class="paid">
            <p>-- PAID --</p>
        </div>
    @endif
</div>

<div class="notes">
    <p>Thank you for your business.</p>
</div>

<div class="footer margin-top">
    <div>&copy; MusicTeachersAid.com</div>
</div>
</body>
</html>
