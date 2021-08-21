@extends('layouts.webapp')
@section('title', 'Schedule Student')
@section('content')

    <div class="col-12">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>
        <h4>Edit Student Schedule</h4>
        @foreach ($lessons as $lesson)
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item"><a href="{{ route('calendar.index') }}">Calendar</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('student.schedule.edit', [$lesson->student_id, $lesson->id]) }}">Edit</a></li>
        </ul>
            @include('partials.studentTabs', $data = ['id' => $lesson->student_id])
            <div class="card">
                <div class="card-body">
                    @if(count($lessons) <= 0)
                        <div class="text-center">
                            <p>That student record does not exist.</p>
                        </div>
                    @else
                        <form class="form-horizontal" method="post" action="{{ route('student.schedule.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label for="name" class="control-label">Student Name</label>
                                        <input type="text" class="form-control" autocomplete="off" name="title" value="{{ $lesson->title }}">
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
                                        @if($startDate)
                                            <input class="date form-control" title="Please select a date" autocomplete="off" type="text" id="editLessonDate" name="start_date" value="{{ $startDate }}">
                                        @else
                                            <input class="date form-control" title="Please select a date" autocomplete="off" type="text" id="editLessonDate" name="start_date" value="{{ date('Y-m-d', strtotime($lesson->start_date)) }}">
                                        @endif
                                        @if ($errors->has('start_date'))
                                            <span class="help-block"><strong>{{ $errors->first('start_date') }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                                        <label for="start_time" class="control-label">Start Time</label>
                                        <select class="form-control" id="start_time" name="start_time">
                                            <option value="{{ date('H:i:s', strtotime($lesson->start_date)) }}">{{ date('h:i A', strtotime($lesson->start_date)) }}</option>
                                            @if(old('start_time'))
                                                <option value="{{ old('start_time') }}">{{ Carbon\Carbon::parse(old('start_time'))->format('h:i A') }}</option>
                                            @endif
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
                                            <option value="{{ old('end_time') }}">{{ old('end_time') }}</option>
                                            <option value="15">15 minutes</option>
                                            <option value="30">30 minutes</option>
                                            <option value="45">45 minutes</option>
                                            <option value="60">60 minutes</option>
                                        </select>

                                        @if ($errors->has('end_time'))
                                            <span class="help-block"><strong>{{ $errors->first('end_time') }}</strong></span>
                                        @endif
                                    </div>
                                </div>

{{--                                <div class="col-sm-3">--}}
{{--                                    <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">--}}
{{--                                        <label for="end_time" class="control-label">End Time</label>--}}
{{--                                        <select class="form-control" id="end_time" name="end_time">--}}
{{--                                            <option value="{{ date('H:i:s', strtotime($lesson->end_date)) }}">{{ date('h:i A', strtotime($lesson->end_date)) }}</option>--}}
{{--                                            <option value="{{ old('end_time') }}">{{ Carbon\Carbon::parse(old('end_time'))->format('h:i A') }}</option>--}}
{{--                                            @if(count($allTimes) <= 0)--}}
{{--                                                <option>No availability</option>--}}
{{--                                            @else--}}
{{--                                                @foreach($allTimes as $allTime)--}}
{{--                                                    <option value="{{ Carbon\Carbon::parse($allTime)->format('H:i:s') }}">{{ Carbon\Carbon::parse($allTime)->format('h:i A') }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            @endif--}}
{{--                                        </select>--}}

{{--                                        @if ($errors->has('end_time'))--}}
{{--                                            <span class="help-block">--}}
{{--                                        <strong>{{ $errors->first('end_time') }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group{{ $errors->has('color') ? ' has-error' : '' }}">
                                        <label for="color" class="control-label">Color</label>
                                        <select class="form-control" id="color" name="color">
                                            <option value="{{ $lesson->color }}" style="background-color: {{ $lesson->color }}; color: white;">Current Color</option>
                                            <option value="#5499C7" style="background-color: #5499C7; color: white;">Blue</option>
                                            <option value="#CD6155" style="background-color: #CD6155; color: white;">Red</option>
                                            <option value="#A569BD" style="background-color: #A569BD; color: white;">Purple</option>
                                            <option value="#52BE80" style="background-color: #52BE80; color: white;">Green</option>
                                            <option value="#F4D03F" style="background-color: #F4D03F; color: white;">Yellow</option>
                                            <option value="#E59866" style="background-color: #E59866; color: white;">Orange</option>
                                            <option value="#85929E" style="background-color: #85929E; color: white;">Grey</option>
                                        </select>

                                        @if ($errors->has('color'))
                                            <span class="help-block"><strong>{{ $errors->first('color') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <input id="id" type="hidden" class="form-control" name="id" value="{{ $lesson->id }}">
                            <input id="student_id" type="hidden" class="form-control" name="student_id" value="{{ $lesson->student_id }}">
{{--                            <input id="interval" type="hidden" class="form-control" name="interval" value="{{ $lesson->interval }}">--}}

                            <div class="pull-left">
                                @if(count($allTimes) > 1)
                                    <button type="submit" name="action" value="update" class="btn btn-primary">
                                        Update
                                    </button>
                                    <button type="submit" name="action" value="updateAll" class="btn btn-warning">
                                        Update All
                                    </button>
                                @endif
                                <a href="{{ route('student.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>

                            <div class="pull-right">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myDeleteModal">Delete</button>
                                <button type="button" class="btn btn-danger-outline" data-toggle="modal" data-target="#myDeleteModalAll">Delete All</button>
                            </div>

                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @include('partials.addStudent')

    <!-- Modal -->
    <div class="modal fade" id="myDeleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Delete Lesson?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this scheduled lesson?</p>
                </div>

                <div class="modal-footer">
                    <form action="{{ route('student.schedule.delete', $lesson->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        &nbsp;<button type="submit" name="action" value="delete" class="btn btn-danger pull-right">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Modal -->

    <!-- Modal -->
    <div class="modal fade" id="myDeleteModalAll" tabindex="-1" role="dialog" aria-labelledby="myModalLabelAll">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabelAll">Delete all the Remaining Lessons?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete all the remaining scheduled lessons?</p>
                </div>

                <div class="modal-footer">
                    <form action="{{ route('student.schedule.deleteAll', $lesson->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        &nbsp;<button type="submit" name="action" value="deleteAll" class="btn btn-danger pull-right">Delete All</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Modal -->

@endsection
