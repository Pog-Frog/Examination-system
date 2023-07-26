@extends('layouts.app')
@section('title', 'Exam details')

<script src="{{asset('js/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">

@section('content')
<main class='page'>
    <section class="clean-block clean-catalog dark">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h4">Exam details</h1>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ Route('instructor_classrooms.exams.edit', ['slug' => $classroom->slug, 'exam_slug' => $exam->slug]) }}" method="POST">
                                <div class="row">
                                    <div class="mb-3 col-3" style="margin-right: 2rem;">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" aria-describedby="titleHelp"
                                               value="{{ old('title') ?? $exam->title }}">
                                        <div id="titleHelp" class="form-text">Enter the title of the exam.</div>
                                    </div>
                                    <div class="col-md-3 ">
                                        <label for="max_attempts" class="form-label">Max
                                            Attempts</label>
                                        <input type="number" class="form-control" id="max_attempts"
                                               name="max_attempts" aria-describedby="max_attemptsHelp"
                                               value="{{ old('max_attempts') ?? $exam->max_attempts }}" required>
                                        <div id="max_attemptsHelp" class="form-text">Enter the max
                                            attempts of the exam.
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" aria-describedby="descriptionHelp"required>{{ old('description') ?? $exam->description }}</textarea>
                                        <div id="descriptionHelp" class="form-text">Enter the description of the exam.</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-4" style="margin-right: 2rem;">
                                        <label for="startdate" class="form-label">Start Date</label>
                                        <input type="datetime-local" class="form-control" id="startdate"
                                               name="startdate" aria-describedby="startdateHelp"
                                               value="{{ old('startdate') ?? $exam->start_date}}" required>
                                        <div id="startdateHelp" class="form-text">Enter the start date of the
                                            exam.
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <label for="enddate" class="form-label">End Date</label>
                                        <input type="datetime-local" class="form-control" id="enddate"
                                               name="enddate" aria-describedby="enddateHelp"
                                               value="{{ old('enddate') ?? $exam->end_date}}" required>
                                        <div id="enddateHelp" class="form-text">Enter the end date of the
                                            exam.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="border-bottom">
                                        <p class="h5 mb-3">Exam options: </p>
                                    </div>

                                    @foreach($all_exam_options as $option)
                                        <div class="row mt-2">
                                            <div class="col">
                                                <label class="form-check-label"
                                                       for="{{$option->name}}">{{$option->name}}</label>
                                                <input class="checkbox" type="checkbox"
                                                       id="{{$option->name}}"
                                                       name="selected_options[]" value="{{$option->id}}"
                                                       @if($exam_options->contains($option->id) || old($option->name))
                                                           checked
                                                    @endif
                                                >
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-3">
                                    <div class="col-3">
                                        <button class="btn btn-success mt-2" type="submit">Update</button>
                                    </div>
                                </div>
                            </form>
                            <div
                                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-5 pb-2 mb-3 border-bottom">
                                <h3 class="h5">Exam questions: </h3>
                            </div>
                            <div class="mb-3">
                                <a href="{{ Route('instructor_classrooms.exams.questions.add', ['slug' => $classroom->slug, 'exam_slug' => $exam->slug]) }}" class="btn btn-success"><small><i class="fas fa-plus"></i>Add new question</small></a>
                            </div>
                            <form action="{{ Route('instructor_classrooms.exams.questions.delete', ['slug' => $classroom->slug , 'exam_slug' => $exam->slug]) }}" method="POST">
                                <table id='questions-table'>
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Subject</th>
                                            <th scope="col">Author</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Grade</th>
                                            <th scope="col">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($exam_questions as $question)
                                        <tr>
                                            <td><input type="checkbox" name="exam_questions_remove[]" value="{{ $question->id }}"></td>
                                            <td>{{ $question->title }}</td>
                                            <td>{{ $question->category }}</td>
                                            <td>{{ $question->subject }}</td>
                                            <td>{{ $question->instructor_name }}</td>
                                            <td>{{ $question->type_name }}</td>
                                            <td>{{ $question->grade }}</td>
                                            <td><a href="{{ Route('instructor_questions.edit', ['question_slug' => $question->slug, 'slug' => $classroom->slug]) }}" class="btn btn-primary"><small>View</small></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button class="btn btn-danger mt-2" type="submit"><small>Remove selected questions</small></button>
                            </form>
                            <div
                                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-5 pb-2 mb-3 border-bottom">
                                <h3 class="h5">Delete exam: </h3>
                            </div>
                            <form action="{{ Route('instructor_classrooms.exams.delete', ['slug' => $classroom->slug, 'exam_slug' => $exam->slug]) }}" method="POST">
                                @csrf
                                <button class="btn btn-danger mt-2" type="submit"><small>Delete exam</small></button>
                            
                              
                                                   
                            </form>

                           <a href = "{{ Route('instructor_classrooms.show', ['slug' => $classroom->slug]) }}"> <button class="btn btn-success mt-2" type="submit"><small>Done Exam</small></button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

<script>
    $(document).ready(function() {
        $('#questions-table').DataTable({
            language: {
                "emptyTable": "The exam doesn't have any questions yet."
            }
        });

    });
</script>
