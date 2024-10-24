@extends('layouts.webapp')
@section('title', 'Payments')
@section('content')

    <div class="col-12">
        <h4>Payments</h4>
        <ul class="breadcrumb">
            @if(! Auth::user()->isAdmin())
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Payments</li>
            @endif
        </ul>

        <div id="app">
            <payments></payments>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
