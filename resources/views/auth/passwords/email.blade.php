@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')

    <div id="fixedNavBar" class="container reg-padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="section-header text-center">
                    <h2 class="title">Reset Your Password</h2>
                </div>
                <div class="panel panel-default">
                    <br />
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                <div class="col-md-8 col-md-offset-2">
                                    <label for="email" class=" control-label">Email Address</label>
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-2">
                                    <a href="{{ route('login') }}" class="btn-link">Already have an account?</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Send Password Reset Link
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
