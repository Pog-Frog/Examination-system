@extends('layouts.app')

@section('title', 'My Results')

@section('content')
    <main class="page">
        @auth('web')
            <html>
                <head>
                    <link rel="stylesheet" href="{{ asset('css/results.css') }}">
                </head>
                <br></br>
                <section class="clean-block clean-catalog dark">
                    <div class="results-container">
                        @if(count($exams) > 0)
                        <h1>My Results</h1>
                        <div class="card">
                            <table>
                                <tr>
                                    <th>Exam Title</th>
                                    <th>Submitted At</th>
                                    <th>Grade</th>
                                    <th>Review</th>
                                </tr>
                                @foreach($exams as $exam)
                                <tr>
                                    <td>{{ $exam->title }}</td>
                                    <td>
                                    @foreach($examResults as $examResult)
                                    @if($examResult->exam_id == $exam->id)
                                    {{ $examResult->created_at->addHours(3)->format('F j, Y, g:i a') }}
                                    @endif
                                    @endforeach
                                    </td>
                                    <td class="grade">
                                    @foreach($examResults as $examResult)
                                    @if($examResult->exam_id == $exam->id)
                                    {{ $examResult->marks }} / {{ $exam->total_mark }}
                                    @endif
                                    @endforeach
                                    </td>
                                    <td><a href="{{ route('student_classroom.viewSubmission', ['slug' => $classroom->slug,'exam_id' => $exam->id]) }}"><button>View Submission</button></a></td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        @else
                        <h1>You don't have any results yet.</h1>
                        @endif
                    </div>
                </section>
            </html>
        @endauth
    </main>
@endsection