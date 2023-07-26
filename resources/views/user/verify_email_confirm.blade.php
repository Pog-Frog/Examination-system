@extends('layouts.app')

@section('title', 'Email Verification')

@section('content')
    <main class="page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info">Verify your email</h2>
                </div>
                <br/>
                <form method="POST" action="{{ Route('student_verify_email.post', ['email'=>$email, 'token' => $token]) }}">
                    @csrf
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Verify</button>
                </form>
            </div>
        </section>
    </main>
@endsection
