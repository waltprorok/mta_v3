@extends('layouts.webapp')
@section('title', 'Sent Messages')
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
                    <table class="table table-hover">
                        <thead class="thead">
                        <tr>
                            <th scope="col">Read</th>
                            <th scope="col">To</th>
                            <th scope="col">Email</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Sent</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr class="table-row" data-href="{{route('message.read', $message->id)}}">
                                <th scope="row">@if ($message->read) <span class="badge badge-success">READ</span> @endif</th>
                                <td>{{ $message->userFrom->first_name }}&nbsp;{{ $message->userFrom->last_name }}</td>
                                <td>{{ $message->userTo->email }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>@if (isset($message->created_at)) {{$message->created_at->format('M d')}} @endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    No Messages
                @endif
            </div>
        </div>
    </div>

@endsection
