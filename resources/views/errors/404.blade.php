@extends('layouts.app')

@section('title', 'Not Found')

<link rel="stylesheet" href="{{asset('css/instructor_dashboard.css')}}">

@section('content')
<main class="page">
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
                                Hmm... We can't seem to find the page you're looking for.
                            </h3>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection