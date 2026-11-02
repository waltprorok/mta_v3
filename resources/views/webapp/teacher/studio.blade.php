@extends('layouts.webapp')

@section('title', 'Studio Information')

@section('content')

    @php
        $editing = $teacher !== null;
        $user = Auth::user();
    @endphp

    <div class="col-12">

        <h4>Studio Settings</h4>

        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Studio</li>
        </ul>

        @include('partials.teacherTabs')

        <div class="card">

            <div class="card-header bg-light">
                Studio Information
            </div>

            <div class="card-body col-md-10 col-sm-12">

                <form class="form-horizontal" method="POST"
                      action="{{ $editing ? route('teacher.studio.update') : route('teacher.studio.store') }}"
                      enctype="multipart/form-data"
                >

                    @csrf

                    @if($editing)
                        @method('PUT')
                    @endif

                    {{-- Studio Name --}}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">

                                <label for="studio_name" class="control-label">
                                    Studio Name <span class="text-danger">*</span>
                                </label>

                                <input id="studio_name" type="text" name="studio_name"
                                       class="form-control @error('studio_name') is-invalid @enderror"
                                       value="{{ old('studio_name', $teacher->studio_name ?? '') }}"
                                       required
                                >

                                @error('studio_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>
                    </div>

                    {{-- First Name / Last Name --}}
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">

                                <label for="first_name" class="control-label">
                                    First Name <span class="text-danger">*</span>
                                </label>

                                <input id="first_name" type="text" name="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $teacher->first_name ?? $user->first_name) }}"
                                       required
                                >

                                @error('first_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">

                                <label for="last_name" class="control-label">
                                    Last Name <span class="text-danger">*</span>
                                </label>

                                <input id="last_name" type="text" name="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $teacher->last_name ?? $user->last_name) }}"
                                       required
                                >

                                @error('last_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>

                    </div>

                    {{-- Address --}}
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">

                                <label for="address" class="control-label">
                                    Address <span class="text-danger">*</span>
                                </label>

                                <input
                                        id="address"
                                        type="text"
                                        name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address', $teacher->address ?? '') }}"
                                        required
                                >

                                @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">

                                <label for="address_2" class="control-label">
                                    Address 2
                                </label>

                                <input id="address_2" type="text" name="address_2"
                                       class="form-control @error('address_2') is-invalid @enderror"
                                       placeholder="Apt 34, Suite 123, Building H"
                                       value="{{ old('address_2', $teacher->address_2 ?? '') }}"
                                >

                                @error('address_2')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>

                    </div>

                    {{-- City / State / Zip --}}
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">

                                <label for="city" class="control-label">
                                    City <span class="text-danger">*</span>
                                </label>

                                <input id="city" type="text" name="city"
                                       class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city', $teacher->city ?? '') }}"
                                       required
                                >

                                @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">

                                <label for="state" class="control-label">
                                    State <span class="text-danger">*</span>
                                </label>

                                <select id="state" name="state"
                                        class="form-control @error('state') is-invalid @enderror"
                                        required
                                >
                                    <option value="">Select State</option>

                                    @foreach(config('states') as $abbreviation => $name)
                                        <option value="{{ $abbreviation }}"
                                                @selected(old('state', $teacher->state ?? '') === $abbreviation)
                                        >
                                            {{ $name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('state')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">

                                <label for="zip" class="control-label">
                                    Zip <span class="text-danger">*</span>
                                </label>

                                <input id="zip" type="text" name="zip"
                                       class="form-control @error('zip') is-invalid @enderror"
                                       value="{{ old('zip', $teacher->zip ?? '') }}"
                                       required
                                >

                                @error('zip')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>

                    </div>

                    <hr>

                    {{-- Email / Phone --}}
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">

                                <label for="email" class="control-label">
                                    Email <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fa fa-envelope"></i>
                                    </span>

                                    <input id="email" type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $teacher->email ?? $user->email) }}"
                                           required
                                    >

                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">

                                <label for="phone" class="control-label">
                                    Phone <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fa fa-phone"></i>
                                    </span>

                                    <input id="phone" type="tel" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="(___) ___-____"
                                           value="{{ old('phone', $teacher->phone ?? '') }}"
                                           required
                                    >

                                    @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Studio Logo --}}
                    <div class="row">
                        @if($editing && $teacher->logo)
                            <div class="col-sm-4">
                                <div class="form-group">

                                    <label class="control-label">
                                        Current Logo
                                        <small>*For Invoices</small>
                                    </label>

                                    <div>
                                        <img src="{{ asset('storage/teacher/' . $teacher->logo) }}"
                                             alt="Studio Logo"
                                             class="img-fluid"
                                             style="max-height: 150px;"
                                        >
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="{{ $editing && $teacher->logo ? 'col-sm-8' : 'col-sm-6' }}">
                            <div class="form-group">

                                <label for="logo" class="control-label">
                                    {{ $editing && $teacher->logo ? 'Replace Logo' : 'Studio Logo' }}
                                    <small>*For Invoices</small>
                                </label>

                                <input id="logo" type="file" name="logo"
                                       accept="image/*"
                                       class="form-control @error('logo') is-invalid @enderror"
                                >

                                @error('logo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Form Actions --}}
                    <div class="mt-3">

                        <button type="submit" class="btn btn-primary">
                            {{ $editing ? 'Update Studio' : 'Save Studio' }}
                        </button>

                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
