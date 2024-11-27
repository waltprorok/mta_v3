@php use App\Models\Student; @endphp
@extends('layouts.webapp')
@section('title', 'Household')
@section('content')

    <div class="col-12">
        @if (Auth::user()->isTeacher())
            <h4>Household</h4>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Household</li>
            </ul>
        @endif

        <div class="row gutters-sm">
            @forelse($parent->parentOfStudents as $student)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex flex-column align-items-center text-center">
                                @if($student->photo !== null)
                                    <img src="/storage/student/{{ $student->photo }}" alt="{{ $student->photo }}"
                                         class="rounded-circle" width="120">
                                @else
                                    <img src="{{ asset('webapp/img/avatar.jpeg') }}" alt="stock-avatar"
                                         class="rounded-circle" width="120">
                                @endif
                                <div class="mt-3">
                                    <h5>{{ $student->full_name }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row p-1">
                                <div class="col-sm-12">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <span class="pl-3">
                                        <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>&nbsp;<small class="text-secondary">&nbsp;<em>&#8226;</em>&nbsp;Student</small>
                                    </span>
                                </div>
                            </div>

                            <div class="row p-1">
                                <div class="col-sm-12">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                    <span class="pl-3">
                                        {{ $student->phone_number }}
                                    </span>
                                </div>
                            </div>

                            <div class="row p-1">
                                <div class="col-sm-12">
                                    <i class="fa fa-music" aria-hidden="true"></i>
                                    <span class="pl-3">
                                        {{ $student->instrument }}
                                    </span>
                                </div>
                            </div>

                            <div class="row p-1">
                                <div class="col-sm-12">
                                    <i class="fa fa-birthday-cake" aria-hidden="true"></i>
                                    <span class="pl-3">
                                        @if($student->date_of_birth != null)
                                            {{ date('F d, Y', strtotime($student->date_of_birth)) }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="row p-1">
                                <div class="col-sm-12">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <span class="pl-3">
                                        <a href="mailto:{{ $student->parent->email }}">{{ $student->parent->email }}</a>&nbsp;<small class="text-secondary">&nbsp;<em>&#8226;</em>&nbsp;Parent</small>
                                    </span>
                                </div>
                            </div>

                            <div class="row p-1">
                                <div class="col-sm-12">
                                    <i class="fa fa-phone-square" aria-hidden="true"></i>
                                    <span class="pl-3">
                                       {{ $student->parent_phone_number }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card text-center">
                    <div class="card-body">
                        <p>There are no students in your household.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

@endsection
