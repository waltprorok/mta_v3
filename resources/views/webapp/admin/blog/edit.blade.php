@extends('layouts.webapp')
@section('title', 'Admin Edit Blog')
@section('content')

    <div class="col-12">
        <h4>Edit Blog Post</h4>
        <br>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.blog.update', $blog->id) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" value="{{ $blog->title }}">

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control" name="slug" value="{{ $blog->slug }}">

                                @if ($errors->has('slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body">Body</label>&nbsp;<small>*Markdown Available</small>
                                <textarea class="form-control" name="body" rows="24">{{ $blog->body }}</textarea>

                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="image">Featured Image</label>
                                @if ($blog->image_url)
                                    <div class="text-center">
                                        <img src="{{ $blog->image_url }}" alt="{{ $blog->image }}" width="100%">
                                    </div>
                                    <input type="hidden" name="updateImage" value="{{ $blog->image }}">
                                @else
                                    <div class="text-center">
                                        <p>There is no image for this news article.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('released_on') ? ' has-error' : '' }}">
                                <label for="released_on">Release Date</label>
                                <input type="text" class="form-control" name="released_on" id="blogRelease"
                                       autocomplete="off" value="{{ date('Y-m-d', strtotime($blog->released_on)) }}">

                                @if ($errors->has('released_on'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('released_on') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('release_time') ? ' has-error' : '' }}">
                                <label for="start_time" class="control-label">Release Time</label>
                                <select class="form-control" id="release_time" name="release_time">
                                    <option value="{{ $blog->date_blog_raw }}">{{ $blog->date_hour_min }}</option>
                                    <option value="09:00:00">9:00 AM</option>
                                    <option value="12:00:00">12:00 PM</option>
                                    <option value="15:00:00">3:00 PM</option>
                                </select>

                                @if ($errors->has('release_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('release_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                <label for="image">Update Image</label>
                                <input type="file" class="form-control" name="image">

                                @if ($errors->has('image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pull-left">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.blog.list') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
