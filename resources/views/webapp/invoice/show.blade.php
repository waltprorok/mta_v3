@extends('layouts.webapp')
@section('title', 'Invoice')
@section('content')

    <div class="col-12">
        @if(Auth::user()->isTeacher())
            <h4>Invoice</h4>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">Billing</a></li>
                <li class="breadcrumb-item active">Invoice</li>
            </ul>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="row p-5">
                    <div class="col-md-6">
                        @if ($invoice->student->getTeacher->logo)
                            <img src="{{ asset('storage/teacher/'. $invoice->student->getTeacher->logo) }}" alt="business logo" width="180px">
                        @else
                            <img src="{{ asset('webapp/img/logo-mta1.png') }}" alt="mta logo">
                        @endif
                    </div>

                    <div class="col-md-6 text-right">
                        <p class="font-weight-bold mb-2">Invoice: {{ $invoice->id }}</p>
                        <p class="text-muted mb-1"><span class="text-muted">Issue Date: </span>{{ $invoice->created_at->format('m/d/Y') }}</p>
                        <p class="text-muted mb-1">
                            <span class="text-muted">Due Date: </span>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('m/d/Y') : $invoice->created_at->endOfMonth()->format('m/d/Y') }}
                        </p>
                        <p class="text-muted mb-1"><span class="text-muted">Status: </span>
                            <span class="{{ $invoice->is_paid ? 'font-weight-bold' : 'badge-danger' }}">{{ $invoice->is_paid ? 'Paid' : 'Not Paid' }}</span></p>
                    </div>

                    <div class="col-md-6 text-left">
                        <p class="font-weight-bold mb-4">Billed By:</p>
                        <p class="mb-1"><span class="text-muted"></span><strong>{{ $invoice->student->getTeacher->studio_name }}</strong></p>
                        <p class="mb-1"><span class="text-muted"></span>{{ $invoice->student->getTeacher->first_name }} {{ $invoice->student->getTeacher->last_name }}</p>
                        <p class="mb-1"><span class="text-muted"></span>{{ $invoice->student->getTeacher->address }} {{ $invoice->student->getTeacher->address_2 }}
                            <br>{{ $invoice->student->getTeacher->city }}, {{ $invoice->student->getTeacher->state }} {{ $invoice->student->getTeacher->zip }}</p>
                        <br/>
                        <p class="mb-1"><span class="text-muted"></span>{{ $invoice->student->getTeacher->email }}</p>
                        <p class="mb-1"><span class="text-muted"></span>{{ $invoice->student->getTeacher->phone_number }}</p>
                    </div>

                    <div class="col-md-6">
                        <p class="font-weight-bold mb-4">Billed To:</p>
                        <p class="mb-1">@if ($invoice->student->parent)
                                {{ $invoice->student->parent->first_name }} {{ $invoice->student->parent->last_name }}
                            @else
                                {{ $invoice->student->first_name }} {{ $invoice->student->last_name }}
                            @endif</p>
                        <p class="mb-1">@if ($invoice->student->address)
                                {{ $invoice->student->address }} {{ $invoice->student->address_2 }}
                            @else @endif</p>
                        <p class="mb-1">@if ($invoice->student->city || $invoice->student->state || $invoice->student->zip)
                                {{ $invoice->student->city }}, {{ $invoice->student->state }} {{ $invoice->student->zip }}
                            @else @endif</p>
                        <br/>
                        <p class="mb-1">@if ($invoice->student->email)
                                {{ $invoice->student->email }}
                            @else @endif</p>
                        <p class="mb-1">@if ($invoice->student->parent)
                                {{ $invoice->student->parent->email }}
                            @else @endif</p>
                        <p class="mb-1">@if ($invoice->student->phone)
                                {{ $invoice->student->phone_number }}
                            @else @endif</p>
                    </div>
                </div>

                <hr class="my-1">

                <div class="row p-5">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="border-0 text-uppercase small font-weight-bold">Status</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Name</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Date</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Time</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Interval</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Billing Rate</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lessons as $lesson)
                                <tr>
                                    <td>{{ $lesson->status }}</td>
                                    <td>{{ $lesson->title }}</td>
                                    <td>{{ Carbon\Carbon::parse($lesson->start_date)->format('D, M d, Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($lesson->start_date)->format('g:i') }} - {{ Carbon\Carbon::parse($lesson->end_date)->format('g:i a') }}</td>
                                    <td>{{ $lesson->interval }} minutes</td>
                                    <td>{{ ucfirst($lesson->billingRate->type) }}</td>
                                    <td>${{ number_format($lesson->billingRate->amount, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <p class="pt-2 text-left">Thank you for your business.</p>
                    </div>
                </div>

                @if($invoice->is_paid && $balanceDue == 0)
                    <div class="px-5 mr-5 col-md-12">
                        <h3 class="text-danger text-right">-- PAID --</h3>
                    </div>
                @endif

                <div class="d-flex flex-row-reverse bg-dark text-white p-4">
                    <div class="py-3 px-5 text-right">
                        <div class="mb-2"><strong>Balance Due</strong></div>
                        <div class="h2 font-weight-light">${{ number_format($balanceDue, 2) }}</div>
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

