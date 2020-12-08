@extends('layouts.webapp')
@section('title', 'Dashboard')
@section('content')

    <div class="col-12">

        <div class="row">
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <span class="h4 d-block font-weight-normal mb-2">{{ Auth::user()->students->where('status', '==', 'Active')->count() }}</span>
                            <span class="font-weight-light">Active Students</span>
                        </div>

                        <div class="h2 text-muted">
                            <i class="fa fa-users" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <span class="h4 d-block font-weight-normal mb-2">$3,200</span>
                            <span class="font-weight-light">Monthly Income</span>
                        </div>

                        <div class="h2 text-muted">
                            <i class="fa fa-money" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <span class="h4 d-block font-weight-normal mb-2">{{ Auth::user()->lessons->whereBetween('start_date', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->count() }}</span>
                            <span class="font-weight-light">Lessons This Week</span>
                        </div>

                        <div class="h2 text-muted">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <span class="h4 d-block font-weight-normal mb-2">3</span>
                            <span class="font-weight-light">Open Time Blocks</span>
                        </div>

                        <div class="h2 text-muted">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Completed Lessons
                    </div>

                    <div class="card-body p-0">
                        <div class="p-4">
                            <canvas id="line-chart" width="100%" height="20"></canvas>
                        </div>

{{--                        <div class="justify-content-around mt-4 p-4 bg-light d-flex border-top d-md-down-none">--}}
{{--                            <div class="text-center">--}}
{{--                                <div class="text-muted small">Total Traffic</div>--}}
{{--                                <div>12,457 Users (40%)</div>--}}
{{--                            </div>--}}

{{--                            <div class="text-center">--}}
{{--                                <div class="text-muted small">Banned Users</div>--}}
{{--                                <div>95,333 Users (5%)</div>--}}
{{--                            </div>--}}

{{--                            <div class="text-center">--}}
{{--                                <div class="text-muted small">Page Views</div>--}}
{{--                                <div>957,565 Pages (50%)</div>--}}
{{--                            </div>--}}

{{--                            <div class="text-center">--}}
{{--                                <div class="text-muted small">Total Downloads</div>--}}
{{--                                <div>957,565 Files (100 TB)</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
