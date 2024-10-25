@extends('layouts.webapp')
@section('title', 'Billing')
@section('content')

    <div class="col-12">
        @if(Auth::user()->isTeacher())
            <h4>Billing</h4>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Billing</li>
            </ul>
        @endif
        <div id="app">
            <payments></payments>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
