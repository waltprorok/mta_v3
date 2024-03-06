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

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <table class="table table-sm m-0">
                                        <tbody>
                                        <tr>
                                            <th class="border-0" style="padding: .0rem;">From:</th>
                                            <td class="border-0" style="padding: .0rem;"><strong>{{ $message->userFrom->full_name }}</strong> | {{ $message->userFrom->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="border-0" style="padding: .0rem;">To:</th>
                                            <td class="border-0" style="padding: .0rem;">{{ $message->userTo->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="border-0" style="padding: .0rem;">Subject:</th>
                                            <td class="border-0" style="padding: .0rem;">{{ $message->subject }}</td>
                                        </tr>
                                        <tr>
                                            <th class="border-0" style="padding: .0rem;">Sent:</th>
                                            <td class="border-0" style="padding: .0rem;">{{ $message->created_at->format('F d, Y g:m a') }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="textarea"><b>Message:</b></label>
                                        <textarea id="textarea" class="form-control" rows="12" disabled>{{ $message->body }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    @if ($message->userFrom->id != Auth::id())
                        <a href="{{ route('message.reply', [$message->userFrom->id, $message->subject]) }}" class="btn btn-primary">Reply</a>
                    @endif
                    <a href="{{ route('message.delete', $message->id) }}" class="btn btn-danger float-right">Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection
