@extends('layouts.webapp')
@section('title', 'Schedule Student')
@section('content')

    <div class="col-12">

        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>

        <h2>Schedule Student</h2>

        @foreach ($students as $student)
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
                                        <label for="Title" class="control-label">Title</label>
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
                                        <label for="Title" class="control-label">Start Date</label>
                                        <input class="date form-control" autocomplete="off" type="text" id="lessonDate"
                                               name="start_date" value="{{ old('start_date') }}">
                                        @if ($errors->has('start_date'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                                        <label for="start_time" class="control-label">Start Time</label>
                                        <select class="form-control" id="start_time" name="start_time">
                                            <option value="{{ old('start_time') }}">{{ old('start_time') }}</option>
                                            <option value="09:00:00">9:00</option>
                                            <option value="09:30:00">9:30</option>
                                            <option value="10:00:00">10:00</option>
                                            <option value="10:30:00">10:30</option>
                                            <option value="11:00:00">11:00</option>
                                            <option value="11:30:00">11:30</option>
                                            <option value="12:00:00">12:00</option>
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
                                        <label for="end_time" class="control-label">End Time</label>
                                        <select class="form-control" id="end_time" name="end_time">
                                            <option value="{{ old('end_time') }}">{{ old('end_time') }}</option>
                                            <option value="09:00:00">9:00</option>
                                            <option value="09:30:00">9:30</option>
                                            <option value="10:00:00">10:00</option>
                                            <option value="10:30:00">10:30</option>
                                            <option value="11:00:00">11:00</option>
                                            <option value="11:30:00">11:30</option>
                                            <option value="12:00:00">12:00</option>
                                        </select>

                                        @if ($errors->has('end_time'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('end_time') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <input id="student_id" type="hidden" class="form-control" name="student_id"
                                   value="{{ $student->id }}">

                            <div class="pull-left">
                                <button type="submit" class="btn btn-primary">
                                    Schedule
                                </button>
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
