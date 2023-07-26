@extends('layouts.app')

@section('title', 'Verify image')

@section('content')
    <main class="page">
        @auth('web')
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <link rel="stylesheet" href="{{ asset('css/verifyimage.css') }}">
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                </head>
                <body>
                    <div class="container webcam-container">
                        <h3>Verify your image to start exam</h3>
                        <video id="video"></video>
                        <button id="startbutton" data-url="{{ route('student_classroom.take-exam', ['slug' => $classroom->slug,'exam_id' => $exam->id])}}">Capture image</button>
                        <canvas id="canvas"></canvas>
                    </div>
                    <script src="{{ asset('js/verifyimage.js') }}"></script>
                </body>
            </html>
        @endauth
    </main>
@endsection