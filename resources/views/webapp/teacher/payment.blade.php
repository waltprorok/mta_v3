@extends('layouts.webapp')
@section('title', 'Payment Processing')
@section('content')


    <div class="col-12">
        <h4>Studio Settings</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('teacher.payment') }}">Payment</a></li>
        </ul>

        @include('partials.teacherTabs')
        <div class="card">
            <div class="card-header bg-light">Payment Processing</div>
            <div class="card-body">

                <form class="form-horizontal" method="post" action="{{ route('student.schedule.update') }}">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                                <label for="rate" class="control-label">Hourly Rate</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="text" class="form-control" name="rate">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                    @if ($errors->has('rate'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <div class="pull-left">
                            <button type="submit" name="action" value="save" class="btn btn-primary">
                                Save
                            </button>
{{--                            <button type="submit" name="action" value="updateAll" class="btn btn-warning">--}}
{{--                                Update All--}}
{{--                            </button>--}}
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
