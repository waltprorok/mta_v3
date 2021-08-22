@extends('layouts.webapp')
@section('title', 'Lessons')
@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

    <div class="col-12">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>
        <h4>Lessons</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('student.lessons') }}">Lessons</a></li>
        </ul>
        <div class="card">
            <div class="card-body">
                <table class="table" id="dtLessonsIndex">
                    <thead class="thead">
                    <tr>
                        <th scope="col">Completed</th>
                        <th scope="col">Title</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Duration</th>
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
                                <td><input type="checkbox" {{ $lesson->complete == '1' ? 'checked' : '' }} class="select-all checkbox" name="select-all" value="1"/></td>
                                <td>{{ $lesson->title }}</td>
                                <td>{{ Carbon\Carbon::parse($lesson->start_date)->format('m-d-Y h:i A') }}</td>
                                <td>{{ Carbon\Carbon::parse($lesson->end_date)->format('m-d-Y h:i A') }}</td>
                                <td>{{ $lesson->interval }} minutes</td>
                                <td><a href="{{ route('student.schedule.edit', [$lesson->student_id, $lesson->id])}}" class="btn btn-primary" role="button" title="edit"><i class="fa fa-edit"></i></a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('partials.addStudent')

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dtLessonsIndex').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

@endsection


