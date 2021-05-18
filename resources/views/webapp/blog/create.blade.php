@extends('layouts.webapp')
@section('title', 'Admin Create Blog')
@section('content')

    <div class="col-12">
        <h4>Create Blog Post</h4>
        <br>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.blog.save') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title" class="control-label">Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}">

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                <label for="slug" class="control-label">Slug</label>
                                <input type="text" class="form-control" name="slug" value="{{ old('slug') }}">

                                @if ($errors->has('slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('released_on') ? ' has-error' : '' }}">
                                <label for="released_on" class="control-label">Release Date</label>
                                <input type="text" class="form-control" name="released_on" id="blogRelease"
                                       autocomplete="off">

                                @if ($errors->has('released_on'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('released_on') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                                <label for="start_time" class="control-label">Release Time</label>
                                <select class="form-control" id="release_time" name="release_time">
                                    <option value="09:00:00">9:00 am</option>
                                    <option value="12:00:00">12:00 pm</option>
                                    <option value="15:00:00">3:00 pm</option>
                                </select>

                                @if ($errors->has('start_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('start_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="image" class="control-label">Image</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body" class="control-label">Body</label>
                                <textarea class="form-control" name="body" rows="16">{{ old('body') }}</textarea>

                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.blog.list') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

@endsection
