@extends('layouts.app')
@section('title', 'Forbidden')
@section('code', '403')
@section('content')

    <div id="pricing" class="section lg-padding">
        <!-- Container -->
        <div class="container sm-padding">
            <!-- Row -->
            <div class="row">
                <!-- Section header -->
                <div class="section-header text-center">
                    <h2 class="title">403 Page</h2>
                    <div class="lead">This page is forbidden.</div>
                    <a href="{{ route('home') }}" class="btn btn-link">Back to Home</a>
                </div>
                <!-- /Section header -->
            </div>
            <!-- Row -->
        </div>
        <!-- /Container -->
    </div>

@endsection
