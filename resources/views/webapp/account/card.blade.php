@extends('layouts.webapp')
@section('title', 'Account')
@section('content')


    <div class="col-12">
        <h2>Credit Card</h2>
        <div class="card">
            <form action="{{ route('subscription.updateCard') }}" method="POST" id="payment-form">
                @csrf
                <div class="form-group">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Update Credit Card</h5>
                    </div>

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
{{--                                <label for="card-element">Update Current Credit Card on File</label>--}}
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
