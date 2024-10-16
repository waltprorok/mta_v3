@extends('layouts.webapp')
@section('title', 'Admin Create Blog')
@section('content')

    <div class="col-12">
        <h4>Create Blog Posts</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"></li>
        </ul>

        <div id="app">
            <create-blog></create-blog>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
