@extends('layouts.app')
@section('title', 'exams')

<script src="{{asset('js/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">

@section('content')
    <main class='page'>
        <section class="clean-block clean-catalog dark">
            <div class="container">
                <div class="content" style="margin-top: 2rem;">
                    <div class="row">
                        <div class="m-3">
                            <a href="{{route('instructor_classrooms.exams.create', ['slug' => $classroom->slug])}}" class="btn btn-success" id="add-classroom-btn"><i class="fas fa-plus"></i> Add new Exam</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <table id='exams-table'>
                                    <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col">Total mark</th>
                                        <th scope="col">Publish status</th>
                                        <th scope="col">Max attempts</th>
                                        <th scope="col">View</th>
                                        <th scope="col">Publish</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($exams as $exam)
                                        <tr>
                                            <td>
                                                <a class="nav-item nav-link" href="">{{ $exam->title }}</a>
                                            </td>
                                            <td>{{ $exam->duration }}</td>
                                            <td>{{ $exam->total_mark }}</td>
                                            <td>{{ $exam->publish_status }}</td>
                                            <td>{{ $exam->max_attempts }}</td>
                                            <td>
                                                <a href="{{route('instructor_classrooms.exams.questions', ['slug' => $classroom->slug, 'exam_slug' => $exam->slug])}}" class="btn btn-primary">View</a>
                                            </td>
                                            @if($exam->publish_status == "false")
                                                <td>
                                                    <a href="{{route('instructor_classrooms.exams.publish', ['slug' => $classroom->slug, 'exam_slug' => $exam->slug])}}" class="btn btn-success">Publish</a>
                                                </td>
                                            @else
                                                <td>
                                                    Exam is published
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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
        $('#exams-table').DataTable();
    });
</script>
