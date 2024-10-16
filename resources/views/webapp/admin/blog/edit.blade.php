@extends('layouts.webapp')
@section('title', 'Admin Edit Blog')
@section('content')

    <div class="col-12">
        <h4>Edit Blog Post</h4>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"></li>
        </ul>

        <div id="app">
            <edit-blog></edit-blog>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
