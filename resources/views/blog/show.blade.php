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
                        <h4>{{ $blog->title }}</h4>
                        <p>{!! $blog->body_html !!}</p>
                    </div>
                </div>
            </main>
            <!-- /Main -->
        </div>
        <!-- /Row -->
    </div>
    <!-- /Container -->
    <!-- /Blog -->

@endsection
