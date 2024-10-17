@extends('layouts.webapp')
@section('title', 'Edit Student')
@section('content')

    <div class="col-12">
        <h4>Edit Student</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ul>
        @include('partials.studentTabs', $data = ['id' => $student->id])
        <div class="card">
            <div class="card-body">
                @if($student == null)
                    <div class="text-center">
                        <p>That student record does not exist.</p>
                    </div>
                @else
                    <form class="form-horizontal" method="POST" action="{{ route('student.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="first_name" class="control-label">First Name <span class="text-danger">*</span></label>
                                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ $student->first_name }}">
                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                    <label for="last_name" class="control-label">Last Name <span class="text-danger">*</span></label>
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $student->last_name }}">
                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label for="email" class="control-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ $student->email }}">
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                    <label for="phone" class="control-label">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                        <input type="tel" class="form-control" name="phone" placeholder="(___) ___-____" value="{{ $student->phone_number }}">
                                    </div>
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                    <label for="status" class="control-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        @if ($student->status)
                                            <option value="{{ $student->status }}">
                                                @if ($student->status == \App\Models\Student::ACTIVE)
                                                    Active
                                                @elseif($student->status == \App\Models\Student::WAITLIST)
                                                    Waitlist
                                                @elseif($student->status == \App\Models\Student::LEAD)
                                                    Lead
                                                @elseif($student->status == \App\Models\Student::INACTIVE)
                                                    Inactive
                                                @endif
                                            </option>
                                        @else
                                            <option value="" selected="selected">Select a Status</option>
                                        @endif
                                        <option value="1">Active</option>
                                        <option value="2">Waitlist</option>
                                        <option value="3">Lead</option>
                                        <option value="4">Inactive</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('instrument') ? 'has-error' : '' }}">
                                    <label for="instrument" class="control-label">Instrument</label>
                                    <select class="form-control" id="instrument" name="instrument">
                                        @if ($student->instrument)
                                            <option value="{{ $student->instrument }}">{{ $student->instrument }}</option>
                                        @else
                                            <option value="" selected="selected">Select an instrument</option>
                                        @endif
                                        <option value="Guitar">Guitar</option>
                                        <option value="Bass">Bass</option>
                                        <option value="Drums">Drums</option>
                                        <option value="Voice">Voice</option>
                                        <option value="Piano">Piano</option>
                                    </select>
                                </div>
                                @if ($errors->has('instrument'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('instrument') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('level') ? 'has-error' : '' }}">
                                    <label for="level" class="control-label">Skill Level</label>
                                    <select class="form-control" id="level" name="level">
                                        @if ($student->level)
                                            <option value="{{ $student->level }}">{{ $student->level }}</option>
                                        @else
                                            <option value="" selected="selected">Select a level</option>
                                        @endif
                                        <option value="Beginner">Beginner</option>
                                        <option value="Intermediate">Intermediate</option>
                                        <option value="Advance">Advance</option>
                                    </select>
                                </div>
                                @if ($errors->has('level'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('level') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('auto_schedule') ? 'has-error' : '' }}">
                                    <label for="auto_schedule" class="control-label">Auto Schedule Lessons
                                        <span><i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="While an active student"></i></span>
                                    </label>
                                    <div class="col-md-6">
                                        <div class="toggle-switch mt-2" data-ts-color="primary">
                                            <input id="auto_schedule" type="checkbox" hidden="hidden" name="auto_schedule"
                                                   value="1" {{ $student->auto_schedule ? 'checked' : '' }}>
                                            <label for="auto_schedule" class="ts-helper"></label>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('auto_schedule'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('auto_schedule') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                                    <label for="date_of_birth" class="control-label">Birthday</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="date_of_birth" id="dateOfBirth" autocomplete="off" value="{{ $student->date_of_birth }}">
                                        <span class="input-group-btn">
                                                <button type="button" title="clear date of birth" class="btn btn-secondary" id="btnEdit"><i class="fa fa-trash-o"></i></button>
                                            </span>
                                    </div>
                                    @if ($errors->has('date_of_birth'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('date_of_birth') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('parent_first_name') ? 'has-error' : '' }}">
                                    <label for="parent_first_name" class="control-label">Parent First Name <span class="text-danger">*</span></label>
                                    <input id="parent_first_name" type="text" class="form-control" name="parent_first_name" value="{{ $student->parent->first_name ?? old('parent_first_name') }}">
                                    @if ($errors->has('parent_first_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('parent_first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('parent_last_name') ? 'has-error' : '' }}">
                                    <label for="parent_last_name" class="control-label">Parent Last Name <span class="text-danger">*</span></label>
                                    <input id="parent_last_name" type="text" class="form-control" name="parent_last_name" value="{{ $student->parent->last_name ?? old('parent_last_name') }}">
                                    @if ($errors->has('parent_last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('parent_last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('parent_email') ? 'has-error' : '' }}">
                                    <label for="email" class="control-label">Parent or Guardian Email (enter if child is a minor)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input id="parent_email" type="email" class="form-control" name="parent_email" value="{{ $student->parent->email ?? old('parent_email') }}">
                                    </div>
                                    @if ($errors->has('parent_email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('parent_email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('parent_phone') ? 'has-error' : '' }}">
                                    <label for="parent_phone" class="control-label">Parent or Guardian Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                        <input type="tel" class="form-control" name="parent_phone" placeholder="(___) ___-____" value="{{ $student->parent_phone_number }}">
                                    </div>
                                    @if ($errors->has('parent_phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('parent_phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('at_studio') ? 'has-error' : '' }}">
                                    <label for="at_studio" class="control-label">Lessons At Studio</label>
                                    <div class="col-md-6">
                                        <div class="toggle-switch mt-2" data-ts-color="primary">
                                            <input id="at_studio" type="checkbox" hidden="hidden" name="at_studio"
                                                   value="1" {{ $student->at_studio ? 'checked' : '' }}>
                                            <label for="at_studio" class="ts-helper"></label>
                                        </div>
                                    </div>
                                    @if ($errors->has('at_studio'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('at_studio') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('at_home') ? 'has-error' : '' }}">
                                    <label for="at_home" class="control-label">Lessons At Home</label>
                                    <div class="col-md-6">
                                        <div class="toggle-switch mt-2" data-ts-color="primary">
                                            <input id="at_home" type="checkbox" hidden="hidden" name="at_home"
                                                   value="1" {{ $student->at_home ? 'checked' : '' }}>
                                            <label for="at_home" class="ts-helper"></label>
                                        </div>
                                    </div>
                                    @if ($errors->has('at_home'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('at_home') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                    <label for="address" class="control-label">Address</label>
                                    <input id="address" type="text" class="form-control" name="address" value="{{ $student->address }}">
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('address_2') ? 'has-error' : '' }}">
                                    <label for="address_2" class="control-label">Address 2</label>
                                    <input id="address_2" type="text" class="form-control" placeholder="Apt 34, Suite 123, Building H" name="address_2" value="{{ $student->address_2 }}">
                                    @if ($errors->has('address_2'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address_2') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
                                    <label for="city" class="control-label">City</label>
                                    <input id="city" type="text" class="form-control" name="city" value="{{ $student->city }}">
                                    @if ($errors->has('city'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group {{ $errors->has('state') ? 'has-error' : '' }}">
                                    <label for="state" class="control-label">State</label>
                                    <select class="form-control" id="state" name="state">
                                        @if ($student->state)
                                            <option value="{{ $student->state }}">{{ $student->state }}</option>
                                        @else
                                            <option value="" selected="selected">Select a State</option>
                                        @endif
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                    </select>
                                    @if ($errors->has('state'))
                                        <span class="help-block"><strong>{{ $errors->first('state') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group {{ $errors->has('zip') ? 'has-error' : '' }}">
                                    <label for="zip" class="control-label">Zip Code</label>
                                    <input id="zip" type="text" class="form-control" name="zip" value="{{ $student->zip }}">
                                    @if ($errors->has('zip'))
                                        <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="photo" class="control-label">Add Picture</label>
                                    <input id="photo" type="file" class="form-control" name="photo">
                                </div>
                            </div>
                            @if ($student->photo != null)
                                <div class="col-sm-3 col-md-offset-3">
                                    <div class="form-group">
                                        <label for="photo" class="control-label">Profile Picture</label>
                                        <img class="form-control text-center" src="/storage/student/{{ $student->photo }}" alt="{{ $student->photo }}">
                                    </div>
                                </div>
                            @endif
                        </div>
                        <hr/>
                        <div class="pull-left">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('student.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                        <input id="student_id" type="hidden" class="form-control" name="student_id" value="{{ $student->id }}">
                        @endif
                    </form>
            </div>
        </div>
    </div>

@endsection
