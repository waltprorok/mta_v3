@extends('layouts.webapp')
@section('title', 'Business Hours')
@section('content')


    <div class="col-12">
        <h2>Studio Settings</h2>
        @include('partials.teacherTabs')
        <div class="card">
            <div class="card-header bg-light">Business Hours</div>
            <div class="card-body">
                <form method="POST" action="{{ route('teacher.hoursUpdate') }}">
                    @method('PUT')
                    @csrf
                    <table class="table">
                        <thead class="thead">
                        <tr>
                            <th>Day of the Week</th>
                            <th>Active</th>
                            <th>Open</th>
                            <th>Close</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hours as $hour)
                            <tr>
                                @if($hour->day == 0)
                                    <th scope="row">Monday</th>
                                @elseif($hour->day == 1)
                                    <th scope="row">Tuesday</th>
                                @elseif($hour->day == 2)
                                    <th scope="row">Wednesday</th>
                                @elseif($hour->day == 3)
                                    <th scope="row">Thursday</th>
                                @elseif($hour->day == 4)
                                    <th scope="row">Friday</th>
                                @elseif($hour->day == 5)
                                    <th scope="row">Saturday</th>
                                @elseif($hour->day == 6)
                                    <th scope="row">Sunday</th>
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
                                            <option value="08:00">8:00 am</option>
                                            <option value="08:30">8:30 am</option>
                                            <option value="09:00">9:00 am</option>
                                            <option value="09:30">9:30 am</option>
                                            <option value="10:00">10:00 am</option>
                                            <option value="10:30">10:30 am</option>
                                            <option value="11:00">11:00 am</option>
                                            <option value="11:30">11:30 am</option>
                                            <option value="12:00">12:00 pm</option>
                                            <option value="12:30">12:30 pm</option>
                                            <option value="13:00">1:00 pm</option>
                                            <option value="13:30">1:30 pm</option>
                                            <option value="14:00">2:00 pm</option>
                                            <option value="14:30">2:30 pm</option>
                                            <option value="15:00">3:00 pm</option>
                                            <option value="15:30">3:30 pm</option>
                                            <option value="16:00">4:00 pm</option>
                                            <option value="16:30">4:30 pm</option>
                                            <option value="17:00">5:00 pm</option>
                                            <option value="17:30">5:30 pm</option>
                                            <option value="18:00">6:00 pm</option>
                                            <option value="18:30">6:30 pm</option>
                                            <option value="19:00">7:00 pm</option>
                                            <option value="19:30">7:30 pm</option>
                                            <option value="20:00">8:00 pm</option>
                                            <option value="20:30">8:30 pm</option>
                                            <option value="21:00">9:00 pm</option>
                                            <option value="21:30">9:30 pm</option>
                                            <option value="22:00">10:00 pm</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select id="single-select" name="rows[{{ $hour->day }}][close_time]" class="form-control">
                                            <option value="{{ $hour->close_time }}">{{ $hour->hour_close_time }}</option>
                                            <option value="08:00">8:00 am</option>
                                            <option value="08:30">8:30 am</option>
                                            <option value="09:00">9:00 am</option>
                                            <option value="09:30">9:30 am</option>
                                            <option value="10:00">10:00 am</option>
                                            <option value="10:30">10:30 am</option>
                                            <option value="11:00">11:00 am</option>
                                            <option value="11:30">11:30 am</option>
                                            <option value="12:00">12:00 pm</option>
                                            <option value="12:30">12:30 pm</option>
                                            <option value="13:00">1:00 pm</option>
                                            <option value="13:30">1:30 pm</option>
                                            <option value="14:00">2:00 pm</option>
                                            <option value="14:30">2:30 pm</option>
                                            <option value="15:00">3:00 pm</option>
                                            <option value="15:30">3:30 pm</option>
                                            <option value="16:00">4:00 pm</option>
                                            <option value="16:30">4:30 pm</option>
                                            <option value="17:00">5:00 pm</option>
                                            <option value="17:30">5:30 pm</option>
                                            <option value="18:00">6:00 pm</option>
                                            <option value="18:30">6:30 pm</option>
                                            <option value="19:00">7:00 pm</option>
                                            <option value="19:30">7:30 pm</option>
                                            <option value="20:00">8:00 pm</option>
                                            <option value="20:30">8:30 pm</option>
                                            <option value="21:00">9:00 pm</option>
                                            <option value="21:30">9:30 pm</option>
                                            <option value="22:00">10:00 pm</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        {{--                        <tr>--}}
                        {{--                            <th scope="row">Tuesday</th>--}}
                        {{--                            <input name="rows[1][day]" type="hidden" value="1">--}}
                        {{--                            <td>--}}
                        {{--                                <div class="toggle-switch" data-ts-color="primary">--}}
                        {{--                                    <input id="ts2" name="rows[1][active]" type="checkbox" value="1" hidden="hidden">--}}
                        {{--                                    <label for="ts2" class="ts-helper"></label>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[1][open_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[1][close_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th scope="row">Wednesday</th>--}}
                        {{--                            <input name="rows[2][day]" type="hidden" value="2">--}}
                        {{--                            <td>--}}
                        {{--                                <div class="toggle-switch" data-ts-color="primary">--}}
                        {{--                                    <input id="ts3" name="rows[2][active]" type="checkbox" value="1" hidden="hidden">--}}
                        {{--                                    <label for="ts3" class="ts-helper"></label>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[2][open_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[2][close_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th scope="row">Thursday</th>--}}
                        {{--                            <input name="rows[3][day]" type="hidden" value="3">--}}
                        {{--                            <td>--}}
                        {{--                                <div class="toggle-switch" data-ts-color="primary">--}}
                        {{--                                    <input id="ts4" name="rows[3][active]" type="checkbox" value="1" hidden="hidden">--}}
                        {{--                                    <label for="ts4" class="ts-helper"></label>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[3][open_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[3][close_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th scope="row">Friday</th>--}}
                        {{--                            <input name="rows[4][day]" type="hidden" value="4">--}}
                        {{--                            <td>--}}
                        {{--                                <div class="toggle-switch" data-ts-color="primary">--}}
                        {{--                                    <input id="ts5" name="rows[4][active]" type="checkbox" value="1" hidden="hidden">--}}
                        {{--                                    <label for="ts5" class="ts-helper"></label>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[4][open_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[4][close_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th scope="row">Saturday</th>--}}
                        {{--                            <input name="rows[5][day]" type="hidden" value="5">--}}
                        {{--                            <td>--}}
                        {{--                                <div class="toggle-switch" data-ts-color="primary">--}}
                        {{--                                    <input id="ts6" name="rows[5][active]" type="checkbox" value="1" hidden="hidden">--}}
                        {{--                                    <label for="ts6" class="ts-helper"></label>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[5][open_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[5][close_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th scope="row">Sunday</th>--}}
                        {{--                            <input name="rows[6][day]" type="hidden" value="6">--}}
                        {{--                            <td>--}}
                        {{--                                <div class="toggle-switch" data-ts-color="primary">--}}
                        {{--                                    <input id="ts7" name="rows[6][active]" type="checkbox" value="1" hidden="hidden">--}}
                        {{--                                    <label for="ts7" class="ts-helper"></label>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[6][open_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                            <td>--}}
                        {{--                                <div class="form-group">--}}
                        {{--                                    <select id="single-select" name="rows[6][close_time]" class="form-control">--}}
                        {{--                                        <option value="08:00">8:00 am</option>--}}
                        {{--                                        <option value="08:30">8:30 am</option>--}}
                        {{--                                        <option value="09:00">9:00 am</option>--}}
                        {{--                                        <option value="09:30">9:30 am</option>--}}
                        {{--                                        <option value="10:00">10:00 am</option>--}}
                        {{--                                        <option value="10:30">10:30 am</option>--}}
                        {{--                                        <option value="11:00">11:00 am</option>--}}
                        {{--                                        <option value="11:30">11:30 am</option>--}}
                        {{--                                        <option value="12:00">12:00 pm</option>--}}
                        {{--                                        <option value="12:30">12:30 pm</option>--}}
                        {{--                                        <option value="13:00">1:00 pm</option>--}}
                        {{--                                        <option value="13:30">1:30 pm</option>--}}
                        {{--                                        <option value="14:00">2:00 pm</option>--}}
                        {{--                                        <option value="14:30">2:30 pm</option>--}}
                        {{--                                        <option value="15:00">3:00 pm</option>--}}
                        {{--                                        <option value="15:30">3:30 pm</option>--}}
                        {{--                                        <option value="16:00">4:00 pm</option>--}}
                        {{--                                        <option value="16:30">4:30 pm</option>--}}
                        {{--                                        <option value="17:00">5:00 pm</option>--}}
                        {{--                                        <option value="17:30">5:30 pm</option>--}}
                        {{--                                        <option value="18:00">6:00 pm</option>--}}
                        {{--                                        <option value="18:30">6:30 pm</option>--}}
                        {{--                                        <option value="19:00">7:00 pm</option>--}}
                        {{--                                        <option value="19:30">7:30 pm</option>--}}
                        {{--                                        <option value="20:00">8:00 pm</option>--}}
                        {{--                                        <option value="20:30">8:30 pm</option>--}}
                        {{--                                        <option value="21:00">9:00 pm</option>--}}
                        {{--                                        <option value="21:30">9:30 pm</option>--}}
                        {{--                                        <option value="22:00">10:00 pm</option>--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </td>--}}
                        {{--                        </tr>--}}
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>

                </form>
            </div>
        </div>
    </div>

@endsection