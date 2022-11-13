@extends('layouts.app')
@section('title', 'Blog')
@section('content')

    <!-- Blog -->
    <!-- Container -->
    <div class="container" id="blog">
        <!-- Row -->
        <div class="row">
            <!-- Main -->
            <main id="main" class="col-md-8 col-md-offset-2 sm-padding">
                <div class="blog">
                    <div class="blog-content">
                        <h2>{{ $blog->title }}</h2>
                        <ul class="blog-meta">
                            <li><i class="fa fa-clock-o"></i>{{ $blog->date_time }}</li>
                            <li>
                                <i class="fa fa-user"></i>{{ $blog->author->first_name . ' ' . $blog->author->last_name }}
                            </li>
                        </ul>
                    </div>
                    @if ($blog->image_url)
                        <div class="blog-img">
                            <div class="single-blog-img">
                                <img width="100%" src="{{ $blog->image_url }}" alt="{{ $blog->image }}">
                            </div>
                        </div>
                    @endif
                    <div class="blog-content">
                        <br/>
                        <h4>{{ $blog->title }}</h4>
                        <p>{!! $blog->body_html !!}</p>
                        <br/>
                    </div>
                </div>
            </main>
            <!-- /Main -->
        </div>
        <!-- /Row -->

        <hr>

        <div id="blog" class="section lg-padding">
            <!-- Container -->
            <div class="container">
                <!-- Row -->
                <div class="row">
                    <!-- Section header -->
                    <div class="section-header text-center">
                        <h2 class="title">Subscribe to Our News Letter</h2>
                        <h3>Subscribe to stay up to date with the latest and greatest news and releases.</h3>
                    </div>
                    <!-- /Section header -->
                    <!-- blog -->
                    <div class="col-md-6 col-md-offset-3">
                        <form method="post" action="{{ route('newsletter') }}">
                            @csrf
                            @honeypot
                            <div class="input-group">
                                <input class="form-control" name="email" placeholder="Enter Your Email Address" type="text">
                                <span class="input-group-btn">
                               <button class="btn btn-primary" type="submit">Submit</button>
                            </span>
                            </div>
                        </form>
                    </div>
                    <!-- /blog -->
                </div>
                <!-- /Row -->
            </div>
            <!-- /Container -->
        </div>
    </div>
    <!-- /Container -->
    <!-- /Blog -->

@endsection
