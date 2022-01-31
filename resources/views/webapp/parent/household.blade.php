@extends('layouts.webapp')
@section('title', 'Household')
@section('content')

    <div class="col-12">

        <h4>Household</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Household</li>
        </ul>
        @include('partials.studentListTabs')

        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                @foreach($parent->parentOfStudent as $student)
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            @if($student->photo != null)
                                <img src="/storage/student/{{ $student->photo }}" alt="{{ $student->photo }}"
                                     class="rounded-circle" width="120">
                            @else
                                <img src="{{ asset('webapp/imgs/avatar.jpeg') }}" alt="stock-avatar"
                                     class="rounded-circle" width="120">
                            @endif
                            <div class="mt-3">
                                <h5>{{ $student->first_name }}&nbsp;{{ $student->last_name }}</h5>
                                @if($student->instrument != null)
                                    <p class="mb-1">{{ $student->instrument }}</p>
                                @endif
{{--                                <button class="btn btn-primary">Follow</button>--}}
{{--                                <button class="btn btn-outline-primary">Message</button>--}}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="col-md-8">
                @foreach($parent->parentOfStudent as $student)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Name</h6>
                            </div>
                            <div class="col-sm-9">
                                {{ $student->first_name }}&nbsp;{{ $student->last_name }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9">
                                <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Phone</h6>
                            </div>
                            <div class="col-sm-9">
                                {{ $student->phone_number }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Birthday</h6>
                            </div>
                            <div class="col-sm-9">
                                {{ date('F d, Y | l ', strtotime($student->date_of_birth)) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        <!-- -->

{{--        <div class="card">--}}
{{--            <div class="card-body">--}}
{{--                <table class="table table-condensed table-hover table-responsive-md">--}}
{{--                    <thead class="thead">--}}
{{--                    <tr>--}}
{{--                        <th scope="col">First Name</th>--}}
{{--                        <th scope="col">Last Name</th>--}}
{{--                        <th scope="col">Phone</th>--}}
{{--                        <th scope="col">Email</th>--}}
{{--                        <th scope="col">Instrument</th>--}}
{{--                        <th scope="col" data-orderable="false">Actions</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}

{{--                    @if(count($parent->parentOfStudent) == null)--}}
{{--                        <tr>--}}
{{--                            <td>No student.</td>--}}
{{--                            <td></td>--}}
{{--                            <td></td>--}}
{{--                            <td></td>--}}
{{--                            <td></td>--}}
{{--                            <td></td>--}}
{{--                            <td></td>--}}
{{--                        </tr>--}}
{{--                    @else--}}
{{--                        @foreach($parent->parentOfStudent as $student)--}}
{{--                            <tr>--}}
{{--                                <td>{{ $student->first_name }}</td>--}}
{{--                                <td>{{ $student->last_name }}</td>--}}
{{--                                <td>{{ $student->phone_number }}</td>--}}
{{--                                <td>{{ $student->email }}</td>--}}
{{--                                <td>{{ $student->instrument }}</td>--}}
{{--                                <th scope="row">--}}
{{--                                    <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>--}}
{{--                                    <a href="{{ route('student.profile', $student->id) }}" class="btn btn-sm btn-success" role="button" title="profile"><i class="fa fa-user"></i></a>--}}
{{--                                    <a href="{{ route('student.schedule', $student->id) }}" class="btn btn-sm btn-warning" role="role" title="schedule"><i class="fa fa-calendar"></i></a>--}}
{{--                                </th>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>


@endsection
