@extends('layouts.webapp')
@section('title', 'Students')
@section('content')

    <div class="col-12">

        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>

        <h2>Students</h2>
        @include('partials.studentListTabs')
        <div class="card">
            <div class="card-body">
                <table class="table" id="#studentTable">
                    <thead class="thead">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Instrument</th>
                        <th scope="col">Status</th>
                        <th scope="col">Update</th>
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
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ $student->instrument }}</td>
                                <td>{{ $student->status }}</td>
                                <th scope="row">
                                    <a href="{{ route('student.edit', $student->id)}}"
                                       class="btn btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i>
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="float-right">{{ $students->links() }}</div>
    </div>

    @include('partials.addStudent')

@endsection
