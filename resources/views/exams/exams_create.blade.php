@extends('layouts.app')
@section('title', 'Create exam')

@section('content')
    <main class="page">
        <section class="clean-block clean-catalog dark">
            <div class="container">
                <div class="content">
                    <div class="row_cus">
                        <div class="text-center" style="padding-top: 2rem;">
                            <div class="block-heading">
                                <h3 class="text-info">
                                    <dt>Exam options</dt>
                                </h3>
                            </div>
                        </div>
                        <form method="POST"
                              action="{{ Route('instructor_classrooms.exams.create.post', $classroom->slug)}}">
                            @if(old('options_done'))
                                <input type="hidden" name="options_done" value="true">
                                @if(old('exam_options_done') != null)
                                    @foreach(old('exam_options_done') as $option)
                                        <input type="hidden" name="exam_options_done[]" value="{{$option}}">
                                    @endforeach
                                @endif
                                <div class="text-center">
                                    <div class="row justify-content-md-center mb-4">
                                        <div class="col-md-6 text-center">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                   aria-describedby="titleHelp" value="{{ old('title') }}" required>
                                            <div id="titleHelp" class="form-text">Enter the title of the exam.
                                            </div>
                                        </div>

                                        <div class="row justify-content-md-center mb-4">
                                            <div class="col-md-6 mt-4">
                                                <label for="startdate" class="form-label">Start Date</label>
                                                <input type="datetime-local" class="form-control" id="startdate"
                                                       name="startdate" aria-describedby="startdateHelp"
                                                       value="{{ old('startdate') }}" required>
                                                <div id="startdateHelp" class="form-text">Enter the start date of the
                                                    exam.
                                                </div>
                                            </div>

                                            <div class="row justify-content-md-center mb-4">
                                                <div class="col-md-6 mt-4">
                                                    <label for="enddate" class="form-label">End Date</label>
                                                    <input type="datetime-local" class="form-control" id="enddate"
                                                           name="enddate" aria-describedby="enddateHelp"
                                                           value="{{ old('enddate') }}" required>
                                                    <div id="enddateHelp" class="form-text">Enter the end date of the
                                                        exam.
                                                    </div>
                                                </div>

                                                <div class="row justify-content-md-center mb-4">
                                                    <div class="col-md-6 mt-4">
                                                        <label for="description" class="form-label">Description</label>
                                                        <textarea class="form-control" id="description"
                                                                  name="description" aria-describedby="descriptionHelp"
                                                                  required>{{ old('description') }}</textarea>
                                                        <div id="descriptionHelp" class="form-text">Enter the
                                                            description of the exam.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row justify-content-md-center">
                                                    <div class="col-md-6">
                                                        <label for="max_attempts" class="form-label">Max
                                                            Attempts</label>
                                                        <input type="number" class="form-control" id="max_attempts"
                                                               name="max_attempts" aria-describedby="max_attemptsHelp"
                                                               value="{{ old('max_attempts') }}" required>
                                                        <div id="max_attemptsHelp" class="form-text">Enter the max
                                                            attempts of the exam.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-5 mb-5">
                                                    <button type="submit" class="btn btn-primary">Next: choose
                                                        questions
                                                    </button>
                                                </div>

                                            </div>
                                            @else
                                                <div class="text-center">
                                                    @foreach($exam_options as $option)
                                                        <div class="row justify-content-md-center">
                                                            <div class="col-md-6">
                                                                <div class="clean-product-item">
                                                                    <div class="form-check form-switch">
                                                                        <label class="form-check-label"
                                                                               for="{{$option->name}}">{{$option->name}}</label>
                                                                        <input class="form-check-input" type="checkbox"
                                                                               id="{{$option->name}}"
                                                                               name="{{$option->name}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="mt-5">
                                                        <button type="submit" class="btn btn-primary">Next</button>
                                                    </div>
                                                    @endif
                                                </div>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

<style>
    .row_cus {
        min-height: 80vh;
    }

    input {
        text-align: center;
    }

    select {
        text-align: center;
    }

</style>
