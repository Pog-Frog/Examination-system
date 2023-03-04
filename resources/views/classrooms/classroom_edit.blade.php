@extends('layouts.app')

@section('title', 'Edit classroom')

@section('content')
<main class="page">
    <div class="container mb-4" style="margin-top: 4rem;">
        <section class="clean-form dark">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h4">Edit classroom</h1>
            </div>
            <div class="mb-3 col-10">
                <form class="" method="POST" action="{{ Route('instructor_classrooms.regenerate_code', $classroom->slug) }}">
                    <label for="code" class="form-label">Classroom code</label>
                    <input type="text" class="form-control w-25" id="code" name="code" aria-describedby="codeHelp" value="{{ $classroom->code }}" readonly>
                    <div id="codeHelp" class="form-text mb-3">This is the code that students will use to join your classroom.</div>
                    <button type="submit" class="btn btn-success">Regenerate code</button>
                </form>
                <small class="text-muted"><button id="copy-button" name="copy-button" class="btn btn-sm btn-outline-secondary copy-button" data-clipboard-text="{{ $classroom->code }}">
                        Copy code
                    </button>
                </small>
            </div>
            <form method="POST" action="{{ Route('instructor_classrooms.edit.post', $classroom->slug) }}">
                <div class="mb-3 col-8">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" value="{{ $classroom->name }}">
                    <div id="nameHelp" class="form-text">Enter thet name of your classroom.</div>
                </div>
                <div class="mb-3 col-10">
                    <label for="info" class="form-label">Description</label>
                    <textarea class="form-control" id="info" name="info" rows="3" aria-describedby="infoHelp">{{ $classroom->info }}</textarea>
                    <div id="infoHelp" class="form-text">Enter a description for your classroom.</div>

                </div>

                <button type="submit" class="btn btn-success">Submit</button>
            </form>
            <form method="POST" action="{{ Route('instructor_classrooms.delete', $classroom->slug) }}">
                @csrf
                <button type="submit" class="btn btn-danger mt-3">Delete classroom</button>
            </form>
        </section>
    </div>
</main>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var copyButtons = document.querySelectorAll('.copy-button')
        copyButtons.forEach(function(copyButton) {
            copyButton.addEventListener('click', function() {
                var value = this.getAttribute('data-clipboard-text');
                navigator.clipboard.writeText(value)
                    .then(() => {
                        console.log(value);
                        alert("Code copied to clipboard");
                    })
                    .catch(err => {
                        console.error('Failed to copy text: ', err);
                    });
            });
        });
    });
</script>