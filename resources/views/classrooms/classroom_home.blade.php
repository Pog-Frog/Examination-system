@extends('layouts.app')

@section('title', 'Classroom home')

@section('content')
    <main class="page">
        @auth('instructor')
            <div class="container-fluid container" style="margin-top: 4rem;">
                <div class="row mb-3">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                         xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                         preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <text x="15%" y="70%" font-size="40px" fill="#eceeef" dy=".3em">{{$classroom->name}}</text>
                    </svg>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-header btn-outline-dark">
                                        <h5>Upcoming Exams</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @if($exams->count() > 0)
                                                @foreach($exams as $exam)
                                                    <li class="list-group-item">{{ $exam->title }}<br/> Due
                                                        date: {{$exam->start_date}}</li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item">No work</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="card">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h4 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed btn-outline-dark"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                                        aria-controls="flush-collapseOne">
                                                    Course description
                                                </button>
                                            </h4>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                                 aria-labelledby="flush-headingOne"
                                                 data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">{{ $classroom->info }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row mb-3">
                            <div class="col">
                                <div class="card">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                    aria-expanded="false" aria-controls="flush-collapseTwo">
                                                Post Announcement
                                            </button>
                                        </h2>
                                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                             aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <form
                                                    action="{{ Route('instructor_classrooms.announce', $classroom->slug) }}"
                                                    method="POST">
                                                    <div class="form-group">
                                                        <input type="text" name="title" class="form-control"
                                                               placeholder="Announcement Title"
                                                               value="{{ old('title') }}">
                                                        <textarea class="form-control mt-2" name="text"
                                                                  placeholder="Write your announcement here"
                                                                  value="{{ old('text') }}" rows="3"
                                                                  onkeypress="if(event.keyCode==13) { this.form.submit(); }"></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            @if($announcements->count() > 0)
                                @foreach($announcements as $announcement)
                                    <div class="row">
                                        <div class="col mt-2">
                                            <div class="card">
                                                <div class="card-header">
                                                    @if ($announcement->announcement_author->photo == null)
                                                        <a href="{{ Route('instructor_profile') }}"
                                                           class="btn btn-light mr-2">{{ Auth::guard('instructor')->user()->name }}</a>
                                                    @else
                                                        @if($announcement->announcement_author()->author_role == 'instructor')
                                                            <img
                                                                src="{{ asset('ProfilePics/instructors/' . $announcement->announcement_author->photo) }}"
                                                                alt="avatar" class="img-fluid rounded-circle ml-1"
                                                                style="max-height: 35px; width: 30px">
                                                        @else
                                                            <img
                                                                src="{{ asset('ProfilePics/students/' . $announcement->announcement_author->photo) }}"
                                                                alt="avatar" class="img-fluid rounded-circle ml-1"
                                                                style="max-height: 35px; width: 30px">
                                                        @endif
                                                        <a href="{{ Route('instructor_get_user', ['id' => $announcement->announcement_author->id, 'slug' => $classroom->slug , 'role'=> $announcement->announcement_author()->author_role ])}}"
                                                           class="btn btn-light mr-2">{{ $announcement->announcement_author->name }}
                                                            &nbsp&nbsp<small><a class="btn btn-outline-primary btn-sm" role="button" style="cursor: default">{{ $announcement->announcement_author()->author_role }}</a>
                                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{{ $announcement->date_created }}</small>
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="card-body">

                                                    <h6>{{ $announcement->title }}</h6>
                                                    <hr>
                                                    <p>{{ $announcement->text }}</p>
                                                </div>
                                                <div class="card-header">
                                                    <a href="{{ Route('instructor_classrooms.announcements.comments', ['slug' => $classroom->slug, 'id' => $announcement->id])}}"
                                                       class="btn btn-light mr-2"><i class="fas fa-comment"></i>&nbsp&nbsp{{ $announcement->getComments()->count() }}
                                                        &nbsp&nbspClass comments
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                        </div>
                        @else
                            <div class="row">
                                <div class="col mt-2 mb-3">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-center">
                                            <img src="{{ asset('img/no_content.png') }}" alt="No Announcements"
                                                 class="img-fluid" style="max-width: 50%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endauth
        @auth('web')
            <div class="container-fluid container" style="margin-top: 4rem;">
                <div class="row mb-3">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                         xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                         preserveAspectRatio="xMidYMid slice" focusable="false">
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <text x="15%" y="70%" font-size="40px" fill="#eceeef" dy=".3em">{{$classroom->name}}</text>
                    </svg>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-header btn-outline-dark">
                                        <h5>Upcoming Exams</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @if($exams->count() > 0)
                                                @foreach($exams as $exam)
                                                    <li class="list-group-item">{{ $exam->title }}<br/> Due
                                                        date: {{$exam->start_date}}</li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item">No work</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="card">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h4 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed btn-outline-dark"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                                        aria-controls="flush-collapseOne">
                                                    Course description
                                                </button>
                                            </h4>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                                 aria-labelledby="flush-headingOne"
                                                 data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">{{ $classroom->info }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row mb-3">
                            <div class="col">
                                <div class="card">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                    aria-expanded="false" aria-controls="flush-collapseTwo">
                                                Post Announcement
                                            </button>
                                        </h2>
                                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                             aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <form
                                                    action="{{ Route('student_classroom.announce', $classroom->slug) }}"
                                                    method="POST">
                                                    <div class="form-group">
                                                        <input type="text" name="title" class="form-control"
                                                               placeholder="Announcement Title"
                                                               value="{{ old('title') }}">
                                                        <textarea class="form-control mt-2" name="text"
                                                                  placeholder="Write your announcement here"
                                                                  value="{{ old('text') }}" rows="3"
                                                                  onkeypress="if(event.keyCode==13) { this.form.submit(); }"></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            @if($announcements->count() > 0)
                                @foreach($announcements as $announcement)
                                    <div class="row">
                                        <div class="col mt-2">
                                            <div class="card">
                                                <div class="card-header">
                                                    @if ($announcement->announcement_author->photo == null)
                                                        <a href="{{ Route('student_profile') }}"
                                                           class="btn btn-light mr-2">{{ Auth::user()->name }}</a>
                                                    @else
                                                        @if($announcement->announcement_author()->author_role == 'instructor')
                                                            <img
                                                                src="{{ asset('ProfilePics/instructors/' . $announcement->announcement_author->photo) }}"
                                                                alt="avatar" class="img-fluid rounded-circle ml-1"
                                                                style="max-height: 35px; width: 30px">
                                                        @else
                                                            <img
                                                                src="{{ asset('ProfilePics/students/' . $announcement->announcement_author->photo) }}"
                                                                alt="avatar" class="img-fluid rounded-circle ml-1"
                                                                style="max-height: 35px; width: 30px">
                                                        @endif
                                                        <a href="{{ Route('student_get_user', ['id' => $announcement->announcement_author->id, 'slug' => $classroom->slug , 'role'=> $announcement->announcement_author()->author_role ])}}"
                                                           class="btn btn-light mr-2">{{ $announcement->announcement_author->name }}
                                                            &nbsp&nbsp<small><a class="btn btn-outline-primary btn-sm" role="button" style="cursor: default">{{ $announcement->announcement_author()->author_role }}</a>
                                                                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{{ $announcement->date_created }}</small>
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <h6>{{ $announcement->title }}</h6>
                                                    <hr>
                                                    <p>{{ $announcement->text }}</p>
                                                </div>
                                                <div class="card-header">
                                                    <a href="{{ Route('student_classroom.announcements.comments', ['slug' => $classroom->slug, 'id' => $announcement->id])}}"
                                                       class="btn btn-light mr-2"><i class="fas fa-comment"></i>&nbsp&nbsp{{ $announcement->getComments()->count() }}
                                                        &nbsp&nbspClass comments
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                        </div>
                        @else
                            <div class="row">
                                <div class="col mt-2 mb-3">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-center">
                                            <img src="{{ asset('img/no_content.png') }}" alt="No Announcements"
                                                 class="img-fluid" style="max-width: 50%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endauth
    </main>
@endsection

<style>
    @media only screen and (max-width: 767px) {
        .img-fluid {
            max-width: 100% !important;
        }
    }
</style>
