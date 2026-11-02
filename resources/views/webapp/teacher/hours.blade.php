@extends('layouts.webapp')

@section('title', 'Business Hours')

@section('content')

    @php
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        $savedHours = $hours->keyBy('day');
    @endphp

    <div class="col-12">
        <h4>Studio Settings</h4>

        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Hours</li>
        </ul>

        @include('partials.teacherTabs')

        <div class="card">
            <div class="card-header bg-light">
                Business Hours
            </div>

            <div class="card-body">
                <form method="POST" action="{{ $editing ? route('teacher.hours.update') : route('teacher.hours.store') }}">
                    @csrf
                    @if($editing)
                        @method('PUT')
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Day of the Week</th>
                                <th>Active</th>
                                <th>Open</th>
                                <th>Close</th>
                                <th>Total</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($days as $day => $dayName)
                                @php
                                    $hour = $savedHours->get($day);
                                    $active = old(
                                        "rows.$day.active",
                                        $hour->active ?? false
                                    );
                                    $openTime = old(
                                        "rows.$day.open_time",
                                        $hour->open_time ?? ($selectHours[0] ?? '')
                                    );
                                    $closeTime = old(
                                        "rows.$day.close_time",
                                        $hour->close_time ?? ($selectHours[0] ?? '')
                                    );
                                @endphp

                                <tr>
                                    {{-- Day --}}
                                    <th scope="row">
                                        {{ $dayName }}
                                        <input type="hidden" name="rows[{{ $day }}][day]" value="{{ $day }}">
                                    </th>
                                    {{-- Active --}}
                                    <td>
                                        <div class="toggle-switch" data-ts-color="primary">
                                            <input
                                                    id="ts{{ $day }}"
                                                    name="rows[{{ $day }}][active]"
                                                    type="checkbox"
                                                    value="1"
                                                    hidden="hidden"
                                                    @checked($active)
                                            >

                                            <label
                                                    for="ts{{ $day }}"
                                                    class="ts-helper"
                                            ></label>
                                        </div>
                                    </td>
                                    {{-- Open Time --}}
                                    <td>
                                        <div class="form-group">
                                            <select id="open-time-{{ $day }}"
                                                    name="rows[{{ $day }}][open_time]"
                                                    class="form-control @error("rows.$day.open_time") is-invalid @enderror"
                                            >

                                                @foreach($selectHours as $selectHour)
                                                    <option value="{{ $selectHour }}"
                                                            @selected($openTime == $selectHour)
                                                    >
                                                        {{ \Carbon\Carbon::parse($selectHour)->format('g:i a') }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error("rows.$day.open_time")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </td>

                                    {{-- Close Time --}}
                                    <td>
                                        <div class="form-group">
                                            <select id="close-time-{{ $day }}"
                                                    name="rows[{{ $day }}][close_time]"
                                                    class="form-control @error("rows.$day.close_time") is-invalid @enderror"
                                            >

                                                @foreach($selectHours as $selectHour)
                                                    <option value="{{ $selectHour }}"
                                                            @selected($closeTime == $selectHour)
                                                    >
                                                        {{ \Carbon\Carbon::parse($selectHour)->format('g:i a') }}
                                                    </option>

                                                @endforeach
                                            </select>

                                            @error("rows.$day.close_time")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </td>
                                    {{-- Total Hours --}}
                                    <td>
                                        @if($hour && $hour->active)
                                            <strong>{{ \Carbon\Carbon::parse($hour->open_time)
                                                    ->diff($hour->close_time)
                                                    ->format('%h:%I') }}
                                            </strong>
                                        @else
                                            0:00
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                            <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">
                                    Total Hours
                                </th>

                                <th>
                                    {{ $totalHours }}
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        {{ $editing ? 'Update Hours' : 'Save Hours' }}
                    </button>

                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                </form>
            </div>
        </div>
    </div>

@endsection
