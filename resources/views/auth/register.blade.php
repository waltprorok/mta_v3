@extends('layouts.app')
@section('title', 'Create your account')
@section('content')

    <div id="fixedNavBar" class="container reg-padding">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div class="section-header text-center">
                    <h2 class="title">Create an Account</h2>
                </div>
                <p class="text-center">Already have an account? <a href="{{ route('login') }}"><u>Sign In</u></a></p>
                <div class="panel panel-default">
                    <br/>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                            @csrf
                            @honeypot
                            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="first_name" class="control-label">First Name</label>
                                    <input id="first_name" type="text" class="form-control" name="first_name" placeholder="First name" value="{{ old('first_name') }}" autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="last_name" class="control-label">Last Name</label>
                                    <input id="last_name" type="text" class="form-control" name="last_name" placeholder="Last name" value="{{ old('last_name') }}">

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="email" class="control-label">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" placeholder="email@example.com" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="password" class="control-label">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="password-confirm" class="control-label">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm password">
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="timezone" class="control-label">Time Zone</label>
                                    <select class="form-control" name="timezone">
                                        @foreach($timezones as $value)
                                            <option value="{{ $value }}" {{ old('timezone') === $value ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('timezone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('timezone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-check {{ $errors->has('terms') ? 'has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="terms" class="form-check-label">
                                        <input type="checkbox" name="terms" value="1" id="terms" class="form-check-input">
                                        &nbsp;&nbsp;I agree to the
                                        <a href="{{ route('terms') }}" target="_blank" style="text-decoration: underline;">Terms of Service</a> and
                                        <a href="{{ route('privacy') }}" target="_blank" style="text-decoration: underline;">Privacy Policy</a>.
                                    </label>

                                    @if ($errors->has('terms'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('terms') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1" style="margin-top: 16px;">
                                    <button type="submit" id="signup" class="btn btn-primary btn-block" disabled>Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
