@extends('layouts.app')

@section('title', $exam->title)

@section('content')
    <main class="page">
        @auth('web')
            <html>
                <head>
                    <link rel="stylesheet" href="{{ asset('css/exam.css') }}">
                </head>
                <br></br>
                <body>
                    <div class="examcontainer">
                    <div class="examheader">
                        <h1 class="examh1">&nbsp {{ $exam->title }}</h1>
                        <!-- Timer -->
                        <div id="timer">
                            @php
                            $duration = $exam->duration *60;
                            $start_date = new DateTime($exam->start_date, new DateTimeZone('Africa/Cairo'));
                            $now = new DateTime('now', new DateTimeZone('Africa/Cairo'));
                            $now->modify('+1 hour');
                            $delay = $now->getTimestamp() - $start_date->getTimestamp(); // in seconds
                            $remaining_time = $duration - $delay;
                            @endphp
                        <input type="hidden" id="remaining-time" value="{{ $remaining_time }}">   
                        <span id="hours"></span>
                        <span>:</span>
                        <span id="minutes"></span>
                        <span>:</span>
                        <span id="seconds"></span>
                        </div>
                    </div>
                    <br></br>

                    <!-- important notes -->
                    <div class="important">
                        <h3 class="importanth3">Important:</h3>
                        <ul>
                            <li style="line-height: 1.8;padding-bottom: 3px;">Please note that if you leave or exit this page, your answers will not be saved. If you leave and come back, you will have to enter them again.</li>
                            <li>After submitting the exam you will not be able to edit your answers.</li>
                            <li>When the time is up your answers will be submitted automatically.</li>
                        </ul>
                    </div>
                    <!-- good luck animation -->
                    <br></br>
                    <p class="animated-text">Good luck!</p>

                    <form id="exam-form" method="POST" action="{{ route('student_classroom.submit-exam', ['slug' => $classroom->slug,'exam_id' => $exam->id]) }}">
                    @csrf
                        @if (count($mcqQuestionsWithOptions) > 0)
                        <!-- MCQ question -->
                        <div class="question">
                        <h2>MCQ</h2>
                        @foreach ( $mcqQuestionsWithOptions as $index => $mcqQuestionWithOptions )
                            <p>{{ ($index + 1) . '- ' . $mcqQuestionWithOptions['question']->text }}<span class="question-grade">({{ $mcqQuestionWithOptions['question']->grade }} points)</span></p>
                            @foreach ( $mcqQuestionWithOptions['options'] as $option )
                            <label><input type="radio" name="{{ $mcqQuestionWithOptions['question']->id }}" value="{{ $option->option }}" required>{{ $option->option }}</label><br>
                            @endforeach
                            <div class="small-space"></div>
                        @endforeach
                        </div>
                        <br></br>
                        @endif
                        @if (count($tfQuestions) > 0)
                        <!-- True/False question -->
                        <div class="question">
                        <h2>True or False</h2>
                        @foreach ( $tfQuestions as $index => $tfQuestion )
                            <p>{{ ($index + 1) . '- ' . $tfQuestion->question->text }}<span class="question-grade">({{ $tfQuestion->question->grade }} points)</span></p>
                            <label><input type="radio" name="{{ $tfQuestion->question->id }}" value="true" required>True</label><br>
                            <label><input type="radio" name="{{ $tfQuestion->question->id }}" value="false">False</label><br>
                            <div class="small-space"></div>
                        @endforeach
                        </div>
                        <br></br>
                        @endif
                        @if (count($fillQuestionsWithBlanks) > 0)
                        <!-- Fill the blank question -->
                        <div class="question">
                        <h2>Fill in the Blank</h2>
                        @foreach ( $fillQuestionsWithBlanks as $index => $fillQuestionWithBlanks )
                            <p>{{ ($index + 1) . '- ' . $fillQuestionWithBlanks['question']->text }}<span class="question-grade">({{ $fillQuestionWithBlanks['question']->grade }} points)</span></p>
                            @foreach ( $fillQuestionWithBlanks['blanks'] as $blank )
                            <input type="text" name="blank{{ $blank->id }}" placeholder="Write your answer for {{ $blank->blank_id }}" required>
                            @endforeach
                            <div class="small-space"></div>
                        @endforeach
                        </div>
                        <br></br>
                        @endif
                        @if(count($essayQuestions) > 0)
                        <!-- Essay question -->
                        <div class="question">
                        <h2>Essay</h2>
                        @foreach ( $essayQuestions as $index => $essayQuestion )
                            <p>{{ ($index + 1) . '- ' . $essayQuestion->question->text }}<span class="question-grade">({{ $essayQuestion->question->grade }} points)</span></p>
                            <textarea name="{{ $essayQuestion->question->id }}" rows="6" cols="50"  placeholder="Write your answer here" required></textarea>
                            <div class="small-space"></div>
                        @endforeach
                        </div>
                        <br></br>
                        @endif
                        <!-- Submit button -->
                        <div class="submit">
                        <button type="submit">Submit Exam</button>
                        </div>
                        <input type="hidden" name="tabSwitches" id="tabSwitchesInput" value="0">
                    </form>
                    </div>
                    <script src="{{ asset('js/exam.js') }}"></script>
                </body>
            </html>
        @endauth
    </main>
@endsection