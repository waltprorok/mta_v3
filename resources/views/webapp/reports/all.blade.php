@extends('layouts.webapp')
@section('title', 'All Reports')
@section('content')

    <div class="col-12">
        <h2>All Reports</h2>
        <div class="card">
            <div class="card-body">

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
            </div>
        </div>

@endsection