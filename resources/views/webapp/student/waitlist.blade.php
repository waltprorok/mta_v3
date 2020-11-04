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
            <table class="table table-striped" id="#studentTable">
                <thead class="thead">
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Instrument</th>
                    <th scope="col">Status</th>
                    <th scope="col">Update</th>
                </tr>
                </thead>
                <tbody>
                @if(count($waitlists) == null)
                    <tr>
                        <td>No students on wait list.</td>
                    </tr>
                @else
                    @foreach($waitlists as $waitlist)
                        <tr>
                            <td>{{ $waitlist->first_name }}</td>
                            <td>{{ $waitlist->last_name }}</td>
                            <td>{{ $waitlist->phone_number }}</td>
                            <td>{{ $waitlist->email }}</td>
                            <td>{{ $waitlist->instrument }}</td>
                            <td>{{ $waitlist->status }}</td>
                            <th scope="row">
                                <a href="{{ route('student.edit', $waitlist->id)}}"
                                   class="btn btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                            </th>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="float-right">{{ $waitlists->links() }}</div>
    </div>

    @include('partials.addStudent')

@endsection
