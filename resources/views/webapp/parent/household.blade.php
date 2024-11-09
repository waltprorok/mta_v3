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
            <div class="col-md-4 mb-3">
                @forelse($parent->parentOfStudents as $student)
                    <div class="card">
                        <div class="card-body">
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
                                    @if($student->status)
                                        @if($student->status == Student::ACTIVE) <p class="mb-1">Active</p>@endif
                                        @if($student->status == Student::WAITLIST) <p class="mb-1">Waitlist</p>@endif
                                        @if($student->status == Student::INACTIVE) <p class="mb-1">Inactive</p>@endif
                                    @endif
                                    @if($student->instrument)
                                        <p class="mb-1">{{ $student->instrument }}</p>
                                    @endif
                                    {{-- TODO: fix reply to teacher link in controller --}}
{{--                                    @if($teacher != null)--}}
{{--                                        <a href="{{ route('message.reply', ['id' => $teacher->teacher_id, 'subject' => $student->first_name . ' ' . $student->last_name, 'new' => true]) }}"--}}
{{--                                           class="btn btn-sm btn-outline-primary">Message Teacher</a>--}}
{{--                                    @endif--}}

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
                @foreach($parent->parentOfStudents as $student)
                    <div class="card mb-3">
                        <div class="card-body">
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
{{--                            <hr>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-sm-3">--}}
{{--                                    <h6 class="mb-0">Birthday</h6>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-9">--}}
{{--                                    @if($student->date_of_birth != null)--}}
{{--                                        {{ date('F d, Y', strtotime($student->date_of_birth)) }}--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Parent Email</h6>
                                </div>
                                <div class="col-sm-9">
                                    <a href="mailto:{{ $student->parent->email }}">{{ $student->parent->email }}</a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Parent Phone</h6>
                                </div>
                                <div class="col-sm-9">
                                    <a href="mailto:{{ $student->parent_phone }}">{{ $student->parent_phone }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
