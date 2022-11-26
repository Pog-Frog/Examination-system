<nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white clean-navbar">
    <div class="container"><a class="navbar-brand logo" href="{{ Route('index') }}">Quizzix</a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="navbar-nav ms-auto">
                @auth('web')
                    <li class="nav-item"><a class="nav-link" href="{{ Route('student_dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">My Classrooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Exams</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">My Results</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ Route('student_logout') }}">Logout</a></li>
                @endauth
                @auth('instructor')
                    <li class="nav-item"><a class="nav-link" href="{{ Route('instructor_dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">My Classrooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Question Bank</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ Route('instructor_logout') }}">Logout</a></li>
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