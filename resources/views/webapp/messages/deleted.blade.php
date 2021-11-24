@extends('layouts.webapp')
@section('title', 'Deleted Messages')
@section('content')

    <div class="col-12">
        <h4>Messages</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('message.inbox') }}">Messages</a></li>
            <li class="breadcrumb-item active">Deleted</li>
        </ul>

        <div class="card">
            <div class="card-body">
                @if (count($messages) > 0)
                    <ul class="list-group">
                        @foreach($messages as $message)
                            <li class="list-group-item">
                                Deleted | From: {{ $message->userFrom->name }}, {{ $message->userFrom->email }} |
                                Subject: {{ $message->subject }}
                                <a href="{{ route('message.return', $message->id) }}" class="btn btn-sm btn-info float-right">Return message to inbox</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    No Messages
                @endif
            </div>
        </div>
    </div>

@endsection
