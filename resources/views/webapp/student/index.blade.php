@extends('layouts.webapp')
@section('title', 'Students')
@section('content')

    <div class="col-12">
        <h3>Students</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('student.index') }}">Students</a></li>
        </ul>

        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>

        @include('partials.studentListTabs')
        <div class="card">
            <table class="table table-striped" id="#studentTable">
                <thead class="thead">
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Instrument</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($students) == null)
                    <tr>
                        <td>No active students at this time.</td>
                    </tr>
                @else
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->phone_number }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->instrument }}</td>
                            <td>{{ $student->status }}</td>
                            <th scope="row">
                                <a href="{{ route('student.edit', $student->id) }}" class="btn btn-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('student.profile', $student->id) }}" class="btn btn-success" role="button" title="profile"><i class="fa fa-user"></i></a>
                                <a href="{{ route('student.schedule', $student->id) }}" class="btn btn-warning" role="role" title="schedule"><i class="fa fa-calendar"></i></a>
                            </th>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="float-right">{{ $students->links() }}</div>
    </div>

    @include('partials.addStudent')

@endsection
