@extends('layouts.webapp')
@section('title', 'Subscription')
@section('content')

    <div class="col-md-12">
        <h4>Credit Card</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Subscription</li>
        </ul>

        @include('partials.accountTabs')
        <div class="card">
            <form action="{{ route('subscription.updateCard') }}" method="POST" id="payment-form" class="card-form">
                @csrf
                <div class="form-group">
                    <div class="card-header bg-light">Update Credit Card</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="hidden" name="payment_method" class="payment-method">
                                </div>
                                <div class="form-group">
                                    <label for="card-element">Cardholder Name</label>
                                    <input class="StripeElement mb-3 form-control" name="card_holder_name" placeholder="Full name on card" required>
                                </div>
                                <div class="form-group">
                                    <label for="card-element">Credit Card</label>
                                    <div class="form-control" id="card-element">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <div id="card-errors" role="alert"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item active">Update Current Credit Card on File</li>
                                    <li class="list-group-item"><i class="fa fa-credit-card-alt" style="padding-right: 10px;" aria-hidden="true"></i>{{ Auth::user()->card_brand }} ending in {{ Auth::user()->card_last_four }}</li>
                                    <li class="list-group-item"><i class="fa fa-credit-card" style="padding-right: 10px;" aria-hidden="true"></i> Credit Card Number</li>
                                    <li class="list-group-item"><i class="fa fa-keyboard-o" style="padding-right: 10px;" aria-hidden="true"></i> Month, Year, CVC and Zip Code</li>
                                    <li class="list-group-item"><i class="fa fa-lock" style="padding-right: 16px;" aria-hidden="true"></i> Trusted Secure Payment</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="stripe-errors"></div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary pay">Update Card</button>
                            <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>let stripe = Stripe('{{ config('services.stripe.key') }}');</script>
    <script>
        let elements = stripe.elements()
        let style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
        let card = elements.create('card', {style: style})
        card.mount('#card-element')
        let paymentMethod = null
        $('.card-form').on('submit', function (e) {
            $('button.pay').attr('disabled', true)
            if (paymentMethod) {
                return true
            }
            stripe.confirmCardSetup(
                "{{ $intent->client_secret }}",
                {
                    payment_method: {
                        card: card,
                        billing_details: {name: $('.card_holder_name').val()}
                    }
                }
            ).then(function (result) {
                if (result.error) {
                    $('#card-errors').text(result.error.message)
                    $('button.pay').removeAttr('disabled')
                } else {
                    paymentMethod = result.setupIntent.payment_method
                    $('.payment-method').val(paymentMethod)
                    $('.card-form').submit()
                }
            })
            return false
        })
    </script>
    {{--    <script src="{{ asset('webapp/js/stripe.js') }}"></script>--}}
@endsection
