@extends('layouts.webapp')
@section('title', 'Business Hours')
@section('content')
    @inject('carbon', 'Carbon\Carbon')

    <div class="col-12">
        <h4>Studio Settings</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Hours</li>
        </ul>

        @include('partials.teacherTabs')
        <div class="card">
            <div class="card-header bg-light">Business Hours</div>
            <div class="card-body">
                <form method="POST" action="{{ route('teacher.hoursUpdate') }}">
                    @method('PUT')
                    @csrf
                    <table class="table table-responsive-md">
                        <thead class="thead">
                        <tr>
                            <th>Day of the Week</th>
                            <th>Active</th>
                            <th>Open</th>
                            <th>Close</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hours as $hour)
                            <tr>
                                @if($hour->day == 0)
                                    <th scope="row">Sunday</th>
                                @elseif($hour->day == 1)
                                    <th scope="row">Monday</th>
                                @elseif($hour->day == 2)
                                    <th scope="row">Tuesday</th>
                                @elseif($hour->day == 3)
                                    <th scope="row">Wednesday</th>
                                @elseif($hour->day == 4)
                                    <th scope="row">Thursday</th>
                                @elseif($hour->day == 5)
                                    <th scope="row">Friday</th>
                                @elseif($hour->day == 6)
                                    <th scope="row">Saturday</th>
                                @endif

                                <input name="rows[{{ $hour->day }}][day]" type="hidden" value="{{ $hour->day }}">
                                <td>
                                    <div class="toggle-switch" data-ts-color="primary">
                                        <input id="ts{{ $hour->day }}" name="rows[{{ $hour->day }}][active]"
                                               {{ $hour->active == '1' ? 'checked' : '' }} type="checkbox" value="1" hidden="hidden">
                                        <label for="ts{{ $hour->day }}" class="ts-helper"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select id="single-select" name="rows[{{ $hour->day }}][open_time]" class="form-control">
                                            <option value="{{ $hour->open_time }}">{{ $hour->hour_open_time }}</option>
                                            @foreach($selectHours as $sHour)
                                                <option value="{{ $sHour }}">{{ \Carbon\Carbon::parse($sHour)->format('g:i a') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select id="single-select" name="rows[{{ $hour->day }}][close_time]" class="form-control">
                                            <option value="{{ $hour->close_time }}">{{ $hour->hour_close_time }}</option>
                                            @foreach($selectHours as $sHour)
                                                <option value="{{ $sHour }}">{{ \Carbon\Carbon::parse($sHour)->format('g:i a') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    @if ($hour->active)
                                        <strong>{{ $carbon::createFromTimestamp(strtotime($hour->open_time))->diff($hour->close_time)->format('%h:%I') }}</strong>
                                    @else
                                        0:00
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th><strong>{{ $totalHours }}</strong></th>
                        </tr>
                        </tbody>
                    </table>
                    <hr/>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

@endsection
