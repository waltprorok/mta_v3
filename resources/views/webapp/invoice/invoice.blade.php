@extends('layouts.webapp')
@section('title', 'Edit Student')
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
                        <img src="{{ asset('marketing/img/logo1.png') }}" alt="mta logo">
                    </div>
                    @foreach($student as $data)
                        <div class="col-md-6 text-right">
                            <p class="font-weight-bold mb-1">Invoice #550</p>
                            <p class="text-muted mb-1">Date: {{ Carbon\Carbon::now()->format('m/d/Y') }}</p>
                            <p class="text-muted mb-1">Due: {{ Carbon\Carbon::now()->addDays(15)->format('m/d/Y') }}</p>
                        </div>

                        <div class="col-md-6 text-left">
                            <p class="font-weight-bold mb-4">Billing Information</p>
                            <p class="mb-1"><span class="text-muted"></span><strong>{{ $data->studentTeacher->studio_name}}</strong></p>
                            <p class="mb-1"><span class="text-muted"></span>{{ $data->studentTeacher->first_name }} {{ $data->studentTeacher->last_name }}</p>
                            <p class="mb-1"><span class="text-muted"></span>{{ $data->studentTeacher->address }} {{ $data->studentTeacher->address_2 }}
                                <br>{{ $data->studentTeacher->city }}, {{ $data->studentTeacher->state }} {{ $data->studentTeacher->zip }}</p>
                            <p class="mb-1"><span class="text-muted"></span>{{ $data->studentTeacher->email }}</p>
                            <p class="mb-1"><span class="text-muted"></span>{{ $data->studentTeacher->phone_number }}</p>
                        </div>
                </div>

                <hr class="my-1">
                <div class="row pb-5 p-5">
                    <div class="col-md-6">
                        <p class="font-weight-bold mb-4">Client Information</p>
                        <p class="mb-1">{{ $data->first_name }} {{ $data->last_name }}</p>
                        <p class="mb-1">{{ $data->address }} {{ $data->address_2 }}</p>
                        <p class="mb-1">{{ $data->city }}, {{ $data->state }} {{ $data->zip }}</p>
                        <p class="mb-1">{{ $data->email }}</p>
                    </div>

                    {{--                    <div class="col-md-6 text-right">--}}
                    {{--                        <p class="font-weight-bold mb-4">Payment Details</p>--}}
                    {{--                        <p class="mb-1"><span class="text-muted">VAT: </span> 1425782</p>--}}
                    {{--                        <p class="mb-1"><span class="text-muted">VAT ID: </span> 10253642</p>--}}
                    {{--                        <p class="mb-1"><span class="text-muted">Payment Type: </span> Root</p>--}}
                    {{--                        <p class="mb-1"><span class="text-muted">Name: </span> John Snow</p>--}}
                    {{--                    </div>--}}
                </div>

                <div class="row p-5">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="border-0 text-uppercase small font-weight-bold">ID</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Title</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Start Date</th>
                                <th class="border-0 text-uppercase small font-weight-bold">End Date</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Quantity</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Description</th>
                                <th class="border-0 text-uppercase small font-weight-bold">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data->lessons as $lesson)
                                <tr>
                                    <td>{{ $lesson->id }}</td>
                                    <td>{{ $lesson->title }}</td>
                                    <td>{{ Carbon\Carbon::parse($lesson->start_date)->format('m-d-Y g:i a') }}</td>
                                    <td>{{ Carbon\Carbon::parse($lesson->end_date)->format('m-d-Y g:i a') }}</td>
                                    <td>{{ $lesson->interval }} minutes</td>
                                    <td>{{ $lesson->billingRate->description }}</td>
                                    <td>${{ $lesson->billingRate->amount }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <p class="pt-2 text-left">Thank you for your business.</p>
                    </div>
                </div>
                @endforeach
                <div class="d-flex flex-row-reverse bg-dark text-white p-4">
                    <div class="py-3 px-5 text-right">
                        <div class="mb-2"><strong>Balance Due</strong></div>
                        <div class="h2 font-weight-light">${{ $balanceDue }}</div>
                    </div>

                    <div class="py-3 px-5 text-right">
                        <div class="mb-2">Total</div>
                        <div class="h2 font-weight-light">${{ $total }}</div>
                    </div>

                    <div class="py-3 px-5 text-right">
                        <div class="mb-2">Discount</div>
                        <div class="h2 font-weight-light">{{ $discount }}%</div>
                    </div>

                    <div class="py-3 px-5 text-right">
                        <div class="mb-2">Sub - Total amount</div>
                        <div class="h2 font-weight-light">${{ $subTotal }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

