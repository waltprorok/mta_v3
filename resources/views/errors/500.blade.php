@extends('layouts.app')
@section('title', 'Server Error')
@section('code', '500')
@section('content')

    <div id="pricing" class="section lg-padding">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
                <!-- Section header -->
                <div class="section-header text-center">
                    <h2 class="title">500 Page</h2>
                    <div class="lead">Server Error.</div>
                    <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
                </div>
                <!-- /Section header -->
            </div>
            <!-- Row -->
        </div>
        <!-- /Container -->
    </div>

@endsection
