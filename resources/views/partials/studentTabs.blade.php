<ul class="nav nav-tabs" id="studentTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'student.edit' ? 'active' : '' }}" id="student"
           href="{{ route('student.edit', $data['id'] ) }}">Student</a>
    </li>
    @if (Route::currentRouteName() == 'student.schedule')
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'student.schedule' ? 'active' : '' }}" id="schedule"
               href="{{ route('student.schedule', $data['id']) }}">Schedule</a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'student.schedule.edit' ? 'active' : '' }}" id="schedule"
               href="{{ route('student.schedule', $data['id']) }}">Schedule</a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'student.profile' ? 'active' : '' }}" id="profile"
           href="{{ route('student.profiles', $data['id']) }}">Profile</a>
    </li>
</ul>
