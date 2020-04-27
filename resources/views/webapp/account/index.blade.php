@extends('layouts.webapp')
@section('title', 'Account')
@section('content')

    <div class="col-12">
        <h2>Account</h2>
        <div class="card">
            <form action="{{ route('subscription.create') }}" method="post" id="payment-form">
                @csrf
                <div class="form-group">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Billing</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <label for="card-element">Amount</label>
                                @foreach($plans as $plan)
                                    <p class="form-control">${{ number_format($plan->cost, 2) }}
                                        for {{ ucfirst($plan->name) }} Plan / Month</p>
                                    <label for="card-element">Credit Card</label>
                                    <div class="form-control" id="card-element">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- Used to display form errors. -->
                                    <div id="card-errors" role="alert"></div>
                                    <input type="hidden" name="plan" value="{{ $plan->id }}"/>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <p>Your <b>FREE</b> account is available until <b>{{ date('M d, Y', strtotime(Auth::user()->trial_ends_at )) }}</b></p>
                                <ul class="list-group">
                                    <li>Enter Information</li>
                                    <li>Credit Card Number</li>
                                    <li>Month, Year, CSV and Zip Code</li>
                                    <li>Trusted Secure Payment</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>var stripe = Stripe('{{ env("STRIPE_KEY") }}');</script>
    <script src="{{ asset('webapp/js/stripe.js') }}"></script>
@endsection
