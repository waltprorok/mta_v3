@extends('layouts.app')
@section('title', 'Log In')
@section('content')

    <div id="fixedNavBar" class="container reg-padding">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div class="section-header text-center">
                    <h2 class="title">Sign In To Your Account</h2>
                </div>
                <p class="text-center">Not a member yet? <a href="{{ route('register') }}"><u>Click here</u></a> to join our
                    community.</p>
                <div class="panel panel-default">
                    <br/>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                            @csrf
                            @honeypot
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="email" class="control-label">Email Address</label>
                                    <input id="email" type="email" tabindex="1" class="form-control" autocapitalize="off" autocomplete="email" name="email" placeholder="email@example.com"
                                           value="{{ old('email') }}" autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="col-md-10 col-md-offset-1">
                                    <label for="password" class="control-label">Password</label>
                                    <small>
                                        <a class="btn btn-link pull-right" tabindex="-1" href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a>
                                    </small>
                                    <input id="password" type="password" tabindex="2" class="form-control" name="password" placeholder="Password">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" tabindex="3" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <button type="submit" tabindex="4" class="btn btn-primary btn-block">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
