@extends('layouts.app')
@section('title', 'Page Expired')
@section('code', '419')
@section('content')

    <div id="pricing" class="section lg-padding">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
                <!-- Section header -->
                <div class="section-header text-center">
                    <h2 class="title">419 Page</h2>
                    <div class="lead">The page you are looking for has expired.</div>
                    <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
                </div>
                <!-- /Section header -->
            </div>
            <!-- Row -->
        </div>
        <!-- /Container -->
    </div>

@endsection
