@extends('layouts.webapp')
@section('title', 'Student Profile')
@section('content')

    <div class="col-12">
        <h4>Student Profile</h4>
        @foreach ($students as $student)
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ul>
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                @if($student->photo != null)
                                    <img src="/storage/student/{{ $student->photo }}" alt="{{ $student->photo }}"
                                         class="rounded-circle" width="150">
                                @else
                                    <img src="{{ asset('webapp/imgs/avatar.jpeg') }}" alt="stock-avatar"
                                         class="rounded-circle" width="150">
                                @endif
                                <div class="mt-3">
                                    <h5>{{ $student->first_name }}&nbsp;{{ $student->last_name }}</h5>
                                    @if($student->instrument != null)
                                        <p class="mb-1">{{ $student->instrument }}</p>
                                    @endif
                                    <a href="{{ route('message.create', ['id' => $student->student_id]) }}" class="btn btn-outline-primary">Message</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-globe mr-2 icon-inline">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="2" y1="12" x2="22" y2="12"></line>
                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                    </svg>
                                    Website
                                </h6>
                                <span class="text-secondary">https://bootdey.com</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0">
                                    <i class="fa fa-skype fa-2x" aria-hidden="true"></i>
                                    <span class="ml-2">Skype</span>
                                </h6>
                                <span class="text-secondary">User Name</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0">
                                    <i class="fa fa-video-camera fa-2x" aria-hidden="true"></i>
                                    <span class="ml-2">FaceTime ID</span>
                                </h6>
                                <span class="text-secondary">Id Number Here</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
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
                                @if (isset($student->date_of_birth))
                                    <div class="col-sm-9">
                                        {{ date('F d, Y', strtotime($student->date_of_birth)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row gutters-sm">
                        <div class="col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project
                                        Status</h6>
                                    <small>Web Design</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%"
                                             aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Website Markup</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%"
                                             aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>One Page</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%"
                                             aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Mobile Template</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%"
                                             aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Backend API</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%"
                                             aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project
                                        Status</h6>
                                    <small>Web Design</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%"
                                             aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Website Markup</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%"
                                             aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>One Page</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%"
                                             aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Mobile Template</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%"
                                             aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Backend API</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%"
                                             aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    @endforeach

@endsection
