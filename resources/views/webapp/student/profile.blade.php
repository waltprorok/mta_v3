@extends('layouts.webapp')
@section('title', 'Student Profile')
@section('content')

    <div class="col-12">

        <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                data-target="#addStudentModal"><i class="fa fa-plus"></i>&nbsp;Add Student
        </button>

        <h2>Student Profile</h2>

        @foreach ($students as $student)

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-3">
                            <img class="form-control text-center" src="/storage/teacher/20201104_031122.jpg"
                                 alt="20201104_031122.jpg">
                        </div>

                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Student Profile</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $student->first_name }}&nbsp;{{ $student->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $student->phone_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $student->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $student->date_of_birth }}</td>
                                    </tr>
                                    @if($student->address_2 == null)
                                        <tr>
                                            <td>{{ $student->address }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{ $student->address }}&nbsp;Apt/Suite&nbsp;{{ $student->address_2 }}</td>
                                        </tr>

                                    @endif
                                    <tr>
                                        <td>{{ $student->city }}&#44;&nbsp;{{ $student->state }}&nbsp;{{ $student->zip }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $student->instrument }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $student->status }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    @endforeach

@endsection