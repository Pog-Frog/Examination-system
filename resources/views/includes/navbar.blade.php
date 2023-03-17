<nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white clean-navbar">
    <div class="container"><a class="navbar-brand logo" href="{{ Route('index') }}">{{ config('app.name') }}</a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="navbar-nav ms-auto">
                @auth('web')
                    <li class="nav-item"><a class="nav-link" href="{{ Route('student_dashboard') }}">My classrooms</a></li>
                    @if(Request::is('student/classrooms/*') && !Request::is('student/classrooms/join') && !Request::is('student/classrooms/leave'))
                    <li class="nav-item"><a class="nav-link" href="{{ Route('student_classroom.show', $classroom->slug) }}">{{ $classroom->name }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ Route('student_classrooms.students', $classroom->slug) }}">People</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Exams</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">My Results</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ Route('student_logout') }}">Logout</a></li>
                    @if (Auth::user()->photo == null)
                        <a href="{{ Route('student_profile') }}" class="btn btn-light mr-2">{{ Auth::user()->getFirstname() }}</a>
                    @else
                        <li class="nav-item">
                            <a href="{{ Route('student_profile') }}" class="btn btn-light mr-2">{{ Auth::user()->getFirstname() }}
                                <img src="{{ asset('ProfilePics/students/' . Auth::user()->photo) }}" alt="avatar"
                                    class="img-fluid rounded-circle ml-1" style="max-height: 25px; width: 20px">
                            </a>
                        </li>
                    @endif
                @endauth
                @auth('instructor')
                    <li class="nav-item"><a class="nav-link" href="{{ Route('instructor_dashboard') }}">My classrooms</a></li>

                    @if(Request::is('instructor/classrooms/*') && !Request::is('instructor/classrooms/create') && !Request::is('instructor/classrooms/*/edit'))
                    <li class="nav-item"><a class="nav-link" href="{{ Route('instructor_questions', $classroom->slug) }}">Question Bank</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ Route('instructor_classrooms.show', $classroom->slug) }}">{{ $classroom->name }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ Route('instructor_classrooms.students', $classroom->slug) }}">People</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ Route('instructor_classrooms.exams', $classroom->slug) }}">Exams</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ Route('instructor_logout') }}">Logout</a></li>
                    @if (Auth::guard('instructor')->user()->photo == null)
                        <a href="{{ Route('instructor_profile') }}" class="btn btn-light mr-2">{{ Auth::guard('instructor')->user()->getFirstname() }}</a>
                    @else
                        <li class="nav-item">
                            <a href="{{ Route('instructor_profile') }}" class="btn btn-light mr-2">{{ Auth::guard('instructor')->user()->getFirstname() }}
                                <img src="{{ asset('ProfilePics/instructors/' . Auth::guard('instructor')->user()->photo) }}" alt="avatar"
                                    class="img-fluid rounded-circle ml-1" style="max-height: 25px; width: 20px">
                            </a>
                        </li>
                    @endif
                @endauth
                @guest
                    @guest('admin')
                        @guest('instructor')
                        <li class="nav-item"><a class="nav-link" href="{{ Route('index') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ Route('index') }}#features">Features</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ Route('contactUs') }}">Contact Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ Route('index') }}#aboutus">About Us</a></li>
                        <li class="nav-item">
                            <div class="nav-item dropdown"><a class="dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" href="#">LOGIN/REGISTER</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ Route('student_login') }}">STUDENT</a>
                                    <a class="dropdown-item" href="{{ Route('instructor_login') }}">INSTRUCTOR</a>
                                </div>
                            </div>
                        </li>
                        @endguest
                    @endguest
                @endguest
            </ul>
        </div>
    </div>
</nav>
