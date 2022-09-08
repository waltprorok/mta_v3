@extends('layouts.app')
@section('title', 'Unauthorized')
@section('code', '401')
@section('content')

    <div id="pricing" class="section lg-padding">
        <!-- Container -->
        <div class="container sm-padding">
            <!-- Row -->
            <div class="row">
                <!-- Section header -->
                <div class="section-header text-center">
                    <h2 class="title">401 Page</h2>
                    <div class="lead">User is unauthorized to view this page.</div>
                    <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
                </div>
                <!-- /Section header -->
            </div>
            <!-- Row -->
        </div>
        <!-- /Container -->
    </div>

@endsection
