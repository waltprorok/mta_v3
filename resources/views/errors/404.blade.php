@extends('layouts.app')
@section('title', '404')
@section('code', '403')
@section('content')

    <div id="pricing" class="section lg-padding">
        <!-- Container -->
        <div class="container sm-padding">
            <!-- Row -->
            <div class="row">
                <!-- Section header -->
                <div class="section-header text-center">
                    <h2 class="title">404 Page</h2>
                    <div class="lead">The page you are looking for was not found.</div>
                    <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
                </div>
                <!-- /Section header -->
            </div>
            <!-- Row -->
        </div>
        <!-- /Container -->
    </div>

@endsection
