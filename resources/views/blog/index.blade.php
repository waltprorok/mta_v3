@extends('layouts.app')
@section('title', 'Blog')
@section('content')

    <!-- Blog -->
    <div id="blog" class="section reg-padding">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title">Blog</h2>
                </div>
                @foreach ($blogs as $blog)
                    <div class="col-md-12 col-lg-12">
                        <div class="blog thumbnail">
                            <article class="post vt-post">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
                                        <div class="post-type post-img">
                                            <a href="{{ route('blog.show', $blog->slug) }}">
                                                <img class="img-responsive" src="{{ $blog->image_url }}" alt="{{ $blog->image }}">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-8">
                                        <div class="blog-content">
                                            <h2 class="md-heading">{{ $blog->title }}</h2>
                                            <ul class="blog-meta">
                                                <li><i class="fa fa-user"></i>{{ $blog->author->first_name . ' ' . $blog->author->last_name }}</li>
                                                <li><i class="fa fa-clock-o"></i>{{ $blog->date_time }}</li>
                                            </ul>
                                            <p>{{ $blog->body_short }}</p>
                                            <p class="text-right">
                                                <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-default">Read More</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- /Container -->
    </div>
    <!-- /Blog -->

    <div class="container">
        <nav class="text-center">
            {{ $blogs->links() }}
        </nav>
    </div>

@endsection
