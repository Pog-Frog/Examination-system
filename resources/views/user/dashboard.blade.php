@extends('layouts.app')

@section('title', 'Welcome to Dashboard')

<link rel="stylesheet" href="{{asset('css/instructor_dashboard.css')}}">

@section('content')
<main class="page">
    @if($classrooms->count() > 0)
    <div class="container mt-5">
        <div class="mb-5" style="margin-top: 5rem;">
            <form class="form-inline" method="POST" action="{{route('student_classroom.join')}}">
                <row class="row">
                    <div class="col col-3">
                        <input type="text" class="form-control" name="code" id="code" placeholder="Enter Classroom Code">
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Join new Classroom</button>
                    </div>
            </form>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-4">
            @foreach($classrooms as $classroom)
            <div class="col">
                <div class="card shadow-sm">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" text-anchor="middle" fill="#eceeef" dy=".3em">{{$classroom->name}}</text>
                    </svg>
                    <div class="card-body">
                        <p class="card-text">{{$classroom->info}}</p>
                        <p class="card-text"><i class="fas fa-book"></i> Upcoming exams: {{$classroom->exams_count}}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="{{route('student_classroom.show', $classroom->slug)}}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{route('student_classroom.leave', $classroom->slug)}}" class="btn btn-sm btn-outline-secondary">Leave</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <section class="page_404">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="col d-flex align-items-center justify-content-center">
                        <div class="four_zero_four_bg" style="background-image:url({{url('img/dribbble_1.gif')}});">
                            <h1 class="text-center ">404</h1>
                        </div>
                        <div class="contant_box_404">
                            <h3 class="h2">
                                You are not associated with any classroom yet
                            </h3>
                            <p>Enter the code below to join a classroom</p>
                            <form class="form-inline" method="POST" action="{{route('student_classroom.join')}}">
                                <input type="text" class="form-control mb-2 mr-sm-2" name="code" id="inlineFormInputName2" placeholder="Enter a code to join a classroom">
                                <button type="submit" class="btn btn-primary mb-2">Join</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</main>
@endsection