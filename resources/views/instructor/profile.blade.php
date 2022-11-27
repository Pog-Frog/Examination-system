@extends('layouts.app')

@section('title', 'Student Profile')

@section('content')
    <main class="page">
        <section class="clean-block clean-catalog dark">
            <div class="container">
                <div class="content">
                    <div class="row_cus">
                        <div class="block-heading">
                            <h3 class="text-info"><dt>{{ Auth::guard('instructor')->user()->name}}</dt></h3>
                            <img src="{{ asset('ProfilePics/instructors/' . Auth::guard('instructor')->user()->photo) }}" alt="profile" class="img-thumbnail" style="width: 200px; height: 200px;">
                        </div>
                        <div class="row justify-content-md-center" >
                            <div class="col-md-6">
                                <div class="clean-product-item">
                                    <label class="form-label" for="email">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email" value= "{{ Auth::guard('instructor')->user()->email }}"disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-md-center" >
                            <div class="col-md-6">
                                <div class="clean-product-item">
                                    <label class="form-label" for="email">Phone</label>
                                        <input type="phone" class="form-control" id="phone" name="phone" value= "{{ Auth::guard('instructor')->user()->phone }}"disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-md-center" >
                            <div class="col-md-6">
                                <div class="clean-product-item">
                                    @if(Auth::guard('instructor')->user()->gender == "male")
                                        <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" checked disabled
                                        value="male">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            male
                                        </label>
                                        <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" disabled value="female">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            female
                                        </label>
                                    @else
                                        <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" 
                                            value="male">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            male
                                        </label>
                                        <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" checked disabled value="female">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            female
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-md-center" >
                            <div class="col-md-6">
                                <div class="clean-product-item">
                                    <label class="form-label" for="degree">Degree</label>
                                        <input type="degree" class="form-control" id="degree" name="degree" value= "{{ Auth::guard('instructor')->user()->degree }}"disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-md-center" >
                            <div class="col-md-6">
                                <div class="clean-product-item">
                                    <label class="form-label" for="institute">Institute</label>
                                        <input type="institute" class="form-control" id="institute" name="institute" value= "{{ Auth::guard('instructor')->user()->institute }}"disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-md-center" >
                            <div class="col-md-6">
                                <div class="clean-product-item">
                                    <a href="{{ Route('instructor_profile.edit') }}" class="btn btn-primary">Edit</a>
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
    .row_cus{
        min-height: 80vh;
    }
</style>

