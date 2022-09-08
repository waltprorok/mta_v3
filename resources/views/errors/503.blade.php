@extends('layouts.app')
@section('title', 'Service Unavailable')
@section('message', __($exception->getMessage() ?: 'Service Unavailable'))
@section('code', '503')
@section('content')

    <div id="pricing" class="section lg-padding">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
                <!-- Section header -->
                <div class="section-header text-center">
                    <h2 class="title">503 Page</h2>
                    <div class="lead">Service Unavailable.</div>
                    <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
                </div>
                <!-- /Section header -->
            </div>
            <!-- Row -->
        </div>
        <!-- /Container -->
    </div>

@endsection
