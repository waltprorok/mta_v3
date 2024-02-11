@extends('layouts.webapp')
@section('title', 'Create Message')
@section('content')

    <div class="col-12">
        <h4>Messages</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('message.inbox') }}">Messages</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ul>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('message.send') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="to">To <span class="text-danger">*</span></label>
                                <select class="form-control" name="to" id="to">
                                    @foreach($users as $user)
                                        @if(isset($user->studentTeacher->id))
                                            <option value="{{ $user->studentTeacher->teacher_id }}">
                                                {{ $user->studentTeacher->first_name}}&nbsp;{{ $user->studentTeacher->last_name }}
                                            </option>
                                        @elseif(isset($user->teacher_id))
                                            <option value="{{ $user->teacher_id }}">{{ $user->full_name }}</option>
                                        @else
                                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="subject" placeholder="Enter subject" value="{{ $subject }}">
                                @if ($errors->has('subject'))
                                    <span class="help-block"><strong>{{ $errors->first('subject') }}</strong></span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                                <label for="message">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="message" rows="6"></textarea>
                                @if ($errors->has('message'))
                                    <span class="help-block"><strong>{{ $errors->first('message') }}</strong></span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
