<ul class="nav nav-tabs" id="myTab" role="tablist">
    @if (Route::currentRouteName() == 'teacher.editSettings')
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'teacher.editSettings' ? 'active' : '' }}" id="studio"
               href="{{ route('teacher.studioIndex') }}">Studio</a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'teacher.studioIndex' ? 'active' : '' }}" id="studio"
               href="{{ route('teacher.studioIndex') }}">Studio</a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'pw.teacher' ? 'active' : '' }}" id="password"
           href="{{ route('pw.teacher') }}">Password</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'teacher.payment' ? 'active' : '' }}"
           href="{{ route('teacher.payment') }}">Payment</a>
    </li>
    @if(Route::currentRouteName() == 'teacher.hours')
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'teacher.hours' ? 'active' : '' }}"
               href="{{ route('teacher.hours') }}">Hours</a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'teacher.hoursView' ? 'active' : '' }}"
               href="{{ route('teacher.hours') }}">Hours</a>
        </li>
    @endif
</ul>
