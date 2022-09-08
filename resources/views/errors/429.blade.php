@extends('layouts.app')
@section('title', 'Too Many Requests')
@section('code', '429')
@section('content')

    <div id="pricing" class="section lg-padding">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
                <!-- Section header -->
                <div class="section-header text-center">
                    <h2 class="title">429 Page</h2>
                    <div class="lead">Too Many Requests.</div>
                    <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
                </div>
                <!-- /Section header -->
            </div>
            <!-- Row -->
        </div>
        <!-- /Container -->
    </div>

@endsection
