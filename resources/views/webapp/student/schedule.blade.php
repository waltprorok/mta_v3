@extends('layouts.webapp')
@section('title', 'Schedule Student')
@section('content')

    <div class="col-12">
        <h4>Schedule Student</h4>
        @foreach ($students as $student)
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                <li class="breadcrumb-item active">Schedule</li>
            </ul>
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
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Name</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Email</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Phone</h6>
                                                </div>
                                                <div class="col-sm-9">
                                                    {{ $student->phone_number }}
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Scheduled</h6>
                                                </div>
                                                @if($studentScheduled)
                                                    <div class="col-sm-9">
                                                        <span class="btn btn-success">
                                                            <i class="fa fa-check"></i>&nbsp;Has Appointment
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="col-sm-9">
                                                        <span class="btn btn-danger">
                                                            <i class="fa fa-times"></i>&nbsp;Needs Appointment
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <hr>
                                            @if($studentScheduled)
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Last Lesson</h6>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @foreach($lastLesson as $lesson)
                                                            @if(isset($lesson->hasOneLesson->start_date))
                                                                <i class="fa fa-clock-o"></i>&nbsp;{{ \Carbon\Carbon::parse($lesson->hasOneLesson->start_date)->format('F j, Y | l | g:i:s A ') }}
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <hr>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
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
                                        <select class="form-control" id="start_time" name="start_time">
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
                                @if(count($allTimes) < 1)
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="end_time" class="control-label">Go To Settings</label>
                                            <a href="{{ route('teacher.hours') }}">
                                                <button type="button" class="btn btn-default">
                                                    <i class="fa fa-clock-o"></i> &nbsp; Click if no appointment times are available
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <input type="hidden" class="form-control" name="student_id" value="{{ $student->id }}">
                            <input type="hidden" name="title" value="{{ $student->first_name }} {{ $student->last_name }}">
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

@endsection
