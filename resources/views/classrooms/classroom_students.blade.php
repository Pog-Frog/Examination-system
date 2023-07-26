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
                            @guest('web')
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
                                        <th scope="col">View Cheating Attempts</th>
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
                                                <form action="#" method="POST">
                                                    @csrf
                                                    <td><button type="submit" class="btn btn-outline-primary">View grades</button></td>
                                                </form>

                                                 <form action="{{ Route('instructor_classrooms.students.cheat', ['slug' => $classroom->slug, 'student_slug' => $student->slug]) }}" method="POST">
                                                    @csrf
                                                    <td><button type="submit" class="btn btn-outline-primary">Cheating attempts</button></td>
                                                </form>
                                                <form action="{{ Route('instructor_classrooms.students.delete', ['student_id' => $student->id, 'slug' => $classroom->slug, 'student_slug' => $student->slug ]) }}" method="POST">
                                                    @csrf
                                                    <td><button type="submit" class="btn btn-danger">Remove</button></td>
                                                </form>
                                            @endauth
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endguest
                            @auth('web')
                            <h4 class="text-info clean-product-item"><dt>Instructors</dt></h4>
                            @foreach($instructors as $instructor)
                                <div class="user mt-1" style="padding-left: 3rem;">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <img src="{{ asset('ProfilePics/instructors/' . $instructor->photo) }}" alt="user" class="profile-photo-lg">
                                        </div>
                                        <div class="col pt-3">
                                            <h5>{{ $instructor->name }}</h5>
                                            <p>Instructor</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <h4 class="text-info clean-product-item"><dt>Students</dt></h4>
                            @foreach($students as $student)
                                <div class="user mt-3" style="padding-left: 3rem;">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <img src="{{ asset('ProfilePics/students/' . $student->photo) }}" alt="user" class="profile-photo-lg">
                                        </div>
                                        <div class="col pt-3 ">
                                            <h5>{{ $student->name }}</h5>
                                            <p>Student</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection

<style>
.people {
  background: #f8f8f8;
  border-radius: 4px;
  border: 1px solid #f1f2f2;
  padding: 20px;
  margin-bottom: 20px;
}

.people {
  height: 300px;
  width: 100%;
  border: none;
}

.people .user{
  padding: 20px 0;
  border-top: 1px solid #f1f2f2;
  border-bottom: 1px solid #f1f2f2;
  margin-bottom: 20px;
}

img.profile-photo-lg{
  height: 80px;
  width: 80px;
  border-radius: 50%;
}
</style>

<script>
    $(document).ready(function() {
        $('#students-table').DataTable();
    });
</script>
