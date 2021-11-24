@extends('layouts.webapp')
@section('title', 'Messages')
@section('content')

    <div class="col-12">
        <h4>Messages</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Inbox</li>
        </ul>

        <div class="card">
            <div class="card-body">
                @if (count($messages) > 0)
                    <table class="table">
                        <thead class="thead">
                        <tr>
                            <th scope="col">Read</th>
                            <th scope="col">From</th>
                            <th scope="col">Email</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Sent</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                                <tr>
                                    <th scope="row">@if ($message->read) <i class="fa fa-check"></i> @endif</th>
                                    <td><a href="{{ route('message.read', $message->id) }}">{{ $message->userFrom->first_name }}&nbsp;{{ $message->userFrom->last_name }}</a></td>
                                    <td>{{ $message->userFrom->email }}</td>
                                    <td>{{ $message->subject }}</td>
                                    <td>@if (isset($message->created_at)) {{$message->created_at->format('M d')}} @endif</td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>

                <hr>
                    <ul class="list-group">
                        @foreach($messages as $message)
                            <a href="{{ route('message.read', $message->id) }}">
                                <li class="list-group-item">
                                    <strong>{{ $message->userFrom->first_name }}&nbsp;{{ $message->userFrom->last_name }} &emsp; &emsp; {{ $message->userFrom->email }}
                                        &emsp; &emsp; {{ $message->subject }} @if (isset($message->created_at)) &emsp; &emsp; {{$message->created_at->format('M d')}} @endif
                                    </strong>
                                </li>
                            </a>
                        @endforeach
                    </ul>
                @else
                    No Messages
                @endif
            </div>
        </div>


    </div>

@endsection
