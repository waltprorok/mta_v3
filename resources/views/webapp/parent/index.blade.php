@extends('layouts.webapp')
@section('title', 'Parent')
@section('content')

    <div class="col-12">

        <h4>Parent</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Parent</li>
        </ul>
        @include('partials.studentListTabs')
        <div class="card">
            <div class="card-body">
                <table class="table table-condensed table-hover table-responsive-md">
                    <thead class="thead">
                    <tr>
                        <th scope="col" data-orderable="false">Scheduled</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Instrument</th>
                        <th scope="col" data-orderable="false">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(count($parent->parentOfStudent) == null)
                        <tr>
                            <td>No student.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @else
                        @foreach($parent->parentOfStudent as $student)
                            <tr>
                                @if ($student->hasOneLesson)
                                    <td class="text-center">
                                        <span class="btn btn-sm btn-success btn-rounded">
                                            <i class="fa fa-check"></i>
                                        </span>
                                    </td>
                                @else
                                    <td class="text-center">
                                        <span class="btn btn-sm btn-danger btn-rounded">
                                            <i class="fa fa-times"></i>
                                        </span>
                                    </td>
                                @endif
                                <td>{{ $student->first_name }}</td>
                                <td>{{ $student->last_name }}</td>
                                <td>{{ $student->phone_number }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->instrument }}</td>
                                <th scope="row">
                                    <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('student.profile', $student->id) }}" class="btn btn-sm btn-success" role="button" title="profile"><i class="fa fa-user"></i></a>
                                    <a href="{{ route('student.schedule', $student->id) }}" class="btn btn-sm btn-warning" role="role" title="schedule"><i class="fa fa-calendar"></i></a>
                                </th>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
