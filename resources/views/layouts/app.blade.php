<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Music Teachers Aid is a suite of music lesson tools that helps private music teachers manage in-person music lessons. Start your 30-day free trial today!">
    <meta name="author" content="Walter Prorok">
    <meta name="keywords" content="music teacher software, music teacher web app, automated music teacher studio, independent music teacher">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="google-site-verification" content="IV3PI1a4frdf1GVIKurcc_lOg6awG8gqqOoRkWrugDI"/>
    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Music Teachers Aid">
    <meta property="og:title" content="Music Teachers Aid">
    <meta property="og:description" content="Music Teachers Aid is a suite of music lesson tools that helps private music teachers manage in-person music lessons. Start your 30-day free trial today!">
    <meta property="og:locale" content="en_US">
    <meta property="og:image:type" content="image/png">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('marketing/img/twitter-og.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Music Teachers Aid">
    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Music Teachers Aid">
    <meta name="twitter:description" content="Music Teachers Aid is a suite of music lesson tools that helps private music teachers manage in-person music lessons. Start your 30-day free trial today!">
    <meta name="twitter:image" content="{{ asset('marketing/img/twitter-og.png') }}">
    <meta name="twitter:image:alt" content="Music Teachers Aid">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(View::hasSection('title'))
            @yield('title') | {{ config('app.name', 'MTA') }}
        @else
            {{ config('app.name', 'MTA') }}
        @endif
    </title>

    <!-- Favicons -->
    <link href="{{ asset('marketing/img/favicon-mta.png') }}" rel="icon">
    <link href="{{ asset('marketing/img/apple-touch-icon-mta.png') }}" rel="apple-touch-icon">
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CVarela+Round" rel="stylesheet">
    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('marketing/css/bootstrap.min.css') }}"/>
    <!-- Owl Carousel -->
    <link type="text/css" rel="stylesheet" href="{{ asset('marketing/css/owl.carousel.css') }}"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('marketing/css/owl.theme.default.css') }}"/>
    <!-- Magnific Popup -->
    <link type="text/css" rel="stylesheet" href="{{ asset('marketing/css/magnific-popup.css') }}"/>
    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('marketing/css/font-awesome.min.css') }}"/>
    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('marketing/css/style.css') }}"/>
    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
</head>

<body>
<!-- Header -->
<header>
    <!-- Nav -->
    <nav id="nav" class="navbar nav-transparent">
        <div class="container">
            <div class="navbar-header">
                <!-- Logo -->
                <div class="navbar-brand">
                    <a href="{{ route('home') }}">
                        <img class="logo" src="{{ asset('marketing/img/logo1.png') }}" alt="logo">
                        <img class="logo-alt" src="{{ asset('marketing/img/logo-alt1.png') }}" alt="logo">
                    </a>
                </div>
                <!-- /Logo -->
                <!-- Collapse nav button -->
                <div class="nav-collapse">
                    <span></span>
                </div>
                <!-- /Collapse nav button -->
            </div>

            <!--  Main navigation  -->
            <ul class="main-nav nav navbar-nav navbar-right">
                <li><a class="page-scroll" href="{{ url('/#about') }}">About</a></li>
                <li><a href="{{ url('/#features') }}">Features</a></li>
                <li><a href="{{ url('/#pricing') }}">Pricing</a></li>
                <li><a href="{{ url('/#testimonials') }}">Testimonials</a></li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li class="active"><a href="{{ route('register') }}">Sign Up Today!</a></li>
                @else
                    <li class="has-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"
                           aria-haspopup="true">Account
                        </a>
                        <ul class="dropdown">
                            @if(Auth::user()->admin)
                                <li><a href="{{ route('admin.blog.list') }}"> Admin Section </a></li>
                            @endif
                            @if(Auth::user()->teacher || Auth::user()->student || Auth::user()->parent)
                                <li><a href="{{ route('dashboard') }}"> Dashboard </a></li>
                            @endif
                            <li>
                                <a id="logout-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                    @honeypot
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
            <!-- /Main navigation -->
        </div>
    </nav>
    <!-- /Nav -->
</header>

<!-- /Header -->
<div class="container">
    <div class="sm-padding"></div>
</div>
<div class="container">
    @include('partials.alerts')
</div>

@yield('content')

<!-- Footer -->
<footer id="footer" class="sm-padding bg-dark">
    <div class="container">
        <div class="row">
            <!-- Brand -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="footer-widget">
                    <div style="display: flex; align-items: center; margin-bottom: 20px;">
                        <img src="{{ asset('marketing/img/apple-touch-icon-mta.png') }}"
                             alt="Music Teacher's Aid"
                             width="60"
                             height="60"
                             style="margin-right: 15px; border-radius: 8px;">

                        <h4 style="color: #fff; margin: 0; font-size: 20px; font-weight: 600;">
                            Music Teacher's Aid
                        </h4>
                    </div>

                    <p style="margin: 0; line-height: 1.7;">
                        Spend less time managing your music studio
                        and more time doing what you love — teaching music.
                    </p>

                </div>
            </div>

            <!-- Features -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                <div class="footer-widget">
                    <div class="footer-title">Features</div>

                    <ul class="list-unstyled">
                        <li><a href="/#features">Features</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('register') }}">Get Started</a></li>
                    </ul>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                <div class="footer-widget">
                    <div class="footer-title">Quick Links</div>

                    <ul class="list-unstyled">
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li>
                            <a href="https://www.youtube.com/@walterprorok213"
                               target="_blank"
                               rel="noopener noreferrer">
                                YouTube
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Legal -->
            <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                <div class="footer-widget">
                    <div class="footer-title">Legal</div>

                    <ul class="list-unstyled">
                        <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- Copyright -->
        <div class="row">
            <div class="col-md-12">
                <!-- footer logo -->
                <div class="footer-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('marketing/img/logo-alt1.png') }}" alt="logo"></a>
                </div>
                <!-- /footer logo -->
                <!-- footer copyright -->
                <div class="footer-copyright">
                    <p>Copyright &copy; {{ now()->year }}. All Rights Reserved. Designed by
                        <a href="https://www.linkedin.com/in/walter-prorok/" target="_blank">Music Teachers Aid, LLC</a>
                    </p>
                </div>
                <!-- /footer copyright -->
            </div>
        </div>
    </div>
</footer>
<!-- /Footer -->

<!-- Back to top -->
<div id="back-to-top"></div>
<!-- /Back to top -->

<!-- Preloader -->
<div id="preloader">
    <div class="preloader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<!-- /Preloader -->

<!-- jQuery Plugins -->
<script type="text/javascript" src="{{ asset('marketing/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('marketing/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('marketing/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('marketing/js/jquery.magnific-popup.js') }}"></script>
<script type="text/javascript" src="{{ asset('marketing/js/main.js') }}"></script>

<!-- Scripts -->
{{--<script src="{{ mix('js/app.js') }}"></script>--}}
@production
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
@endproduction
</body>
</html>
