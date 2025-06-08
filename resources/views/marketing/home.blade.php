@extends('layouts.app')
@section('content')

    <div id="home">
        <div class="bg-img">
            <div class="overlay"></div>
        </div>
        <div class="home-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="home-content">
                            <h1 class="white-text">You teach. We'll do the rest!</h1>
                            <h4 class="white-text">The easiest way to manage your independent music teaching studio.</h4>
                            <a href="{{ route('register') }}" class="main-btn">Get Started Today!</a>
                            <a href="{{ url('/#about') }}" class="white-btn">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="about" class="section md-padding">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title">Save time and money with an automated studio</h2>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
                    <div class="about">
                        <h5 style="line-height: 32px;">Have you ever wished for an easier way to track how much each
                            student owes you? Do your students or their parents ever forget about a lesson?
                            <br><br>
                            Music Teacher's Aid is on-line software that takes the frustration out of managing your
                            independent music teaching studio.
                            <br><br>
                            Handling everything from billing and lesson schedules, to automatic reminders and reports.
                            You'll wonder how you ever got by without it!
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="features" class="section md-padding bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="section-header text-center">
                        <h2 class="title">Spend more time teaching</h2>
                    </div>
{{--  <br/><br/>Accept credit card and even set up recurring charges so you never miss a payment again.  --}}
                    <h5 style="line-height: 32px;" class="">Be confident about your rates with professional
                        looking invoices that can be sent automatically with payment reminders.<br><br>
                        Automatically schedule students each month with a click of a button.<br><br>
                        And now that you're spending less time managing your independent music teaching studio,
                        you'll have time to increase your student roster and earn more!</h5>
                    <hr/>
                    <div class="feature">
                        <i class="fa fa-check"></i>
                        <p>Keep track of students and scheduling.</p>
                    </div>
                    <div class="feature">
                        <i class="fa fa-check"></i>
                        <p>Easily track payments.</p>
                    </div>
                    <div class="feature">
                        <i class="fa fa-check"></i>
                        <p>Automate student schedules and reporting.</p>
                    </div>
                    <div class="feature">
                        <i class="fa fa-check"></i>
                        <p>Keep in touch with parents about students progress.</p>
                    </div>
                </div>
                <div class="col-md-7">
                    <div id="about-slider" class="owl-carousel owl-theme">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-1.png') }}" alt="monitor-screenshot">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-2.png') }}" alt="monitor-screenshot">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-3.png') }}" alt="monitor-screenshot">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-4.png') }}" alt="monitor-screenshot">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-5.png') }}" alt="monitor-screenshot">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-6.png') }}" alt="monitor-screenshot">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-7.png') }}" alt="monitor-screenshot">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="pricing" class="section md-padding">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title">Simple, fixed pricing</h2>
                    <h3>Get started with a 30 Day Free Trial.</h3>
                </div>

                <div class="col-md-4 col-md-offset-0">
                    <div class="pricing">
                        <div class="price-head">
                            <span class="price-title">FREEMIUM</span>
                            <div class="price">
                                <h3>FREE<span class="duration">30 Days</span></h3>
                            </div>
                        </div>
                        <ul class="price-content">
                            <li>
                                <p>Unlimited Students</p>
                            </li>
                            <li>
                                <p>Automatic Scheduling</p>
                            </li>
                            <li>
                                <p>Professional Invoicing</p>
                            </li>
                            <li>
                                <p>Track Payments</p>
                            </li>
                            <li>
                                <p>Upgrade Anytime</p>
                            </li>
                            <li>
                                <p>30 Day Free Trial</p>
                            </li>
                        </ul>

                        <div class="price-btn">
                            <a href="{{ route('register') }}" class="outline-btn">Start Free Trial</a>
                        </div>
                    </div>
                </div>

                @foreach($plans as $plan)
                    <div class="col-md-4">
                        <div class="pricing">
                            <div class="price-head">
                                <span class="price-title">{{ $plan->name }}</span>
                                <div class="price">
                                    <h3>${{ number_format($plan->cost, 2) }}
                                        <span class="duration">{{ $plan->slug }}</span>
                                        @if($plan->slug == 'yearly')
                                            <span class="break-down">${{ number_format($plan->cost / 12, 2) }} / month</span>
                                        @endif
                                    </h3>
                                </div>
                            </div>
                            <ul class="price-content">
                                <li>
                                    <p>Unlimited Students</p>
                                </li>
                                <li>
                                    <p>Automatic Scheduling</p>
                                </li>
                                <li>
                                    <p>Professional Invoicing</p>
                                </li>
                                <li>
                                    <p>Track Payments</p>
                                </li>
                                <li>
                                    <p>Student Portal for Families</p>
                                </li>
                                <li>
                                    <p>Cancel Anytime</p>
                                </li>
                            </ul>
                            <div class="price-btn">
                                <a href="{{ route('register') }}" class="outline-btn">Get Started</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="tiles" class="section md-padding">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title">We Can Help With</h2>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="service">
                        <i class="fa fa-calendar"></i>
                        <h3>Scheduling</h3>
                        <p>Easily keep track of students.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="service">
                        <i class="fa fa-credit-card"></i>
                        <h3>Payments</h3>
                        <p>Track all your payments.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="service">
                        <i class="fa fa-file-text-o"></i>
                        <h3>Reporting</h3>
                        <p>Automate billing and reporting.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-md-offset-4">
                    <div class="service">
                        <i class="fa fa-envelope-o"></i>
                        <h3>Communication</h3>
                        <p>Email reminders and invoicing.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="author" class="section md-padding bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div id="about-slider" class="owl-theme">
                        <img class="img-responsive img-rounded" src="{{ asset('marketing/img/about-us.jpeg') }}" alt="about-us">
                    </div>
                </div>
                <br/>
                <div class="col-md-6">
                    <div class="section-header text-center">
                        <h2 class="title">You're in great hands</h2>
                    </div>
                    <h5 style="line-height: 32px;">Music Teacher's Aid was created by a guitar teacher to help
                        manage students with the power of software. Today, the company serves many customers
                        with a team working around the clock every day, to serve you and help you save time,
                        grow your studio, and have more joy in your teaching.</h5>
                </div>
            </div>
        </div>
    </div>

    <div id="testimonials" class="section md-padding">
        <div class="bg-img" style="background-image: url('/marketing/img/guitar-teacher.jpg');">
            <div class="overlay"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div id="testimonial-slider" class="owl-carousel owl-theme">
                        <div class="testimonial">
                            <div class="testimonial-meta">
                                <img src="{{ asset('marketing/img/perso1.jpg') }}" alt="">
                                <h3 class="white-text">Micheal</h3>
                                <span>Bass Teacher</span>
                            </div>
                            <p class="white-text">Music Teachers Aid has increased my business by 30%. I don't know how I managed all
                                my students before using the service.</p>
                        </div>
                        <div class="testimonial">
                            <div class="testimonial-meta">
                                <img src="{{  asset('marketing/img/perso2.jpg') }}" alt="">
                                <h3 class="white-text">Arron</h3>
                                <span>Guitar Teacher</span>
                            </div>
                            <p class="white-text">The best part of Music Teachers Aid is how easy it is to use.</p>
                        </div>
                        <div class="testimonial">
                            <div class="testimonial-meta">
                                <img src="{{ asset('marketing/img/perso3.jpeg') }}" alt="">
                                <h3 class="white-text">Beth</h3>
                                <span>Piano Teacher</span>
                            </div>
                            <p class="white-text">Auto-scheduling has improved efficiency, allowing me to focus on student success rather than administrative headaches.</p>
                        </div>
                        <div class="testimonial">
                            <div class="testimonial-meta">
                                <img src="{{  asset('marketing/img/perso4.jpeg') }}" alt="">
                                <h3 class="white-text">Ava</h3>
                                <span>Music Teacher</span>
                            </div>
                            <p class="white-text">Since switching to a system that generates professional-looking invoices, my business has seen a noticeable improvement in client trust and payment turnaround.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="summary" class="section md-padding">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title"><strong>Music Teacher's Aid</strong></h2>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
                    <div class="about">
{{--  Accept credit card and even set up recurring charges so you never miss a payment again. --}}
                        <h5 style="line-height: 32px;">Be confident about your rates with professional looking invoices
                            that can be sent automatically with payment reminders.<br><br>
                            And now that you're spending less time managing your teaching studio, you'll have time to
                            increase your student roster and earn more!</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="register" class="section md-padding bg-grey">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title">Sign up for your <strong>FREE</strong> 30-day trial</h2>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
                    <div class="price-btn text-center">
                        <a href="{{ route('register') }}" class="outline-btn">Get Started Today!</a>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        <small>*No Credit Card Required</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
