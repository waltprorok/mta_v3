@extends('layouts.webapp')
@section('title', 'All Reports')
@section('content')

    <div class="col-12">
        <h3>All Reports</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('reports.all') }}">Reports</a></li>
        </ul>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">Bar Charts</div>

                    <div class="card-body">
                        <canvas id="bar-chart" width="100%" height="50"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">Line Charts</div>

                    <div class="card-body">
                        <canvas id="line-chart" width="100%" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">Radar Chart</div>

                    <div class="card-body">
                        <canvas id="radar-chart" width="100%" height="50"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">Pie Chart</div>

                    <div class="card-body">
                        <canvas id="pie-chart" width="100%" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>

@endsection
