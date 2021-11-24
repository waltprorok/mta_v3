@extends('layouts.webapp')
@section('title', 'Messages')
@section('content')

    <div class="col-12">
        <h4>Messages</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('message.inbox') }}">Messages</a></li>
            <li class="breadcrumb-item active">Sent</li>
        </ul>

        <div class="card">
            <div class="card-body">
                @if (count($messages) > 0)
                    <ul class="list-group">
                        @foreach($messages as $message)
                            <li class="list-group-item">To: {{ $message->userTo->first_name }}, {{ $message->userTo->email }} |
                                Subject: {{ $message->subject }}
                                @if ($message->read)
                                    <span class="badge badge-success float-right">READ</span></li>
                            @endif
                        @endforeach

                    </ul>
                @else
                    No Messages
                @endif
            </div>
        </div>
    </div>

@endsection
