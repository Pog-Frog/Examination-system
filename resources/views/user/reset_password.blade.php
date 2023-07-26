@extends('layouts.app')

@section('title', 'Password reset')

@section('content')
    <main class="page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info">Password Reset</h2>
                </div>
                <form method="POST" action="{{ Route('student_password.reset.post', ['email'=>$email , 'token' =>$token]) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" placeholder="Enter your new password" id="password" name="password">
                        <span class="text-danger">@error('password') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm your password" id="password_confirmation" name="password_confirmation">
                        <span class="text-danger">@error('password_confirmation') {{$message}} @enderror</span>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Reset Password</button>
                </form>
            </div>
        </section>
    </main>
@endsection
