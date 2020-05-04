@extends('layouts.webapp')
@section('title', 'Update Password')
@section('content')

    <div class="col-12">
        <h2>Studio Settings</h2>
        @include('partials.teacherTabs')
        <div class="card">
            <div class="card-header bg-light">Update Password</div>
            <div class="card-body">
                <form class="form-horizontal" method="POST" action="{{ route('pw.studioPW') }}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                <label for="new-password" class="control-label">Current
                                    Password</label>
                                <input id="current-password" type="password" class="form-control"
                                       name="current-password">

                                @if ($errors->has('current-password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                                <label for="new-password" class="control-label">New Password</label>
                                <input id="new-password" type="password" class="form-control"
                                       name="new-password">

                                @if ($errors->has('new-password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="new-password-confirm" class="control-label">Confirm New
                                    Password</label>
                                <input id="new-password-confirm" type="password" class="form-control"
                                       name="new-password_confirmation">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Change Password
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>

                </form>
            </div>
        </div>
    </div>

@endsection
