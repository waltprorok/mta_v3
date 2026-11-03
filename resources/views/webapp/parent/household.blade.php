@extends('layouts.webapp')

@section('title', 'Household')

@section('content')

    <div class="col-12">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1">Household</h4>
                <p class="text-muted mb-0">
                    Students and family contact information.
                </p>
            </div>

            <span class="badge badge-light border px-3 py-2">
            <i class="fa fa-users mr-1"></i>
            {{ $parent->parentOfStudents->count() }}
                {{ \Illuminate\Support\Str::plural('Student', $parent->parentOfStudents->count()) }}
        </span>
        </div>

        @if(Auth::user()->isTeacher())
            <ul class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Household</li>
            </ul>
        @endif

        {{-- Three Column Grid --}}
        <div class="row">

            @forelse($parent->parentOfStudents as $student)

                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="card h-100 shadow-sm">

                        {{-- Profile Header --}}
                        <div class="card-body text-center border-bottom py-4">

                            <img
                                    src="{{ $student->photo
                                ? asset('storage/student/' . $student->photo)
                                : asset('webapp/img/avatar.jpeg') }}"
                                    alt="{{ $student->full_name }} profile photo"
                                    class="rounded-circle border mb-3"
                                    style="width: 100px; height: 100px; object-fit: cover;"
                                    loading="lazy"
                            >

                            <h5 class="mb-1">
                                {{ $student->full_name }}
                            </h5>

                            <p class="text-muted mb-2">
                                <i class="fa fa-music mr-1"></i>
                                {{ $student->instrument ?: 'No instrument assigned' }}
                            </p>

                            <span class="badge badge-{{ strtolower($student->status_name ?? '') === 'active' ? 'success' : 'secondary' }} px-3 py-2">
                            {{ $student->status_name ?: 'Unknown' }}
                        </span>

                        </div>

                        {{-- Student Information --}}
                        <div class="card-body px-4 py-3">

                            <h6 class="text-uppercase text-muted small font-weight-bold mb-3">
                                Student Information
                            </h6>

                            {{-- Email --}}
                            <div class="d-flex mb-3">
                                <i class="fa fa-envelope text-muted mt-1 mr-3"
                                   style="width: 16px;"></i>

                                <div class="flex-grow-1" style="min-width: 0;">
                                    <small class="text-muted d-block">Email</small>

                                    @if($student->email)
                                        <a href="mailto:{{ $student->email }}"
                                           class="text-break">
                                            {{ $student->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="d-flex mb-3">
                                <i class="fa fa-phone text-muted mt-1 mr-3"
                                   style="width: 16px;"></i>

                                <div>
                                    <small class="text-muted d-block">Phone</small>

                                    @if($student->phone_number)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $student->phone_number) }}">
                                            {{ $student->phone_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Birthday --}}
                            <div class="d-flex">
                                <i class="fa fa-birthday-cake text-muted mt-1 mr-3"
                                   style="width: 16px;"></i>

                                <div>
                                    <small class="text-muted d-block">Birthday</small>

                                    @if($student->date_of_birth)
                                        {{ \Carbon\Carbon::parse($student->date_of_birth)->format('M j, Y') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- Parent Information --}}
                        <div class="card-body px-4 py-3 border-top">

                            <h6 class="text-uppercase text-muted small font-weight-bold mb-3">
                                Parent Information
                            </h6>

                            {{-- Parent Email --}}
                            <div class="d-flex mb-3">
                                <i class="fa fa-envelope text-muted mt-1 mr-3"
                                   style="width: 16px;"></i>

                                <div class="flex-grow-1" style="min-width: 0;">
                                    <small class="text-muted d-block">Email</small>

                                    @if($student->parent && $student->parent->email)
                                        <a href="mailto:{{ $student->parent->email }}"
                                           class="text-break">
                                            {{ $student->parent->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Parent Phone --}}
                            <div class="d-flex">
                                <i class="fa fa-phone text-muted mt-1 mr-3"
                                   style="width: 16px;"></i>

                                <div>
                                    <small class="text-muted d-block">Phone</small>

                                    @if($student->parent_phone_number)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $student->parent_phone_number) }}">
                                            {{ $student->parent_phone_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fa fa-users fa-3x text-muted mb-3"></i>

                            <h5>No Students Found</h5>

                            <p class="text-muted mb-0">
                                There are currently no students associated with this household.
                            </p>
                        </div>
                    </div>
                </div>

            @endforelse

        </div>

    </div>

@endsection
