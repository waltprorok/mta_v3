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
            <img src="{{ public_path('/webapp/img/logo-mta1.png') }}" alt="mta" width="180"/>
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
            <td class="w-full">
                <div>Billed By:</div>
                <br>
                <div><h4>{{ $invoice->student->studentTeacher->studio_name }}</h4></div>
                <div>{{ $invoice->student->studentTeacher->first_name }}&nbsp;{{ $invoice->student->studentTeacher->last_name }}</div>
                <div>{{ $invoice->student->studentTeacher->address }}</div>
                <div>{{ $invoice->student->studentTeacher->city }}, {{ $invoice->student->studentTeacher->state }} {{ $invoice->student->studentTeacher->zip }}</div>
                <br>
                <div>{{ $invoice->student->studentTeacher->email }}</div>
            </td>
        </tr>
    </table>
</div>

<hr>

<div class="margin-top">
    <table class="w-full">
        <tr>
            <td class="w-full">
                <div>Billed To:</div>
                <br>
                <div>{{ $invoice->student->first_name }}&nbsp;{{ $invoice->student->last_name }}</div>
                <div>{{ $invoice->student->email }} </div>
            </td>
        </tr>
    </table>
</div>

<div class="margin-top">
    <table class="products">
        <tr>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Quantity</th>
            <th>Billing Rate</th>
        </tr>
        @foreach($invoice['lessons'] as $item)
            <tr class="items">
                <td>
                    {{ Carbon\Carbon::parse($item->start_date)->format('Y-m-d g:i a') }}
                </td>
                <td>
                    {{ Carbon\Carbon::parse($item->end_date)->format('Y-m-d g:i a') }}
                </td>
                <td>
                    {{ $item->interval }} minutes
                </td>
                <td>
                    {{ ucfirst($item->billingRate->type) }}
                </td>
            </tr>
        @endforeach
    </table>
</div>

<hr/>

<div class="total">
    <table>
        <tr style="text-align: right">
            <td>Subtotal</td>
            <td>${{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr style="text-align: right">
            <td>Discount</td>
            <td>${{ number_format($invoice->discount, 2) }}</td>
        </tr>
        <tr style="text-align: right">
            <td><strong>Total</strong></td>
            <td><strong>${{ number_format($invoice->total, 2) }}</strong></td>
        </tr>
        <tr style="text-align: right">
            <td>Balance Due</td>
            <td>${{ number_format($invoice->balance_due, 2) }}</td>
        </tr>
    </table>
</div>

<div class="footer margin-top">
    <div>&copy; Music Teachers Aid</div>
</div>
</body>
</html>
