@extends('layouts.webapp')
@section('title', 'Household')
@section('content')

    <div class="col-12">

        <h4>Household</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Household</li>
        </ul>

{{--        @include('partials.studentListTabs')--}}

        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                @forelse($parent->parentOfStudent as $student)
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
                                    {{--<button class="btn btn-primary">Follow</button>--}}
                                    {{--<button class="btn btn-outline-primary">Message</button>--}}
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
            </div>
        </div>
    </div>

@endsection