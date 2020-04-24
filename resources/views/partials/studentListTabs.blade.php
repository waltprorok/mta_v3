<ul class="nav nav-tabs" id="studentListTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'student.index' ? 'active' : '' }}" id="active"
           href="{{ route('student.index') }}">Active</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'student.waitlist' ? 'active' : '' }}" id="waitlist"
           href="{{ route('student.waitlist') }}">Waitlist</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'student.leads' ? 'active' : '' }}" id="leads"
           href="{{ route('student.leads') }}">Leads</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'student.inactive' ? 'active' : '' }}" id="inactive"
           href="{{ route('student.inactive') }}">Inactive</a>
    </li>
</ul>
