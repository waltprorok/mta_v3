@extends('layouts.webapp')
@section('title', 'Students')
@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

    <div class="col-12">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>
        <h4>Students</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item active">Wait List</li>
        </ul>
        @include('partials.studentListTabs')
        <div class="card">
            <div class="card-body">
                <table class="table" id="dtStudentWaitlistIndex">
                    <thead class="thead">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col" data-orderable="false">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Instrument</th>
                        <th scope="col" data-orderable="false">Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($waitlists) == 0)
                        <tr>
                            <td>No students on the wait list at this time.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @else
                        @foreach($waitlists as $waitlist)
                            <tr>
                                <td>{{ $waitlist->first_name }}</td>
                                <td>{{ $waitlist->last_name }}</td>
                                <td>{{ $waitlist->phone_number }}</td>
                                <td>{{ $waitlist->email }}</td>
                                <td>{{ $waitlist->instrument }}</td>
                                <th scope="row">
                                    <a href="{{ route('student.edit', $waitlist->id)}}"
                                       class="btn btn-sm btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                                </th>
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
            $('#dtStudentWaitlistIndex').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

@endsection
