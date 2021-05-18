@extends('layouts.webapp')
@section('title', 'Account')
@section('content')



    <div class="col-12">
        <h4>Contacts</h4>
        <div id="app">
            <div class="card">
                <contacts></contacts>
            </div>
        </div>
    </div>

    <script src="/js/app.js"></script>
@endsection
