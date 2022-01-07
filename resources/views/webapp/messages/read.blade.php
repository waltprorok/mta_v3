@extends('layouts.webapp')
@section('title', 'Read Message')
@section('content')

    <div class="col-12">
        <h4>Messages</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('message.inbox') }}">Messages</a></li>
            <li class="breadcrumb-item active">Read</li>
        </ul>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <b>From:</b>&emsp;&emsp;{{ $message->userFrom->first_name }} {{ $message->userFrom->last_name }} - {{ $message->userFrom->email }}
                                <br/>
                                <b>To:</b>&nbsp;&emsp;&emsp;&emsp;{{ $message->userTo->first_name }} {{ $message->userTo->last_name }} - {{ $message->userTo->email }}
                                <br/>
                                <b>Subject:</b>&emsp;{{ $message->subject }}
                                <br/>
                                <b>Sent:</b>&emsp;&emsp;&ensp;{{ $message->created_at->format('F d Y h:m a') }}
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="textarea"><b>Message:</b></label>
                                    <textarea id="textarea" class="form-control" rows="10" disabled>{{ $message->body }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                @if ($message->userFrom->id != Auth::id())
                    <a href="{{ route('message.create', [$message->userFrom->id, $message->subject]) }}" class="btn btn-primary">Reply</a>
                @endif
                <a href="{{ route('message.delete', $message->id) }}" class="btn btn-danger float-right">Delete</a>
            </div>
        </div>
    </div>

@endsection
