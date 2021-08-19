@extends('layouts.webapp')
@section('title', 'Admin Users')
@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

    <div class="col-12">
        <h4>Users</h4>
        <br>
        <div class="card">
            <div class="card-body">
                <table class="table" id="dtUserIndex">
                    <thead class="thead">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Admin</th>
                        <th scope="col">Teacher</th>
                        <th scope="col">Student</th>
                        <th scope="col">Parent</th>
                        <th scope="col">Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->admin }}</td>
                            <td>{{ $user->teacher }}</td>
                            <td>{{ $user->student }}</td>
                            <td>{{ $user->parent }}</td>
                            <td>{{ $user->created_at }}</td>
                            {{--<td class="text-nowrap">--}}
                            {{--<span class="align-baseline"><a href="{{ route('admin.blog.edit', $teacher->id )}}" class="btn btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>--}}
                            {{--<a href="{{ route('blog.show', $teacher->slug) }}" target="_blank" class="btn btn-outline-dark" role="button" title="view"><i class="fa fa-chrome" aria-hidden="true"></i></a>--}}
                            {{--<button type="button" class="btn btn-outline-danger" title="delete" data-toggle="modal" data-target="#myDeleteModal-{{ $teacher->id }}"><i class="fa fa-trash-o" aria-hidden="true"></i>--}}
                            {{--</button></span>--}}
                            {{--</td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dtUserIndex').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

@endsection
