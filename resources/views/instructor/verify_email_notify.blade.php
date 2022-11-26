@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
    <main class="page">
        <section class="clean-block clean-form dark">
            <div class="container">
                <div class="block-heading">
                    <h2 class="text-info">Check your email for verification link</h2>
                </div>
                <br/>
                <form method="POST" action="{{ Route('instructor_resend_verification_email', ['email'=>$email]) }}">
                    @csrf
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Resend the verficaiton link</button>
                </form>
            </div>
        </section>
    </main>
@endsection
