@extends('layouts.webapp')
@section('title', 'Students')
@section('content')

    <div class="col-12">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>
        <h4>Students</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('student.leads') }}">Leads</a></li>
        </ul>
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
                @if(count($leads) == null)
                    <tr>
                        <td>No student leads at this time.</td>
                    </tr>
                @else
                    @foreach($leads as $lead)
                        <tr>
                            <td>{{ $lead->first_name }}</td>
                            <td>{{ $lead->last_name }}</td>
                            <td id="phone">{{ $lead->phone_number }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->instrument }}</td>
                            <td>{{ $lead->status }}</td>
                            <th scope="row">
                                <a href="{{ route('student.edit', $lead->id)}}"
                                   class="btn btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                            </th>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div class="float-right">{{ $leads->links() }}</div>
    </div>

    @include('partials.addStudent')

@endsection
