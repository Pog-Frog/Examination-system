@extends('layouts.app')

@section('title', $exam->title)

@section('content')
    <main class="page">
        @auth('web')
            <html>
                <head>
                    <link rel="stylesheet" href="{{ asset('css/submission.css') }}">
                </head>
                <br></br>
                <body>
                    <div class="examcontainer">
                    <div class="examheader">
                        <h1 class="examh1">&nbsp {{ $exam->title }}</h1>
                    </div>
                        @if (count($mcqQuestionsWithOptions) > 0)
                        <!-- MCQ question -->
                        <div class="question">
                        <h2>MCQ</h2>
                        @foreach ( $mcqQuestionsWithOptions as $index => $mcqQuestionWithOptions )
                            <p>{{ ($index + 1) . '- ' . $mcqQuestionWithOptions['question']->text }}@if($mcqQuestionWithOptions['student_answer']=='?')<span class="notAnswered">(Not Answered)</span>@endif<span class="question-grade">({{ $mcqQuestionWithOptions['grade'] }} / {{ $mcqQuestionWithOptions['question']->grade }} points)</span></p>
                            @foreach ( $mcqQuestionWithOptions['options'] as $option )
                                @php
                                    // Check if the current option is the correct option
                                    $isCorrectOption = $option->option == $mcqQuestionWithOptions['correct_option'];
                                    // Check if the current option is the student's answer
                                    $isStudentAnswer = $option->option == $mcqQuestionWithOptions['student_answer'];
                                    // Set the background color based on the correctness of the answer
                                    $bgColor = $isCorrectOption ? 'green' : ($isStudentAnswer ? 'red' : '');
                                    // Set the marker based on the correctness of the answer
                                    $marker = '';
                                    if ($isCorrectOption) {
                                        $marker = '<span class="mcq-marker">&#10004;</span>';
                                    } else {
                                        $marker = $isStudentAnswer ? '<span class="mcq-marker">&#10008;</span>' : '';
                                    }
                                    // Set the text based on the correctness of the answer
                                    $text = $isCorrectOption ? 'Correct' : ($isStudentAnswer ? 'Incorrect' : '');
                                @endphp
                                <label class="@if ($isStudentAnswer) checked @endif">
                                    <input type="radio" name="{{ $mcqQuestionWithOptions['question']->id }}" value="{{ $option->option }}" required @if ($isStudentAnswer) checked @endif disabled>{{ $option->option }}
                                    <span class="mcq-answer {{ $bgColor }}"><span class="mcq-marker">{!! $marker !!}</span> {{ $text }}</span>
                                </label>
                                <br>
                            @endforeach
                            <div class="small-space"></div>
                        @endforeach
                        </div>
                        <br></br>
                        @endif
                        @if (count($tfQuestionsWithOptions) > 0)
                        <!-- True/False question -->
                        <div class="question">
                        <h2>True or False</h2>
                        @foreach ( $tfQuestionsWithOptions as $index => $tfQuestionWithOptions )
                            <p>{{ ($index + 1) . '- ' . $tfQuestionWithOptions['question']->text }}@if($tfQuestionWithOptions['student_answer']=='?')<span class="notAnswered">(Not Answered)</span>@endif<span class="question-grade">({{ $tfQuestionWithOptions['grade'] }} / {{ $tfQuestionWithOptions['question']->grade }} points)</span></p>
                            @foreach ( $tfQuestionWithOptions['options'] as $option )
                                @php
                                    // Check if the current option is the correct option
                                    $isCorrectOption = $option['option'] == $tfQuestionWithOptions['correct_option'];
                                    // Check if the current option is the student's answer
                                    $isStudentAnswer = $option['option'] == $tfQuestionWithOptions['student_answer'];
                                    // Set the background color based on the correctness of the answer
                                    $bgColor = $isCorrectOption ? 'green' : ($isStudentAnswer ? 'red' : '');
                                    // Set the marker based on the correctness of the answer
                                    $marker = '';
                                    if ($isCorrectOption) {
                                        $marker = '<span class="mcq-marker">&#10004;</span>';
                                    } else {
                                        $marker = $isStudentAnswer ? '<span class="mcq-marker">&#10008;</span>' : '';
                                    }
                                    // Set the text based on the correctness of the answer
                                    $text = $isCorrectOption ? 'Correct' : ($isStudentAnswer ? 'Incorrect' : '');
                                @endphp
                                <label class="@if ($isStudentAnswer) checked @endif">
                                    <input type="radio" name="{{ $tfQuestionWithOptions['question']->id }}" value="{{ $option['option'] }}" required @if ($isStudentAnswer) checked @endif disabled>{{ $option['option'] }}
                                    <span class="mcq-answer {{ $bgColor }}"><span class="mcq-marker">{!! $marker !!}</span> {{ $text }}</span>
                                </label>
                                <br>
                            @endforeach
                            <div class="small-space"></div>
                        @endforeach
                        </div>
                        <br></br>
                        @endif
                        @if (count($fillQuestionsWithBlanks) > 0)
                            <!-- Fill the blank question -->
                            <div class="question">
                                <h2>Fill in the Blank</h2>
                                @foreach ($fillQuestionsWithBlanks as $index => $fillQuestionWithBlanks)
                                    <p>{{ ($index + 1) . '- ' . $fillQuestionWithBlanks['question']->text }}<span class="question-grade">({{ $fillQuestionWithBlanks['grade'] }} / {{ $fillQuestionWithBlanks['question']->grade }} points)</span></p>
                                    @foreach ($fillQuestionWithBlanks['blanks'] as $blank)
                                        @php
                                        foreach ($fillQuestionWithBlanks['student_answers'] as $answer) {
                                            if ($answer['blank_id'] === $blank->id) {
                                                $studentAnswer = $answer['answer'];
                                                $isCorrect = $answer['is_correct'];
                                                break;
                                            }
                                        }
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-0">
                                                <p>{{ $blank->blank_id }}:@if($studentAnswer=='?')@php $studentAnswer = ''; @endphp<span class="notAnswered">(Not Answered)</span>@endif</p>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="blank{{ $blank->id }}" value="{{ $studentAnswer }}" required disabled style="margin-top: -10px;">
                                                @if ($isCorrect)
                                                <span class="mcq-answer green" style="transform: translate(280px, -46px);"><span class="mcq-marker">{!! $marker !!}</span> Correct</span>
                                                @elseif($studentAnswer == '')
                                                <p>Correct answer : <span style="color:green;">{{ $blank->blank_answer }}</span></p>
                                                @else
                                                <span class="mcq-answer red" style="transform: translate(280px, -46px);"><span class="mcq-marker">{!! $marker !!}</span> Incorrect</span>
                                                <p>Correct answer : <span style="color:green;">{{ $blank->blank_answer }}</span></p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="small-space"></div>
                                @endforeach
                            </div>
                            <br>
                        @endif
                        @if(count($essayQuestionsWithAnswers) > 0)
                        <!-- Essay questions -->
                        <div class="question">
                            <h2>Essay</h2>
                            @foreach ($essayQuestionsWithAnswers as $index => $essayQuestion)
                                <p>{{ ($index + 1) . '- ' . $essayQuestion['question']->text }}@if($essayQuestion['student_answer']=='?')<span class="notAnswered">(Not Answered)</span>@endif<span class="question-grade">({{ $essayQuestion['grade'] }} / {{ $essayQuestion['question']->grade }} points)</span></p>
                                <p class="notAnswered" style="float:right;">AI-Generated Grade (pending instructor review)</p>
                                <textarea name="{{ $essayQuestion['question']->id }}" rows="6" cols="50" disabled>{{ $essayQuestion['student_answer'] }}</textarea>
                                <div class="small-space"></div>
                            @endforeach
                        </div>
                        <br></br>
                        @endif
                                        
                    <script src="{{ asset('js/submission.js') }}"></script>
                </body>
            </html>
        @endauth
    </main>
@endsection