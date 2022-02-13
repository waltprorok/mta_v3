@extends('layouts.webapp')
@section('title', 'Admin Blog')
@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

    <div class="col-12">
        <h4>Blog Posts</h4>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary float-right"><i class="fa fa-plus"></i> Create Post</a>
        <br>
        <br>
        <div class="card">
            <div class="card-body">
                <table class="table table-responsive-md" id="dtBlogIndex">
                    <thead class="thead">
                    <tr>
                        <th scope="col" data-orderable="false">Image</th>
                        <th scope="col">Title</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Released On</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col" data-orderable="false">Functions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($blogs as $blog)
                        <tr>
                            <td><img width="40" src="{{ $blog->image_url }}" alt="{{ $blog->image }}"></td>
                            <td>{{ $blog->title }}</td>
                            <td>{{ $blog->slug }}</td>
                            <td>{{ $blog->released_on }}</td>
                            <td>{{ $blog->created_at }}</td>
                            <td>{{ $blog->updated_at }}</td>
                            <td class="text-nowrap">
                            <span class="align-baseline"><a href="{{ route('admin.blog.edit', $blog->id )}}" class="btn btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="btn btn-outline-dark" role="button" title="view"><i class="fa fa-chrome" aria-hidden="true"></i></a>
                            <button type="button" class="btn btn-outline-danger" title="delete" data-toggle="modal" data-target="#myDeleteModal-{{ $blog->id }}"><i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button></span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            @foreach($blogs as $blog)
        <!-- Modal -->
        <div class="modal fade" id="myDeleteModal-{{ $blog->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Delete Blog</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <p>Are you sure you want to delete this blog article?</p>
                    </div>

                    <div class="modal-footer">
                        <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            &nbsp;<button type="submit" class="btn btn-danger pull-right">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- End of Modal -->
            @endforeach

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dtBlogIndex').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

@endsection
