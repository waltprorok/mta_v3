@extends('layouts.app')
@section('title', 'Register')
@section('content')

    <div id="fixedNavBar" class="container reg-padding">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="section-header text-center">
                    <h2 class="title">Create an Account</h2>
                </div>
                <p class="text-center">Already a member? <a href="{{ route('login') }}"><u>Sign In</u></a></p>
                <div class="panel panel-default">
                    <br />
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="first_name" class="col-md-4 control-label">First Name</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" name="first_name"
                                           value="{{ old('first_name') }}" autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label for="last_name" class="col-md-4 control-label">Last Name</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" name="last_name"
                                           value="{{ old('last_name') }}">

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-check{{ $errors->has('terms') ? ' has-error' : '' }}">
                                <label for="terms" class="col-md-4 control-label"></label>
                                <div class="col-md-6">
                                    <label for="terms" class="form-check-label">
                                        <input type="checkbox" name="terms" value="1" id="terms"
                                               class="form-check-input">
                                        &nbsp;&nbsp;I agree to the
                                        <a href="{{ route('terms') }}" target="_blank"
                                           style="text-decoration: underline;">Terms of Service</a> and
                                        <a href="{{ route('privacy') }}" target="_blank"
                                           style="text-decoration: underline;">Privacy Policy</a>.
                                    </label>
                                    @if ($errors->has('terms'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('terms') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" id="signup" class="btn btn-primary" disabled>Sign Up</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Payment Questions</div>
                    <div class="panel-body">
                        <p><b>Do I have to give my credit card information to use the 30-Day free trial?</b></p>
                        <p>Definitely not. We only expect you to submit your card info once you've tried the
                            service and decide to continue using it. Once you submit your payment info, your card will
                            be charged at that time, and the free period will end.</p>
                        <p><b>How do I cancel my account?</b></p>
                        <p>You can cancel at any time from the "Account->Subscription" page after you login. There are no
                            cancellation fees or other hidden charges. Everything is month to month.</p>
                        <p><b>What types of payment do you accept?</b></p>
                        <p>We accept all major credit cards (Visa, Mastercard, Amex, Discover).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
