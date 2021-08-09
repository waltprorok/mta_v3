<!-- main-container -->
<div class="main-container">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <p></p>
            <ul class="nav">
                @if(Auth::user()->admin == true)
                    <li class="nav-item">
                        <a href="{{ route('admin.blog.list') }}"
                           class="nav-link {{ Route::currentRouteName() == 'admin.blog.list' ? 'active' : '' }}">
                            <i class="fa fa-newspaper-o"></i> Blog
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('webapp.contact.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'webapp.contact.index' ? 'active' : '' }}">
                            <i class="fa fa-compress"></i> Contact
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('webapp.admin.students') }}"
                           class="nav-link {{ Route::currentRouteName() == 'webapp.admin.students' ? 'active' : '' }}">
                            <i class="fa fa-users"></i> Students
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('webapp.admin.teachers') }}"
                           class="nav-link {{ Route::currentRouteName() == 'webapp.admin.teachers' ? 'active' : '' }}">
                            <i class="fa fa-graduation-cap"></i> Teachers
                        </a>
                    </li>
                @endif

                @if(Auth::user()->teacher == true)
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                           class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                            <i class="icon icon-speedometer"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a href="#"
                           class="nav-link nav-dropdown-toggle {{ Route::currentRouteName() == 'student.index' ? 'active' : '' }}">
                            <i class="fa fa-users"></i> Students <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="{{ route('student.index') }}" class="nav-link">
                                    <i class="fa fa-list" aria-hidden="true"></i> Student List
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('student.lessons') }}" class="nav-link">
                                    <i class="fa fa-music" aria-hidden="true"></i> Lessons
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Practice Log
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-book" aria-hidden="true"></i> Lending Library
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-pencil" aria-hidden="true"></i> Repertoire Tracker
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-bullhorn" aria-hidden="true"></i> Push Announcements
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('calendar.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'calendar.index' ? 'active' : '' }}">
                            <i class="fa fa-calendar"></i> Calendar
                        </a>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a href="#"
                           class="nav-link nav-dropdown-toggle {{ Route::currentRouteName() == '#' ? 'active' : '' }}">
                            <i class="fa fa-credit-card"></i> Billing <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-pencil"></i> Transaction Log
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-money"></i> Payments Received
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-list-alt"></i> List Payments
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-keyboard-o"></i> Enter a Payments
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-check-square-o"></i> Fees &amp; Credits
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-list-ul"></i> List Fees &amp; Credits
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-plus-square"></i> Change Fee / Issue Credit
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-exchange"></i> Expenses &amp; Other Income
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-envelope"></i> Invoicing
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-car"></i> Mileage Tracker
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a href="#"
                           class="nav-link nav-dropdown-toggle {{ Route::currentRouteName() == 'reports.all' ? 'active' : '' }}">
                            <i class="fa fa-pie-chart"></i> Reports <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="{{ route('reports.all') }}" class="nav-link">
                                    <i class="fa fa-area-chart"></i> All Reports
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-line-chart"></i> Financial Reports
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-bar-chart"></i> Student Reports
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-calendar-check-o"></i> Calendar Reports
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @include('partials.alerts')
                @yield('content')
            </div>
        </div>
    </div>
</div>
<!-- end main-container -->
