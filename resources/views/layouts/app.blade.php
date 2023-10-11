<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Music Teachers Aid is an automated studio for music teachers. Start your 30-day free trial today!">
    <meta name="author" content="Walter Prorok">
    <meta name="keywords" content="music teacher software, music teacher web app, automated music teacher studio">
    <meta name="robots" content="index, follow">
    <meta name="google-site-verification" content="IV3PI1a4frdf1GVIKurcc_lOg6awG8gqqOoRkWrugDI"/>

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
                <li><a href="{{ url('/#testimonials') }}">Testimonials</a></li>
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                <li><a href="{{ route('pricing') }}">Pricing</a></li>
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li class="active"><a href="{{ route('register') }}">Sign Up Today!</a></li>
                @else
                    <li class="has-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"
                           aria-haspopup="true" v-pre>Account
                        </a>
                        <ul class="dropdown">
                            <li><a href="{{ route('dashboard') }}"> Dashboard </a></li>
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
    <!-- Container -->
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="footer-widget ">
                    <div class="footer-title">Company</div>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="footer-widget ">
                    <div class="footer-title">Quick Links</div>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
            </div>
            {{--            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">--}}
            {{--                <div class="footer-widget ">--}}
            {{--                    <div class="footer-title">Social</div>--}}
            {{--                    <ul class="list-unstyled">--}}
            {{--                        <li><a href="#">Facebook</a></li>--}}
            {{--                        <li><a href="#">Twitter</a></li>--}}
            {{--                        <li><a href="#">LinkedIn</a></li>--}}
            {{--                        <li><a href="#">YouTube</a></li>--}}
            {{--                    </ul>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div class="col-xl-3 col-lg-4 col-md-12 col-sm-6 col-6 col-lg-offset-2">
                <div class="footer-widget ">
                    <h3 class="footer-title">Subscribe to Our News Letter</h3>
                    <form method="post" action="{{ route('newsletter') }}">
                        @csrf
                        @honeypot
                        <div class="newsletter-form input-group">
                            <input class="form-control" name="email" placeholder="Enter Your Email Address" type="text">
                            <span class="input-group-btn">
                               <button class="btn btn-primary" type="submit">Submit</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Row -->
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
                        <a href="{{ route('home') }}">Music Teachers Aid, LLC</a></p>
                </div>
                <!-- /footer copyright -->
            </div>
        </div>
        <!-- /Row -->
    </div>
    <!-- /Container -->
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
{{--<script src="{{ asset('js/app.js') }}"></script>--}}

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
