@extends('layouts.app')

@section('title', 'Student Register')

@section('content')
    <main class="page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info">Student Signup</h2>
                </div>
                <form method="POST" action="{{ Route('student_register.post') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" placeholder="EX: John Smith" id="name" name="name">
                        <span class="text-danger">@error('name') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email address</label>
                        <input type="email" class="form-control" placeholder="name@example.com" id="email" name="email">
                        <span class="text-danger">@error('email') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        <span class="text-danger">@error('password') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="phone">Phone</label>
                        <input type="tel" class="form-control" placeholder="EX: +20123456789" id="phone" name="phone">
                        <span class="text-danger">@error('phone') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3">
                        <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" checked
                                value="male">
                        <label class="form-check-label" for="flexRadioDefault1">
                            male
                        </label>
                        <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" value="female">
                        <label class="form-check-label" for="flexRadioDefault2">
                            female
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="degree">Degree</label>
                        <input type="text" class="form-control" placeholder="EX: bachlors, highschool student" id="degree" name="degree">
                        <span class="text-danger">@error('degree') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="institute">Institute</label>
                        <input type="text" class="form-control" placeholder="Ex: MIT, GUC" id="institute" name="institute">
                        <span class="text-danger">@error('institute') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Choose picture</label>
                        <input class="form-control" type="file" id="image" name="image">
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign up</button>
                </form>
            </div>
        </section>
    </main>
@endsection
