@extends('layouts.webapp')
@section('title', 'Account')
@section('content')

    <div class="col-12">
        <h3>Credit Card</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Profile</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('account.subscription') }}">Subscription</a></li>
        </ul>

        @include('partials.accountTabs')
        <div class="card">
            <form action="{{ route('subscription.updateCard') }}" method="POST" id="payment-form">
                @csrf
                <div class="form-group">
                    <div class="card-header bg-light">Update Credit Card</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="form-control" id="card-element">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <div id="card-errors" role="alert"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item active">Update Current Credit Card on File</li>
                                    <li class="list-group-item"><i class="fa fa-credit-card" style="padding-right: 10px;" aria-hidden="true"></i> Credit Card Number</li>
                                    <li class="list-group-item"><i class="fa fa-keyboard-o" style="padding-right: 10px;" aria-hidden="true"></i> Month, Year, CVC and Zip Code</li>
                                    <li class="list-group-item"><i class="fa fa-lock" style="padding-right: 16px;" aria-hidden="true"></i> Trusted Secure Payment</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="stripe-errors"></div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Update Card</button>
                            <a href="{{ URL::previous() }}" class="btn btn-outline-dark">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script src="https://js.stripe.com/v3/"></script>
    <script>var stripe = Stripe('{{ env("STRIPE_KEY") }}');</script>
    <script src="{{ asset('webapp/js/stripe.js') }}"></script>
@endsection
