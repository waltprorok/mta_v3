@extends('layouts.webapp')
@section('title', 'Plans')
@section('content')

    <div class="col-12">
        <h4>Stripe Billing Plans</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"></li>
        </ul>

        <div id="app">
            <plans></plans>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
