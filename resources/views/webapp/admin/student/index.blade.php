@extends('layouts.webapp')
@section('title', 'Admin Students')
@section('content')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

    <div class="col-12">
        <h4>Students</h4>
        <br>
        <div class="card">
            <div class="card-body">
                <table class="table table-responsive-md" id="dtStudentIndex">
                    <thead class="thead">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col" data-orderable="false">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Instrument</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->phone_number }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->instrument }}</td>
                            <td>
                                @if ($student->status == \App\Models\Student::ACTIVE) Active
                                @elseif($student->status == \App\Models\Student::WAITLIST) Waitlist
                                @elseif($student->status == \App\Models\Student::LEAD) Lead
                                @elseif($student->status == \App\Models\Student::INACTIVE) Inactive
                            </td>
                            @endif
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
            $('#dtStudentIndex').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
    </script>

@endsection
