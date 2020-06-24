@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')

    <div id="contact" class="section md-padding">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title">Contact Us</h2>
                    <h3>Have a question? Contact us at Music Teacher's Aid.</h3>
                </div>

                <div class="col-md-8 col-md-offset-2">

                    <form action="{{ route('contact') }}" method="post">
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">Name</label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">Email</label>
                            <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('subject') ? ' has-error' : '' }}">
                            <label for="subject" class="control-label">Subject</label>
                            <input name="subject" type="text" class="form-control" value="{{ old('subject') }}">
                            @if ($errors->has('subject'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('message') ? ' has-error' : '' }}">
                            <label for="message" class="control-label">Message</label>
                            <textarea name="message" rows="6" class="form-control">{{ old('message') }}</textarea>
                            @if ($errors->has('message'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                            {!! app('captcha')->display(); !!}
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <button class="main-btn" type="submit">Send message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection