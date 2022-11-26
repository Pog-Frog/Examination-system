@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <main class="page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info">Enter your email</h2>
                </div>
                <form method="POST" action="{{ Route('student_forgot_password.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">Email address</label>
                        <input type="email" class="form-control" placeholder="name@example.com" id="email" name="email">
                        <span class="text-danger">@error('email') {{$message}} @enderror</span>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Reset Password</button>
                </form>
            </div>
        </section>
    </main>
@endsection
