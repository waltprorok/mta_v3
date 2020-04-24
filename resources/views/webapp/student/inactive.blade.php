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
            <table class="table table-responsive-md" id="#studentTable">
                <thead class="thead-dark">
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
                @if(count($inactives) == null)
                    <tr>
                        <td>No inactive students at this time.</td>
                    </tr>
                @else
                    @foreach($inactives as $inactive)
                        <tr>
                            <td>{{ $inactive->first_name }}</td>
                            <td>{{ $inactive->last_name }}</td>
                            <td>{{ $inactive->email }}</td>
                            <td>{{ $inactive->phone }}</td>
                            <td>{{ $inactive->instrument }}</td>
                            <td>{{ $inactive->status }}</td>
                            <th scope="row">
                                <a href="{{ route('student.edit', $inactive->id)}}"
                                   class="btn btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                            </th>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="float-right">{{ $inactives->links() }}</div>
    </div>

    @include('partials.addStudent')

@endsection
