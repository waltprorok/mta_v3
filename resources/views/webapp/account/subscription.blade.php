@extends('layouts.webapp')
@section('title', 'Subscription')
@section('content')

    <div class="col-6">
        <h2>Account</h2>
        <div class="card">
            <ul class="list-group">
                <li class="list-group-item">{{ Auth::user()->card_brand }}
                    <span class="float-right">**** **** **** {{ Auth::user()->card_last_four }}</span></li>
                <li class="list-group-item"><a href="{{ route('subscription.invoices') }}">Download Invoices</a></li>
                <li class="list-group-item"><a href="{{ route('subscription.card') }}">Updated Credit Card</a></li>
                @if (Auth::user()->subscription('premium')->cancelled())
                    <li class="list-group-item"><a href="{{ route('subscription.resume') }}">Resume Subscription</a></li>
                @else
                    <li class="list-group-item"><a href="#" data-toggle="modal" data-target="#cancelSubscription">Cancel Subscription</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="cancelSubscription">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Cancel Subscription</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    By click the <b>Cancel Subscription Button</b> you are here by confirming your account will be
                    cancelled. Don't worry you can always resume your subscription if you change your mind.
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a class="btn btn-danger" href="{{ route('subscription.cancel') }}">Cancel Subscription</a>
                </div>

            </div>
        </div>
    </div>

@endsection
