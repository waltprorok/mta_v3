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
                    <h2 class="title">Music Teachers Aid Blog</h2>
                </div>
                @foreach ($blogs as $blog)
                    <div class="col-md-6">
                        <div class="blog thumbnail">
                            @if ($blog->image_url)
                                <div class="blog-img">
                                    <a href="{{ route('blog.show', $blog->slug) }}">
                                        <img class="img-responsive" src="{{ $blog->image_url }}" alt="{{ $blog->image }}">
                                    </a>
                                </div>
                            @endif
                            <div class="blog-content">
                                <h3>{{ $blog->title }}</h3>
                                <ul class="blog-meta">
                                    <li><i class="fa fa-user"></i>{{ $blog->author->first_name . ' ' . $blog->author->last_name }}</li>
                                    <li><i class="fa fa-clock-o"></i>{{ $blog->date_time }}</li>
                                </ul>
                                <p>
                                    <small>{!! \Illuminate\Support\Str::limit($blog->body_html, 160) !!}</small>
                                </p>
                                <p class="text-right">
                                    <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-default">Read More</a>
                                </p>
                            </div>
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
