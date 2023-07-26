@extends('layouts.app')

@section('title', 'Welcome to Dashboard')

@section('content')
<main class="page">
    <div class="container mb-4" style="margin-top: 4rem;">
        <section class="clean-form dark">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h4">Create a classroom</h1>
            </div>
            <form method="POST" action="{{ Route('instructor_classrooms.create.post') }}">
                <div class="mb-3 col-8">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" value="{{ old('name') }}">
                    <div id="nameHelp" class="form-text">Enter thet name of your classroom.</div>
                </div>
                <div class="mb-3 col-10">
                    <label for="info" class="form-label">Description</label>
                    <textarea class="form-control" id="info" name="info" rows="3" aria-describedby="infoHelp" value="{{ old('info') }}"></textarea>
                    <div id="infoHelp" class="form-text">Enter a description for your classroom.</div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </section>
    </div>
</main>
@endsection