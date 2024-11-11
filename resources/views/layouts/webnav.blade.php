<!-- main-container -->
<div class="main-container">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <p></p>
            <ul class="nav">
                @if(Auth::user()->admin)
                    <li class="nav-item">
                        <a href="{{ route('admin.blog.list') }}"
                           class="nav-link {{ Route::currentRouteName() == 'admin.blog.list' || Route::currentRouteName() == 'admin.blog.create' || Route::currentRouteName() == 'admin.blog.edit' ? 'active' : '' }}">
                            <i class="fa fa-newspaper-o"></i>Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'contact.index' ? 'active' : '' }}">
                            <i class="fa fa-paper-plane"></i>Contacts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('complete.lessons') }}"
                           class="nav-link {{ Route::currentRouteName() == 'complete.lessons' ? 'active' : '' }}">
                            <i class="fa fa-music"></i>Lessons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('message.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'message.index' ? 'active' : '' }}">
                            <i class="fa fa-envelope"></i>Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.student.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'admin.student.index' ? 'active' : '' }}">
                            <i class="fa fa-id-card"></i>Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('teacher.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'teacher.index' ? 'active' : '' }}">
                            <i class="fa fa-address-book"></i>Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}"
                           class="nav-link {{ Route::currentRouteName() == 'admin.users' ? 'active' : '' }}">
                            <i class="fa fa-users"></i>Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.support.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'admin.support.index' ? 'active' : '' }}">
                            <i class="fa fa-life-saver"></i>Support
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.billing.plan.list') }}"
                           class="nav-link {{ Route::currentRouteName() == 'admin.billing.plan.list' ? 'active' : '' }}">
                            <i class="fa fa-credit-card-alt"></i>Plans
                        </a>
                    </li>
                @endif

                @if(Auth::user()->parent)
                    <li class="nav-item">
                        <a href="{{ route('parent.household') }}"
                           class="nav-link {{ Route::currentRouteName() == 'parent.household' ? 'active' : '' }}">
                            <i class="fa fa-group"></i>Household
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('parent.calendar') }}"
                           class="nav-link {{ Route::currentRouteName() == 'parent.calendar' ? 'active' : '' }}">
                            <i class="fa fa-calendar"></i>Calendar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('message.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'message.index' ? 'active' : '' }}">
                            <i class="fa fa-envelope"></i>Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payment.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'payment.index' ? 'active' : '' }}">
                            <i class="fa fa-credit-card"></i>Billing
                        </a>
                    </li>
                @endif

                @if(Auth::user()->student)
                    <li class="nav-item">
                        <a href="{{ route('student.calendar') }}"
                           class="nav-link {{ Route::currentRouteName() == 'student.calendar' ? 'active' : '' }}">
                            <i class="fa fa-calendar"></i>Calendar
                        </a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a href="#"--}}
{{--                           class="nav-link {{ Route::currentRouteName() == '#' ? 'active' : '' }}">--}}
{{--                            <i class="fa fa-music"></i>Sheet Music--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="nav-item">
                        <a href="{{ route('payment.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'payment.index' ? 'active' : '' }}">
                            <i class="fa fa-credit-card"></i>Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('message.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'message.index' ? 'active' : '' }}">
                            <i class="fa fa-envelope"></i>Messages
                        </a>
                    </li>
                @endif

                @if(Auth::user()->teacher)
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                           class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                            <i class="icon icon-speedometer"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a href="#students" class="nav-link nav-dropdown-toggle">
                            <i class="fa fa-users"></i>Students<i class="fa fa-caret-left"></i>
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="{{ route('student.index') }}"
                                   class="nav-link {{ Route::currentRouteName() == 'student.index' || Route::currentRouteName() == 'student.schedule.edit'|| Route::currentRouteName() == 'student.schedule' || Route::currentRouteName() == 'student.edit' || Route::currentRouteName() ==  'student.profile' ? 'active' : '' }}">
                                    <i class="fa fa-list ml-3" aria-hidden="true"></i>List
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('complete.lessons') }}" class="nav-link {{ Route::currentRouteName() == 'complete.lessons' ? 'active' : '' }}">
                                    <i class="fa fa-music ml-3" aria-hidden="true"></i>Lessons
                                </a>
                            </li>

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Practice Log--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-book" aria-hidden="true"></i> Lending Library--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-pencil" aria-hidden="true"></i> Repertoire Tracker--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-bullhorn" aria-hidden="true"></i> Push Announcements--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('calendar.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'calendar.index' ? 'active' : '' }}">
                            <i class="fa fa-calendar"></i>Calendar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('message.index') }}"
                           class="nav-link {{ Route::currentRouteName() == 'message.index' ? 'active' : '' }}">
                            <i class="fa fa-envelope"></i>Messages
                        </a>
                    </li>
                    <li class="nav-item nav-dropdown" id="#billing">
                        <a href="#billing" class="nav-link nav-dropdown-toggle {{ Route::currentRouteName() == '#' ? 'active' : '' }}">
                            <i class="fa fa-credit-card"></i>Billing<i class="fa fa-caret-left"></i>
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="{{ route('invoice.index') }}" class="nav-link {{ Route::currentRouteName() == 'invoice.index' ? 'active' : '' }}">
                                    <i class="fa fa-calculator ml-3" aria-hidden="true"></i>Invoices
                                </a>
                            </li>

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-pencil ml-3"></i>Transaction Log--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-money ml-3"></i>Payments Received--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                            <li class="nav-item">
                                <a href="{{ route('invoice.list_of_payments') }}" class="nav-link {{ Route::currentRouteName() == 'invoice.list_of_payments' ? 'active' : '' }}">
                                    <i class="fa fa-list-alt ml-3"></i>List Payments
                                </a>
                            </li>
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-keyboard-o ml-3"></i>Enter a Payment--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-check-square-o ml-3"></i>Fees &amp; Credits--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-list-ul"></i> List Fees &amp; Credits--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-plus-square"></i> Change Fee / Issue Credit--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-exchange"></i> Expenses &amp; Other Income--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-envelope"></i> Invoicing--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-car"></i> Mileage Tracker--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}

                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown" id="#reports">
                        <a href="#reports" class="nav-link nav-dropdown-toggle">
                            <i class="fa fa-pie-chart"></i>Reports<i class="fa fa-caret-left"></i>
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="{{ route('reports.index') }}" class="nav-link {{ Route::currentRouteName() == 'reports.index' ? 'active' : '' }}">
                                    <i class="fa fa-area-chart ml-3"></i>Student Status
                                </a>
                            </li>
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a href="#" class="nav-link">--}}
                            {{--                                    <i class="fa fa-line-chart"></i> Financial Reports--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
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
