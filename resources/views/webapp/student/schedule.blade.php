@extends('layouts.webapp')
@section('title', 'Schedule Student')
@section('content')

    <div class="col-12">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>
        <h4>Schedule Student</h4>
        @foreach ($students as $student)
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('student.schedule', $student->id) }}">Schedule</a></li>
            </ul>
            @if($studentScheduled)
                <div class="alert alert-primary alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    The student is already scheduled.
                </div>
            @endif

            @include('partials.studentTabs', $data = ['id' => $student->id])
            <div class="card">
                <div class="card-body">
                    @if(count($students) <= 0)
                        <div class="text-center">
                            <p>That student record does not exist.</p>
                        </div>
                    @else
                        <form class="form-horizontal" method="post" action="{{ route('student.schedule.save') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label for="title" class="control-label">Student Name</label>
                                        <input class="form-control" autocomplete="off" type="text" name="title"
                                               value="{{ $student->first_name }} {{ $student->last_name }}">
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                        <label class="control-label">Start Date</label>
                                        <input class="date form-control" autocomplete="off" type="text" id="lessonDate" title="Please select a date" name="start_date"
                                               value="{{ $startDate }}">
                                        @if ($errors->has('start_date'))
                                            <span class="help-block"><strong>{{ $errors->first('start_date') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                                        <label for="start_time" class="control-label">Start Time</label>
                                        <select class="form-control" id="start_time" name="start_time">123
                                            @if(count($allTimes) <= 0)
                                                <option>No availability</option>
                                            @else
                                                @foreach($allTimes as $allTime)
                                                    <option value="{{ Carbon\Carbon::parse($allTime)->format('H:i:s') }}">{{ Carbon\Carbon::parse($allTime)->format('h:i A') }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                        @if ($errors->has('start_time'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('start_time') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
                                        <label for="end_time" class="control-label">Duration</label>
                                        <select class="form-control" id="end_time" name="end_time">
                                            @if(count($allTimes) <= 0)
                                                <option>No availability</option>
                                            @else
                                                <option value="{{ old('end_time') }}">{{ old('end_time') }}</option>
                                                <option value="15">15 minutes</option>
                                                <option value="30">30 minutes</option>
                                                <option value="45">45 minutes</option>
                                                <option value="60">60 minutes</option>
                                            @endif
                                        </select>

                                        @if ($errors->has('end_time'))
                                            <span class="help-block"><strong>{{ $errors->first('end_time') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group{{ $errors->has('recurrence') ? ' has-error' : '' }}">
                                        <label for="end_time" class="control-label">Recurrence</label>
                                        <select class="form-control" id="recurrence" name="recurrence">
                                            <option value="{{ old('recurrence') }}">{{ old('recurrence') }}</option>
                                            <option value="1">One Time</option>
                                            <option value="21">One Month</option>
                                            <option value="365">One Year</option>
                                        </select>

                                        @if ($errors->has('recurrence'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('recurrence') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group{{ $errors->has('color') ? ' has-error' : '' }}">
                                        <label for="end_time" class="control-label">Color</label>
                                        <select class="form-control" id="color" name="color">
                                            <option value="#5499C7" style="background-color: #5499C7; color: white;">Blue</option>
                                            <option value="#CD6155" style="background-color: #CD6155; color: white;">Red</option>
                                            <option value="#A569BD" style="background-color: #A569BD; color: white;">Purple</option>
                                            <option value="#52BE80" style="background-color: #52BE80; color: white;">Green</option>
                                            <option value="#F4D03F" style="background-color: #F4D03F; color: white;">Yellow</option>
                                            <option value="#E59866" style="background-color: #E59866; color: white;">Orange</option>
                                            <option value="#85929E" style="background-color: #85929E; color: white;">Grey</option>
                                        </select>
                                        @if ($errors->has('color'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('color') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input id="student_id" type="hidden" class="form-control" name="student_id" value="{{ $student->id }}">
                            <hr/>
                            <div class="pull-left">
                                @if(count($allTimes) > 1)
                                    <button type="submit" class="btn btn-primary">Schedule</button>
                                @endif
                                <a href="{{ route('student.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @include('partials.addStudent')

@endsection
