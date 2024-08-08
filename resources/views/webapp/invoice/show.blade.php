@extends('layouts.webapp')
@section('title', 'Show Invoice')
@section('content')

    <div class="col-12">
        <h4>Invoice</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Billing</a></li>
            <li class="breadcrumb-item active">Invoice</li>
        </ul>

        <div class="card">
            <div class="card-body p-0">
                <div class="row p-5">
                    <div class="col-md-6">
                        <img src="{{ asset('webapp/img/logo-mta1.png') }}" alt="mta logo">
                    </div>

                    <div class="col-md-6 text-right">
                        <p class="font-weight-bold mb-2">Invoice: {{ $invoice->id }}</p>
                        <p class="text-muted mb-1"><span class="text-muted">Date: </span>{{ $invoice->created_at->format('m/d/Y') }}</p>
                        <p class="text-muted mb-1"><span class="text-muted">Due: </span>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('m/d/Y') : $invoice->created_at->endOfMonth()->format('m/d/Y') }}</p>
                        <p class="text-muted mb-1"><span class="text-muted">Status: </span><span class="{{ $invoice->is_paid ? '' : 'badge-danger' }}">{{ $invoice->is_paid ? 'Paid' : 'Not Paid' }}</span></p>
                    </div>

                    <div class="col-md-6 text-left">
                        <p class="font-weight-bold mb-4">Billed By:</p>
                        <p class="mb-1"><span class="text-muted"></span><strong>{{ $invoice->student->studentTeacher->studio_name}}</strong></p>
                        <p class="mb-1"><span class="text-muted"></span>{{ $invoice->student->studentTeacher->first_name }} {{ $invoice->student->studentTeacher->last_name }}</p>
                        <p class="mb-1"><span class="text-muted"></span>{{ $invoice->student->studentTeacher->address }} {{ $invoice->student->studentTeacher->address_2 }}
                            <br>{{ $invoice->student->studentTeacher->city }}, {{ $invoice->student->studentTeacher->state }} {{ $invoice->student->studentTeacher->zip }}</p>
                        <br/>
                        <p class="mb-1"><span class="text-muted"></span>{{ $invoice->student->studentTeacher->email }}</p>
                        <p class="mb-1"><span class="text-muted"></span>{{ $invoice->student->studentTeacher->phone_number }}</p>
                    </div>
                </div>

                <hr class="my-1">
                <div class="row pb-5 p-5">
                    <div class="col-md-6">
                        <p class="font-weight-bold mb-4">Billed To:</p>
                        <p class="mb-1">@if ($invoice->student->first_name)
                                {{ $invoice->student->first_name }} {{ $invoice->student->last_name }}
                            @else @endif</p>
                        <p class="mb-1">@if ($invoice->student->address)
                                {{ $invoice->student->address }} {{ $invoice->student->address_2 }}
                            @else @endif</p>
                        <p class="mb-1">@if ($invoice->student->city || $invoice->student->state || $invoice->student->zip)
                                {{ $invoice->student->city }}, {{ $invoice->student->state }} {{ $invoice->student->zip }}
                            @else @endif</p>
                        <p class="mb-1">@if ($invoice->student->phone)
                                Phone: {{ $invoice->student->phone_number }}
                            @else @endif</p>
                        <p class="mb-1">@if ($invoice->student->email)
                                Email: {{ $invoice->student->email }}
                            @else @endif</p>
                        <p class="mb-1">@if ($invoice->student->parent_email)
                                Parent: {{ $invoice->student->parent_email }}
                            @else @endif</p>

                    </div>

{{--                                        <div class="col-md-6 text-right">--}}
{{--                                            <p class="font-weight-bold mb-4">Payment Details</p>--}}
{{--                                            <p class="mb-1"><span class="text-muted">VAT: </span> 1425782</p>--}}
{{--                                            <p class="mb-1"><span class="text-muted">VAT ID: </span> 10253642</p>--}}
{{--                                            <p class="mb-1"><span class="text-muted">Payment Type: </span> Root</p>--}}
{{--                                            <p class="mb-1"><span class="text-muted">Name: </span> John Snow</p>--}}
{{--                                        </div>--}}
                </div>

                <div class="row p-5">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="border-0 text-uppercase small font-weight-bold">Complete</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Title</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Start Date</th>
                                <th class="border-0 text-uppercase small font-weight-bold">End Date</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Quantity</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Billing Rate</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lessons as $lesson)
                                <tr>
                                    <td>@if($lesson->complete)
                                            <i class="fa fa-check ml-4" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-times ml-4" aria-hidden="true"></i>
                                        @endif</td>
                                    <td>{{ $lesson->title }}</td>
                                    <td>{{ Carbon\Carbon::parse($lesson->start_date)->format('m-d-Y g:i a') }}</td>
                                    <td>{{ Carbon\Carbon::parse($lesson->end_date)->format('m-d-Y g:i a') }}</td>
                                    <td>{{ $lesson->interval }} minutes</td>
                                    <td>{{ $lesson->billingRate->description }}</td>
                                    <td>${{ number_format($lesson->billingRate->amount, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <p class="pt-2 text-left">Thank you for your business.</p>
                    </div>
                </div>

                <div class="d-flex flex-row-reverse bg-dark text-white p-4">
                    <div class="py-3 px-5 text-right">
                        <div class="mb-2"><strong>Balance Due</strong></div>
                        <div class="h2 font-weight-light">${{ number_format($balanceDue,2 ) }}</div>
                    </div>

                    <div class="py-3 px-5 text-right">
                        <div class="mb-2">Total</div>
                        <div class="h2 font-weight-light">${{ number_format($total, 2) }}</div>
                    </div>

                    <div class="py-3 px-5 text-right">
                        <div class="mb-2">Discount</div>
                        <div class="h2 font-weight-light">{{ $discount }}%</div>
                    </div>

                    <div class="py-3 px-5 text-right">
                        <div class="mb-2">Sub - Total amount</div>
                        <div class="h2 font-weight-light">${{ number_format($subTotal, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

