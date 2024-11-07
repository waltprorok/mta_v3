<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'teacher.studioIndex' ? 'active' : '' }}" id="studio"
           href="{{ route('teacher.studioIndex') }}">Studio</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'teacher.hours' ? 'active' : '' }}" id="hours"
           href="{{ route('teacher.hours') }}">Hours</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'teacher.instruments' ? 'active' : '' }}" id="instruments"
           href="{{ route('teacher.instruments') }}">Instruments</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'teacher.billing' ? 'active' : '' }}" id="billing"
           href="{{ route('teacher.billing') }}">Billing</a>
    </li>
</ul>
