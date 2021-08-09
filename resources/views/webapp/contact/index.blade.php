@extends('layouts.webapp')
@section('title', 'Admin Contacts')
@section('content')

    <div class="col-12">
        <h4>Contacts</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('webapp.contact.index') }}">Contacts</a></li>
        </ul>

        <div id="app">
            <contacts></contacts>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
@endsection
