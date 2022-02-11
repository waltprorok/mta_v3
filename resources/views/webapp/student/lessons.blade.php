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
            <li class="breadcrumb-item active">Lessons</li>
        </ul>
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" method="post" action="{{ route('student.lessons.update') }}">
                    @csrf
                    @method('PUT')
                    <table class="table table-condensed table-hover table-responsive-md" id="dtLessonsIndex">
                        <thead class="thead">
                        <tr>
                            <th scope="col" data-orderable="false">Completed</th>
                            <th scope="col">Title</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Duration</th>
                            <th scope="col" data-orderable="false">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($lessons as $lesson)
                            <tr>
                                <td>
                                    <form class="form-horizontal" method="post" action="{{ route('student.lessons.update') }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="checkbox" {{ $lesson->complete == '1' ? 'checked' : '' }} class="checkbox" name="completed"/>
                                        <input type="hidden" name="id" value="{{ $lesson->id }}"/>
                                        <input type="hidden" name="student_id" value="{{ $lesson->student_id }}"/>
                                        <button type="submit" name="save" class="btn btn-outline-primary" style="margin-left: 24px;">
                                            Save
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $lesson->title }}</td>
                                <td>{{ Carbon\Carbon::parse($lesson->start_date)->format('m-d-Y h:i A') }}</td>
                                <td>{{ Carbon\Carbon::parse($lesson->end_date)->format('m-d-Y h:i A') }}</td>
                                <td>{{ $lesson->interval }} minutes</td>
                                <td><a href="{{ route('student.schedule.edit', [$lesson->student_id, $lesson->id])}}" class="btn btn-sm btn-primary" role="button" title="edit"><i
                                                class="fa fa-edit"></i></a></td>
                            </tr>
                        @empty
                            <tr>
                                <td>No lessons have been scheduled at this time.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <button type="submit" name="save" class="btn btn-default">
                        Save
                    </button>
                </form>
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


