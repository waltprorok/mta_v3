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
                            <h4 class="white-text">The easiest way to manage your music teaching studio.</h4>
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
                            student owes you? Do your students or their parents ever forget about a lesson or recital?
                            Or did you ever lend out a book to a student, and never get it back?<br/><br>
                            Music Teacher's Aid is on-line software that takes the frustration out of managing your studio.
                            Handling everything from billing and lesson schedules, to automatic reminders and reports, you'll wonder how you
                            ever got by without it!
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
                    <h5 style="line-height: 32px;" class="">Be confident about your rates with professional
                        looking invoices that can be sent automatically with payment reminders.<br/><br/>Accept credit card and
                        even set up recurring charges so you never miss a payment again.<br/><br/>
                        And now that you're spending less time managing your teaching studio, you'll have time to increase
                        your student roster and earn more!</h5>
                    <hr/>
                    <div class="feature">
                        <i class="fa fa-check"></i>
                        <p>Keep track of students and scheduling.</p>
                    </div>
                    <div class="feature">
                        <i class="fa fa-check"></i>
                        <p>Easily accept payments.</p>
                    </div>
                    <div class="feature">
                        <i class="fa fa-check"></i>
                        <p>Automate billing and reporting.</p>
                    </div>
                    <div class="feature">
                        <i class="fa fa-check"></i>
                        <p>Keep in touch with parents about students progress.</p>
                    </div>
                </div>
                <div class="col-md-7">
                    <div id="about-slider" class="owl-carousel owl-theme">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-4.png') }}" alt="monitor-screenshot">
                        <img class="img-responsive" src="{{ asset('marketing/img/monitor-1.png') }}" alt="monitor-screenshot">
                    </div>
                </div>
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
                        <p>Accept credit card payments.</p>
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
                        <p>Email and text messaging.</p>
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
                            <p class="white-text">MTA has increased my business by 30%. I don't know how I managed all
                                my students before using the service.</p>
                        </div>
                        <div class="testimonial">
                            <div class="testimonial-meta">
                                <img src="{{  asset('marketing/img/perso2.jpg') }}" alt="">
                                <h3 class="white-text">Arron</h3>
                                <span>Guitar Teacher</span>
                            </div>
                            <p class="white-text">The best part of MTA is how easy it is to use.</p>
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
                        <h5 style="line-height: 32px;">Be confident about your rates with professional looking invoices
                            that can be sent automatically with payment reminders. Accept credit card and even set up
                            recurring charges so you never miss a payment again.<br><br>
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
