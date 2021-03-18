@extends('layouts.app')
@section('title', 'Blog')
@section('content')

    <!-- Blog -->
    <div id="blog" class="reg-padding">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
                <div class="section-header text-center">
                    <h2 class="title">Music Teachers Aid Blog</h2>
                </div>
                @foreach ($blogs as $blog)
                    <div class="col-md-4">
                        <div class="thumbnail">
                            @if ($blog->image_url)
                                <a href="{{ route('blog.show', $blog->slug) }}">
                                    <img src="{{ $blog->image_url }}" alt="{{ $blog->image }}">
                                </a>
                            @endif
                            <div class="caption">
                                <h3>{{ $blog->title }}</h3>
                                <div class="blog-content">
                                    <ul class="blog-meta">
                                        <li>
                                            <small><b>{{ $blog->author->first_name . ' ' . $blog->author->last_name }}</b></small>
                                        </li>
                                        <li><small>{{ $blog->date_time }}</small></li>
                                    </ul>
                                    <p>
                                        {!! \Illuminate\Support\Str::limit($blog->body_html, 110) !!}
                                    </p>
                                    <p class="text-right">
                                        <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-default">Read More</a>
                                    </p>
                                </div>
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
