@extends('layouts.webapp')
@section('title', 'Admin Teachers')
@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

    <div class="col-12">
        <h4>Teachers</h4>
        <br>
        <div class="card">
            <div class="card-body">
                <table class="table table-responsive-md" id="dtTeacherIndex">
                    <thead class="thead">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Address 2</th>
                        <th scope="col">City</th>
                        <th scope="col">State</th>
                        <th scope="col">Zip</th>
                        <th scope="col" data-orderable="false">Phone</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->first_name }}</td>
                            <td>{{ $teacher->last_name }}</td>
                            <td>{{ $teacher->address }}</td>
                            <td>{{ $teacher->address_2 }}</td>
                            <td>{{ $teacher->city }}</td>
                            <td>{{ $teacher->state }}</td>
                            <td>{{ $teacher->zip }}</td>
                            <td>{{ $teacher->phone_number }}</td>
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
            $('#dtTeacherIndex').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

@endsection
