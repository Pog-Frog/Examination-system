@extends('layouts.app')

@section('title', 'Quizzix')

<link rel="stylesheet" href="{{asset('css/style.css')}}">
<link rel="stylesheet" href="{{asset('css/aos.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap-icons.css')}}">
<link rel="stylesheet" href="{{asset('css/boxicons.min.css')}}">
<link rel="stylesheet" href="{{asset('css/glightbox.min.css')}}">
<link rel="stylesheet" href="{{asset('css/remixicon.css')}}">
<link rel="stylesheet" href="{{asset('css/swiper-bundle.min.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">


<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

@section('content')
<main>

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">

      <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
        <div class="col-xl-6 col-lg-8">
          <h1>Powerful Online Examination System With Less Cheating<span>.</span></h1>
          <h2>We Introduce To You An Environment Full Of Trust</h2>
        </div>
      </div>

      <div class="row gy-4 mt-5 justify-content-center" data-aos="zoom-in" data-aos-delay="250">
        <div class="col-xl-2 col-md-4">
          <div class="icon-box">
            <i class="bi bi-person-circle"></i>
            <h3><a href="{{ Route('iamteacher')}}">Are You A Teacher?</a></h3>
          </div>
        </div>
        <div class="col-xl-2 col-md-4">
          <div class="icon-box">
            <i class="bi bi-mortarboard-fill"></i>
            <h3><a href="{{ Route('iamstudent')}}">Are You A Student?</a></h3>
          </div>
        </div>

      </div>

    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
            <img src="{{asset('img/face.png')}}" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content" data-aos="fade-right" data-aos-delay="100">
            <h3>About The System.</h3>
            <p >
              Providing a trusted atmosphere for examination whether you are a teacher or a student.
            </p>

            <p>
              We believe that the future is digital, hence introducing this smart system helping you to safely take your
              exams in an environment as comfortable as your own room.
            </p>
              <p>
              Our dear teachers, the effort you do during classes is already much so, please, let Quizzix do the rest for you.
            </p>
            <div class="col col-md-3">
            <a href="" class="button-88">Read More </a>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->



    <!-- ======= Features Section ======= -->
    <section id="features" class="features">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="image col-lg-6" style='background-image: url("../img/think.jpg");' data-aos="fade-right"></div>
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
            <div class="icon-box mt-5 mt-lg-0" data-aos="zoom-in" data-aos-delay="150">
              <i class="bi bi-alarm"></i>
              <h4>It Is Exam Time</h4>
              <p>No need to worry about forgetting your exams dates. Quizzix will notify you.</p>
            </div>
            <div class="icon-box mt-5" data-aos="zoom-in" data-aos-delay="150">
              <i class="bi bi-send-check"></i>
              <h4>What are my colleagues up to ? </h4>
              <p>Communicate With Your colleagues, share your thoughts in the timeline and recieve their opinions about it</p>
            </div>
            <div class="icon-box mt-5" data-aos="zoom-in" data-aos-delay="150">
              <i class="bi bi-cloud-lightning"></i>
              <h4>What is in my teacher`s mind?</h4>
              <p>Stay in touch with your class teacher with Quizzix for any updates or announcements</p>
            </div>
            <div class="icon-box mt-5" data-aos="zoom-in" data-aos-delay="150">
              <i class="bi bi-card-checklist"></i>
              <h4>View Your exams results and review it with correct answers</h4>

            </div>
          </div>
        </div>

      </div>
    </section><!-- End Features Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Features</h2>
          <p>Check our Features</p>
        </div>

        <div class="row">
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-person-bounding-box"></i></div>
              <h4><a href="">Face Recognition</a></h4>
              <p>Only enrolled students may take exams</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-pencil-square"></i></div>
              <h4><a href="">plagiarism checking</a></h4>
              <p>Make sure it is the student`s own answer</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bi bi-shield-check"></i></div>
              <h4><a href="">Auto Grading</a></h4>
              <p>Save time
                and allow Quizzix to grade exams</p>

            </div>
          </div>

        </div>

      </div>
    </section><!-- End Services Section -->

    <!-- ======= Cta Section ======= -->
    <section id="cta" class="cta">
      <div class="container" data-aos="zoom-in">

        <div class="text-center">
          <h3>Need More Information</h3>
          <p> We Are Here To Answer All Your Questions.</p>
          <a class="cta-btn" href="{{ Route('contactUs') }}">Contact Us</a>
        </div>

      </div>
    </section><!-- End Cta Section -->



</main>
@endsection

<!-- Vendor JS Files -->
  <script src="{{URL::asset('js/purecounter_vanilla.js')}}"></script>
  <script src="{{URL::asset('js/aos.js')}}"></script>
  <script src="{{URL::asset('js/bundle.min.js')}}"></script>
  <script src="{{URL::asset('js/glightbox.min.js')}}"></script>
  <script src="{{URL::asset('js/isotope.pkgd.min.js')}}"></script>
  <script src="{{URL::asset('js/swiper-bundle.min.js')}}"></script>
  <script src="{{URL::asset('js/validate.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{URL::asset('js/main.js')}}"></script>

