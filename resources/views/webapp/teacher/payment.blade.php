@extends('layouts.webapp')
@section('title', 'Payment Processing')
@section('content')


    <div class="col-12">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('teacher.payment') }}">Payment</a></li>
        </ul>

        <h2>Studio Settings</h2>

        @include('partials.teacherTabs')
        <div class="card">
            <div class="card-header bg-light">Payment Processing</div>
            <div class="card-body">
                <p>How to get Paid</p>
            </div>
        </div>
    </div>

@endsection
