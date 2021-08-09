@extends('layouts.webapp')
@section('title', 'Lessons')
@section('content')

    <div class="col-12">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>
        <h4>Lessons</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('student.lessons') }}">Lessons</a></li>
        </ul>
        <div class="card">
            <table class="table table-striped" id="#studentTable">
                <thead class="thead">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($lessons) == null)
                    <tr>
                        <td>No lessons have been scheduled at this time.</td>
                    </tr>
                @else
                    @foreach($lessons as $lesson)
                        <tr>
                            <td><input type="checkbox" class="select-all checkbox" name="select-all" value="{{ $lesson->id }}"/></td>
                            <td>{{ $lesson->title }}</td>
                            <td>{{ Carbon\Carbon::parse($lesson->start_date)->format('m-d-Y h:i A') }}</td>
                            <td>{{ Carbon\Carbon::parse($lesson->end_date)->format('m-d-Y h:i A') }}</td>
                            <td><a href="{{ route('student.schedule.edit', [$lesson->student_id, $lesson->id])}}" class="btn btn-primary" role="button" title="edit"><i class="fa fa-edit"></i></a></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="float-right">{{ $lessons->links() }}</div>
    </div>

    @include('partials.addStudent')

@endsection
