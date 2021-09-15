@extends('layouts.webapp')
@section('title', 'Profile')
@section('content')

    <div class="col-12">
        <h4>Account</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('account.profile') }}">Profile</a></li>
        </ul>

        @include('partials.accountTabs')
        <div class="card">
            <div class="card-header bg-light">Update Profile Information</div>
            <div class="card-body">
                <form class="form-horizontal" method="POST" action="{{ route('account.updateProfile') }}">
                    @csrf
                    <div class="row mb-5">
                        <div class="col-md-4 mb-4">
                            <div>Profile Information</div>
                            <div class="text-muted small">Update first name, last name, and email.</div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                        <label class="control-label">First Name</label>
                                        <input for="first_name" name="first_name" class="form-control" value="{{ Auth::user()->first_name }}">
                                        @if ($errors->has('first_name'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                        <label for="last_name" class="control-label">Last Name</label>
                                        <input name="last_name" class="form-control" value="{{ Auth::user()->last_name }}">
                                        @if ($errors->has('last_name'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label for="email" class="control-label">Email Address</label>
                                        <input name="email" class="form-control" value="{{ Auth::user()->email }}">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-5">
                        <div class="col-md-4 mb-4">
                            <div>Update Password</div>
                            <div class="text-muted small">Leave credentials fields empty if you don't wish to change the password.
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('current_password') ? 'has-error' : '' }}">
                                        <label for="current_password" class="control-label">Current Password</label>
                                        <input name="current_password" type="password" class="form-control">
                                        @if ($errors->has('current_password'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('current_password') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('new_password') ? 'has-error' : '' }}">
                                        <label for="new_password" class="control-label">New Password</label>
                                        <input name="new_password" type="password" class="form-control">
                                        @if ($errors->has('new_password'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('new_password') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('new_password_confirmation') ? 'has-error' : '' }}">
                                        <label for="new_password_confirmation" class="control-label">Confirm New
                                            Password</label>
                                        <input name="new_password_confirmation" type="password" class="form-control">
                                        @if ($errors->has('new_password_confirmation'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

@endsection
