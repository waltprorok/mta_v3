@extends('layouts.webapp')
@section('title', 'Account')
@section('content')

    <div class="col-md-12">
        <h4>Account</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Subscription</li>
        </ul>

        @include('partials.accountTabs')
        <div class="card">
            <form action="{{ route('subscription.changed') }}" method="post" id="payment-form">
                @csrf
                <div class="form-group">
                    <div class="card-header bg-light">Change Subscription Billing Plan</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <label for="card-element">Your current <b>{{ ucfirst(Auth::user()->subscriptions()->first()->name) }}</b> plan is on  </label>
                                <label for="card-element">the <b>@if($plan->id != 1)Monthly @else Yearly @endif</b> billing cycle.</label>
                                <select class="form-control" name="plan" id="sel1">
                                    <option value="{{ $plan->id }}">${{ number_format($plan->cost, 2) }} for {{ ucfirst($plan->name) }} Plan / @if($plan->id == 1)Monthly @else Yearly @endif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <button class="btn btn-primary" type="submit">Change Subscription Billing</button>
                            <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">Back</a>
                        </div>
                    </div>
            </form>
        </div>
    </div>

@endsection
