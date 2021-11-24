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
                <p>From: {{ $message->userFrom->name }}<br />
                    Email: {{ $message->userFrom->email }}<br />
                    Subject: {{ $message->subject }}</p>
                <hr>
                Message: <br /><br />{{ $message->body }}
                <hr>
                <a href="{{ route('message.create', [$message->userFrom->id, $message->subject]) }}" class="btn btn-primary">Reply</a>
                <a href="{{ route('message.delete', $message->id) }}" class="btn btn-danger float-right">Delete</a>
            </div>
        </div>


    </div>

@endsection
