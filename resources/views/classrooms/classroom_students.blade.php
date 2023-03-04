@extends('layouts.app')
@section('title', 'Students')

<script src="{{asset('js/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">

@section('content')
<main class='page'>
<section class="clean-block clean-catalog dark">
    <div class="container">
        <div class="content" style="margin-top: 2rem;">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <table id='students-table'>
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Degree</th>
                                        <th scope="col">Institute</th>
                                        <th scope="col">Gender</th>
                                        <th scope="col">Joined</th>
                                        @auth('instructor')
                                        <th scope="col">View grades</th>
                                        <th scope="col">Remove</th>
                                        @endauth
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                    <tr>
                                        <td>
                                            @auth('instructor')
                                            <a class="nav-item nav-link" href="{{ Route('instructor_get_user', ['id' => $student->id, 'slug' => $classroom->slug , 'role'=> 'student' ])}}">{{ $student->name }}</a>
                                            @endauth
                                            @auth('web')
                                            <a class="nav-item nav-link" href="{{ Route('student_get_user', ['id' => $student->id, 'slug' => $classroom->slug , 'role'=> 'student' ])}}">{{ $student->name }}</a>
                                            @endauth
                                            @auth('admin')
                                            <a class="nav-item nav-link" href="{{ Route('student_get_user', ['id' => $student->id, 'slug' => $classroom->slug , 'role'=> 'student' ])}}">{{ $student->name }}</a>
                                            @endauth
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->degree }}</td>
                                        <td>{{ $student->institute }}</td>
                                        <td>{{ $student->gender }}</td>
                                        <td>{{ $student->date_joined }}</td>
                                        @auth('instructor')
                                        <td><button type="button" class="btn btn-outline-primary" onclick="window.location.href='#'">View grades</button></td>
                                        <form action="{{ Route('instructor_classrooms.students.delete', ['student_id' => $student->id, 'slug' => $classroom->slug]) }}" method="POST">
                                            @csrf
                                            <td><button type="submit" class="btn btn-danger">Remove</button></td>
                                        </form>
                                        @endauth
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection

<script>
    $(document).ready(function() {
        $('#students-table').DataTable();
    });
</script>
