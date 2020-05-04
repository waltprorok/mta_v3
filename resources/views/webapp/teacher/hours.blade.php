@extends('layouts.webapp')
@section('title', 'Business Hours')
@section('content')


    <div class="col-12">
        <h2>Studio Settings</h2>
        @include('partials.teacherTabs')
        <div class="card">
            <div class="card-header bg-light">Business Hours</div>
            <div class="card-body">
                <form method="POST" action="{{ route('teacher.hoursSave') }}">
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

                        <tr>
                            <th scope="row">Monday</th>
                            <input name="monday" type="hidden" value="0">
                            <td>
                                <div class="toggle-switch" data-ts-color="primary">
                                    <input id="ts1" name="monday_active" type="checkbox" value="1" hidden="hidden">
                                    <label for="ts1" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="monday_open_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="monday_close_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Tuesday</th>
                            <input name="tuesday" type="hidden" value="1">
                            <td>
                                <div class="toggle-switch" data-ts-color="primary">
                                    <input id="ts2" name="tuesday_active" type="checkbox" value="1" hidden="hidden">
                                    <label for="ts2" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="tuesday_open_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="tuesday_close_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Wednesday</th>
                            <input name="wednesday" type="hidden" value="3">
                            <td>
                                <div class="toggle-switch" data-ts-color="primary">
                                    <input id="ts3" name="wednesday_active" type="checkbox" value="1" hidden="hidden">
                                    <label for="ts3" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="wednesday_open_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="wednesday_close_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Thursday</th>
                            <input name="thursday" type="hidden" value="4">
                            <td>
                                <div class="toggle-switch" data-ts-color="primary">
                                    <input id="ts4" name="thursday_active" type="checkbox" value="1" hidden="hidden">
                                    <label for="ts4" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="thursday_open_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="thursday_close_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Friday</th>
                            <input name="friday" type="hidden" value="5">
                            <td>
                                <div class="toggle-switch" data-ts-color="primary">
                                    <input id="ts5" name="friday_active" type="checkbox" value="1" hidden="hidden">
                                    <label for="ts5" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="friday_open_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="friday_close_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Saturday</th>
                            <input name="saturday" type="hidden" value="6">
                            <td>
                                <div class="toggle-switch" data-ts-color="primary">
                                    <input id="ts6" name="saturday_active" type="checkbox" value="1" hidden="hidden">
                                    <label for="ts6" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="saturday_open_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="saturday_close_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Sunday</th>
                            <input name="sunday" type="hidden" value="7">
                            <td>
                                <div class="toggle-switch" data-ts-color="primary">
                                    <input id="ts7" name="sunday_active" type="checkbox" value="1" hidden="hidden">
                                    <label for="ts7" class="ts-helper"></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="sunday_open_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select id="single-select" name="sunday_close_time" class="form-control">
                                        <option>8:00 am</option>
                                        <option>8:30 am</option>
                                        <option>9:00 am</option>
                                        <option>9:30 am</option>
                                        <option>10:00 am</option>
                                        <option>10:30 am</option>
                                        <option>11:00 am</option>
                                        <option>11:30 am</option>
                                        <option>12:00 pm</option>
                                        <option>12:30 pm</option>
                                        <option>1:00 pm</option>
                                        <option>1:30 pm</option>
                                        <option>2:00 pm</option>
                                        <option>2:30 pm</option>
                                        <option>3:00 pm</option>
                                        <option>3:30 pm</option>
                                        <option>4:00 pm</option>
                                        <option>4:30 pm</option>
                                        <option>5:00 pm</option>
                                        <option>5:30 pm</option>
                                        <option>6:00 pm</option>
                                        <option>6:30 pm</option>
                                        <option>7:00 pm</option>
                                        <option>7:30 pm</option>
                                        <option>8:00 pm</option>
                                        <option>8:30 pm</option>
                                        <option>9:00 pm</option>
                                        <option>9:30 pm</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

@endsection