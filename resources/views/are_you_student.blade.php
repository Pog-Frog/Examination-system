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


    <!-- ======= Features Section ======= -->
    <section id="features" class="features" >
      <div class="container" data-aos="fade-up" style="margin-top: 100px;">

         <div class="row">
          <div class="image col-lg-6" style='background-image: url("../img/student.jpg");' data-aos="fade-right"></div>
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

            <div class="col col-md-3">
            <a href="{{ Route('index')}}" class="button-88">Go Back </a>
            </div>
          </div>
        </div>
    </section><!-- End Features Section -->

    




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

