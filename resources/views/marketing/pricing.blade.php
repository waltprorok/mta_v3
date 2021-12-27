@extends('layouts.app')
@section('title', 'Pricing')
@section('content')

    <!-- Pricing -->
    <div id="pricing" class="section md-padding">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
                <!-- Section header -->
                <div class="section-header text-center">
                    <h2 class="title">Pricing Table</h2>
                    <h3>Music Teacher's Aid automates scheduling and billing so you can focus on your students.</h3>
                </div>
                <!-- /Section header -->
                <!-- pricing -->
                <div class="col-md-4 col-md-offset-0">
                    <div class="pricing">
                        <div class="price-head">
                            <span class="price-title">FREEMIUM</span>
                            <div class="price">
                                <h3>FREE<span class="duration"></span></h3>
                            </div>
                        </div>
                        <ul class="price-content">
                            <li>
                                <p>10 Students</p>
                            </li>
                            <li>
                                <p>Keep Track of Scheduling</p>
                            </li>
                            <li>
                                <p>Manual Payments</p>
                            </li>
                            <li>
                                <p>SSL Encryption</p>
                            </li>
                            <li>
                                <p>Upgrade Anytime</p>
                            </li>
                        </ul>

                        <div class="price-btn">
                            <a href="{{ route('register') }}" class="outline-btn">Sign Up</a>
                        </div>
                    </div>
                </div>

                @foreach($plans as $plan)
                    <div class="col-md-4">
                        <div class="pricing">
                            <div class="price-head">
                                <span class="price-title">{{ $plan->name }}</span>
                                <div class="price">
                                    <h3>${{ number_format($plan->cost, 2) }}<span class="duration">/ {{ $plan->slug }}</span></h3>
                                </div>
                            </div>
                            <ul class="price-content">
                                <li>
                                    <p>Unlimited Students</p>
                                </li>
                                <li>
                                    <p>Scheduling Notifications</p>
                                </li>
                                <li>
                                    <p>Automate Payments</p>
                                </li>
                                <li>
                                    <p>SSL Encryption</p>
                                </li>
                                <li>
                                    <p>Cancel Anytime</p>
                                </li>
                            </ul>
                            <div class="price-btn">
                                <a href="{{ route('register') }}" class="outline-btn">Free Trial</a>
                            </div>
                        </div>
                    </div>
            @endforeach
            <!-- /pricing -->
            </div>
            <!-- Row -->
        </div>
        <!-- /Container -->
    </div>
    <!-- /Pricing -->

@endsection
