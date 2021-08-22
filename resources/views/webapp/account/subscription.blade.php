@extends('layouts.webapp')
@section('title', 'Account Information')
@section('content')

    <div class="col-12">
        <h4>Account</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.profile') }}">Profile</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('account.subscription') }}">Subscription</a></li>
        </ul>

        @include('partials.accountTabs')
        <div class="card">
            <div class="card-header bg-light">Subscription Details</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><i class="fa fa-credit-card" aria-hidden="true" style="padding-right: 12px;"></i>{{ Auth::user()->card_brand }}
                                <span class="float-right">**** **** **** {{ Auth::user()->card_last_four }}</span></li>
                            <li class="list-group-item"><i class="fa fa-download" aria-hidden="true" style="padding-right: 12px;"></i><a href="{{ route('subscription.invoices') }}">Download Invoices</a></li>
                            <li class="list-group-item"><i class="fa fa-keyboard-o" aria-hidden="true" style="padding-right: 12px;"></i><a href="{{ route('subscription.card') }}">Updated Credit Card</a></li>
                            @if (Auth::user()->subscription('premium')->cancelled())
                                <li class="list-group-item"><i class="fa fa-check" aria-hidden="true" style="padding-right: 12px;"></i><a href="{{ route('subscription.resume') }}">Resume Subscription</a></li>
                            @else
                                <li class="list-group-item">
                                    <i class="fa fa-ban" aria-hidden="true" style="padding-right: 14px;"></i><a href="#" data-toggle="modal" data-target="#cancelSubscription">Cancel Subscription</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="card-body">
                @if (Route::currentRouteName() != 'account.subscription')
                    <a href="{{ route('account.subscription') }}" class="btn btn-primary">Back</a>
                @endif
            </div>
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
