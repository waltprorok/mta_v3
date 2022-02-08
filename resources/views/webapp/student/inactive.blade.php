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
            <li class="breadcrumb-item active">Inactive</li>
        </ul>
        @include('partials.studentListTabs')
        <div class="card">
            <div class="card-body">
                <table class="table" id="dtStudentsInactiveIndex">
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
                    @forelse($inactives as $inactive)
                        <tr>
                            <td>{{ $inactive->first_name }}</td>
                            <td>{{ $inactive->last_name }}</td>
                            <td>{{ $inactive->phone_number }}</td>
                            <td>{{ $inactive->email }}</td>
                            <td>{{ $inactive->instrument }}</td>
                            <th scope="row">
                                <a href="{{ route('student.edit', $inactive->id)}}"
                                   class="btn btn-sm btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                            </th>
                        </tr>
                    @empty
                        <tr>
                            <td>No inactive students at this time.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforelse
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
            $('#dtStudentsInactiveIndex').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

@endsection
