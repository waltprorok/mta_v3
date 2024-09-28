@extends('layouts.webapp')
@section('title', 'Support')
@section('content')

    <div class="col-12">
        <h4>Support</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Support</li>
        </ul>

        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('support') }}" method="post">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('subject') ? ' has-error' : '' }}">
                                <label for="subject" class="control-label">Subject</label>
                                <input name="subject" type="text" class="form-control" value="{{ old('subject') }}">
                                @if ($errors->has('subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('message') ? ' has-error' : '' }}">
                                <label for="message" class="control-label">Describe your issue</label>
                                <textarea name="message" rows="12" class="form-control">{{ old('message') }}</textarea>
                                @if ($errors->has('message'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="pull-left">
                        <button class="btn btn-primary" type="submit">Send</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
