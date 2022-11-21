<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(View::hasSection('title'))
            @yield('title') | MTA
        @else
            MTA
        @endif
    </title>

    <!-- Favicons -->
    <link href="{{ asset('marketing/img/favicon-mta.png') }}" rel="icon">
    <link href="{{ asset('marketing/img/apple-touch-icon-mta.png') }}" rel="apple-touch-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('webapp/vendor/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('webapp/vendor/font-awesome-4/css/font-awesome.min.css') }}">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('webapp/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('webapp/css/stylesheet.css') }}">
    <!-- Datepicker -->
    <link rel="stylesheet" href="{{ asset('webapp/css/jquery-ui.css') }}">
    <!-- JQuery -->
    <script src="{{ asset('webapp/vendor/jquery/jquery.min.js') }}"></script>
</head>

<body class="sidebar-fixed header-fixed">
<div class="page-wrapper">
    <nav class="navbar page-header">
        {{--Toogle side mobile bar--}}
        <a href="#" class="btn btn-link sidebar-mobile-toggle d-md-none mr-auto">
            <i class="fa fa-bars"></i>
        </a>
        {{--Toogle side navigation bar--}}
{{--        <a href="#" class="btn btn-link sidebar-toggle d-md-down-none">--}}
{{--            <i class="fa fa-bars"></i>--}}
{{--        </a>--}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('webapp/imgs/logo-mta1.png') }}" alt="mta_logo">
        </a>

        <ul class="navbar-nav ml-auto">
{{--            <li class="nav-item d-md-down-none">--}}
{{--                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                    <i class="fa fa-bell"></i>--}}
{{--                    <span class="badge badge-pill badge-danger">5</span>--}}
{{--                    <span class="small ml-1 d-md-down-none"></span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <a href="{{ route('account.profile') }}" class="dropdown-item">--}}
{{--                        <i class="fa fa-sticky-note"></i>Notifications--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </li>--}}
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" title="Messages" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-envelope-open"></i>
                    <span class="badge badge-pill badge-danger">{{ \App\Models\User::unreadMessagesCount() }}</span>
                    <span class="small ml-1 d-md-down-none"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('message.inbox') }}" class="dropdown-item">
                        <i class="fa fa-envelope"></i>@if (\App\Models\User::unreadMessagesCount() > 0)You Got Mail @else No Mail Today @endif
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" title="Account and Settings" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user fa-lg" aria-hidden="true"></i>
                    <span class="small ml-1 d-md-down-none"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('account.profile') }}" class="dropdown-item">
                        <i class="fa fa-calculator"></i>Account
                    </a>
                    @if(Auth::user()->teacher)
                        <a href="{{ route('teacher.studioIndex') }}" class="dropdown-item">
                            <i class="fa fa-wrench"></i>Settings
                        </a>
                    @endif
                    <a href="{{ route('contact') }}" class="dropdown-item">
                        <i class="fa fa-life-ring"></i>Support
                    </a>
                    <a id="logout-link" class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                        @honeypot
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    @include('layouts.webnav')
</div>
<!-- end main-container -->

<!-- Javascript Libraries -->
{{--Controls Dashboard UI conflicting with Calendar--}}
<script src="{{ asset('webapp/vendor/popper.js/popper.min.js') }}"></script>
<script src="{{ asset('webapp/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('webapp/vendor/chart.js/chart.min.js') }}"></script>
<script src="{{ asset('webapp/js/carbon.js') }}"></script>
<script src="{{ asset('webapp/js/demo.js') }}"></script>
@yield('scripts')
<script src="{{ asset('webapp/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('webapp/js/datepicker.js') }}"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-146819127-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'UA-146819127-1');
</script>
</body>
</html>
