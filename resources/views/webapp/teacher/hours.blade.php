@extends('layouts.webapp')
@section('title', 'Studio Business Hours')
@section('content')


    <div class="col-12">
        <h2>Studio Settings</h2>
        @include('partials.teacherTabs')
        <div class="card">
            <div class="card-header bg-light">Business Hours</div>
            <div class="card-body">
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
                        <td>
                            <div class="toggle-switch" data-ts-color="primary">
                                <input id="ts1" type="checkbox" hidden="hidden">
                                <label for="ts1" class="ts-helper"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="single-select" class="form-control">
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
                                <select id="single-select" class="form-control">
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
                        <td>
                            <div class="toggle-switch" data-ts-color="primary">
                                <input id="ts2" type="checkbox" hidden="hidden">
                                <label for="ts2" class="ts-helper"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="single-select" class="form-control">
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
                                <select id="single-select" class="form-control">
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
                        <td>
                            <div class="toggle-switch" data-ts-color="primary">
                                <input id="ts3" type="checkbox" hidden="hidden">
                                <label for="ts3" class="ts-helper"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="single-select" class="form-control">
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
                                <select id="single-select" class="form-control">
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
                        <td>
                            <div class="toggle-switch" data-ts-color="primary">
                                <input id="ts4" type="checkbox" hidden="hidden">
                                <label for="ts4" class="ts-helper"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="single-select" class="form-control">
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
                                <select id="single-select" class="form-control">
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
                        <td>
                            <div class="toggle-switch" data-ts-color="primary">
                                <input id="ts5" type="checkbox" hidden="hidden">
                                <label for="ts5" class="ts-helper"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="single-select" class="form-control">
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
                                <select id="single-select" class="form-control">
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
                        <td>
                            <div class="toggle-switch" data-ts-color="primary">
                                <input id="ts6" type="checkbox" hidden="hidden">
                                <label for="ts6" class="ts-helper"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="single-select" class="form-control">
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
                                <select id="single-select" class="form-control">
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
                        <td>
                            <div class="toggle-switch" data-ts-color="primary">
                                <input id="ts7" type="checkbox" hidden="hidden">
                                <label for="ts7" class="ts-helper"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select id="single-select" class="form-control">
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
                                <select id="single-select" class="form-control">
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
            </div>
        </div>
    </div>

@endsection