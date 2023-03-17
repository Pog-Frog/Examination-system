@extends('layouts.app')
@section('title', 'Edit Question')

@section('content')
    <main class="page">
        <div class="container mb-4" style="margin-top: 4rem;">
            <section class="clean-form dark">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h4">Edit question</h1>
                </div>
                <div class="row">
                    <form method="POST" id="questionForm" action="{{ Route('instructor_questions.edit.post', ['question_slug' => $question->slug, 'slug' => $classroom->slug]) }}">
                        <div class="mb-3 col-8">
                            <label for="title" class="form-label">Title</label>
                            <input type="title" class="form-control" id="title" name="title" aria-describedby="titleHelp"
                                   value="{{ old('title') ?? $question->title}}">
                            <div id="titleHelp" class="form-text">Enter the title of the question.</div>
                        </div>
                        <div class="mb-3 col-10">
                            <label for="subject" class="form-label">Subject</label>
                            <div class="accordion" id="accordionExample" aria-describedby="subjectHelp">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Choose a subject
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @foreach($subjects as $subject)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="subject" id="{{ $subject }}" value="{{ $subject }}" onclick="disableNewSubject()"
                                                        {{ old('subject') == $subject || $question->subject == $subject ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $subject }}">
                                                        {{ $subject }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            or Create a new subject
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="mb-3">
                                                <label for="newSubject" class="form-label">New subject</label>
                                                <input type="text" class="form-control" id="newSubject" name="newSubject" aria-describedby="newSubjectHelp" value="{{ old('newSubject') }}" onclick="disableSubjects()">
                                                <div id="newSubjectHelp" class="form-text">Enter the name of the new subject.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div type="button" class="btn btn-secondary mt-2" id="clearSelection" onclick="enableAllsubjects()">Clear selection</div>
                            <div id="subjectHelp" class="form-text">Choose a subject for your question.</div>
                        </div>
                        <div class="mb-3 col-10">
                            <label for="category" class="form-label">Category</label>
                            <div class="accordion" id="accordionExample" aria-describedby="categoryHelp">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseOne">
                                            Choose a category
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @foreach($categories as $category)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="category" id="{{ $category }}" value="{{ $category }}" onclick="disableNewCategory()"
                                                        {{ old('category') == $category || $question->category == $category ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $category }}">
                                                        {{ $category }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
                                            or Create a new category
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="mb-3">
                                                <label for="newCategory" class="form-label">New category</label>
                                                <input type="text" class="form-control" id="newCategory" name="newCategory" aria-describedby="newCategoryHelp" value="{{ old('newCategory') }}" onclick="disableCategories()">
                                                <div id="newCategoryHelp" class="form-text">Enter the name of the new category.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div type="button" class="btn btn-secondary mt-2" id="clearSelection" onclick="enableAllCategories()">Clear selection</div>
                            <div id="subjectHelp" class="form-text">Choose a category for your question.</div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-2">
                                <label for="status" class="form-label">status</label>
                                <select class="form-select" id="status" name="status" aria-describedby="statusHelp">
                                    <option class="form-select" value="true" {{ old('status') == "true" || $question->status == "true" ? 'selected' : '' }}>public</option>
                                    <option class="form-select" value="false" {{ old('status') == "false" || $question->status == "false" ? 'selected' : '' }}>private</option>
                                </select>
                                <div id="statusHelp" class="form-text">Choose the status of your question.</div>
                            </div>

                            <div class="mb-3 col-2">
                                <label for="grade" class="form-label">grade</label>
                                <input type="number" class="form-control" id="grade" name="grade" aria-describedby="gradeHelp" value="{{ old('grade') ?? $question->grade }}">
                                <div id="gradeHelp" class="form-text">Enter the grade of your question.</div>
                            </div>
                        </div>
                        <input type="hidden" value="{{$question_type}}" name="question_type" id="question_type">
                        @if($question_type == 'MCQ')
                            <div class="mb-3 col-10">
                                <label for="text" class="form-label">Question</label>
                                <textarea class="form-control" id="text" name="text" rows="3" aria-describedby="textHelp" required>{{ old('text') ?? $question->text}}</textarea>
                                <div id="textHelp" class="form-text">Enter the question.</div>
                            </div>
                            <div id='options-container'>
                                @foreach($question_context as $option)
                                    <div class="mb-3 col-3" id="{{$option['option']}}">
                                        @if($option['is_correct'] == "true")
                                            <label for="answer" class="form-label">Answer</label>
                                            <input type="text" class="form-control" id="answer" name="answer" aria-describedby="answerHelp" value="{{ old('answer') ?? $option['option']}}" required>
                                            <div id="answerHelp" class="form-text">Enter the answer.</div>
                                        @else
                                                <label for="option" class="form-label">Option</label>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <input type="text" class="form-control"  name="mcq_options[]" aria-describedby="optionHelp" value="{{ old('option') ?? $option['option']}}" required>
                                                    </div>
                                                    <div class="col">
                                                        <button type="button" style="max-height: 2.5rem;" class="btn btn-danger" onclick="removeOption('{{$option['option']}}')">delete</button>
                                                    </div>
                                                </div>
                                                <div id="optionHelp" class="form-text">Enter the option.</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div type="button" class="btn btn-success mt-2" onclick="addnewMCQOption()"> <i class="fas fa-plus"></i> add new option</div>

                            <div class="mb-3 mt-3 col-3">
                                <button type="submit" class="btn btn-primary"><small>Update</small></button>
                            </div>
                        @elseif($question_type == 'True False')
                            <div class="mb-3 col-10">
                                <label for="text" class="form-label">Question</label>
                                <textarea class="form-control" id="text" name="text" rows="3" aria-describedby="textHelp" required>{{ old('text') ?? $question->text }}</textarea>
                                <div id="textHelp" class="form-text">Enter the question.</div>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="answer" class="form-label">Answer</label>
                                <select class="form-select" id="answer" name="answer" aria-describedby="answerHelp" required>
                                    <option class="form-select" value="true" {{ old('answer') == "true" || $question_context == "true" ? 'selected' : '' }}>true</option>
                                    <option class="form-select" value="false" {{ old('answer') == "false" || $question_context == "false" ? 'selected' : '' }}>false</option>
                                </select>
                                <div id="answerHelp" class="form-text">Choose the answer.</div>
                            </div>
                            <div class="mb-3 mt-3 col-3">
                                <button type="submit" class="btn btn-primary"><small>Update</small></button>
                            </div>
                        @elseif($question_type == 'Fill in the blanks')
                            <div class="mb-3 col-10">
                                <label for="text" class="form-label">Question</label>
                                <textarea class="form-control" id="text" name="text" rows="3" aria-describedby="textHelp" required disabled>{{ old('text') ?? $question->text}}</textarea>
                                <div id="textHelp" class="form-text">Enter the question.</div>
                            </div>
                            @foreach($question_context as $blank)
                                <div class="mb-3 col-3">
                                    <label for="answer" class="form-label">Answer for {{$blank['blank_id']}}: </label>
                                    <div class="row">
                                        <div class="col-5">
                                            <input type="text" class="form-control" name="answer[{{$blank['blank_id']}}]" aria-describedby="answerHelp" value="{{ old('answer') ?? $blank['blank_answer']}}" required>
                                        </div>
                                    </div>
                                    <div id="answerHelp" class="form-text">Enter the answer.</div>
                                </div>
                                <div class="mb-5 col-3 ">
                                    <label for="case_sensitivity" class="form-label">Case Sensitivity for {{$blank['blank_id']}}: </label>
                                    <select class="form-select" id="case_sensitivity" name="case_sensitivity[{{$blank['blank_id']}}]" aria-describedby="case_sensitivityHelp" required>
                                        <option class="form-select" value="true" {{ old('case_sensitivity') == "true" || $blank['is_case_sensitive'] == "true" ? 'selected' : '' }}>true</option>
                                        <option class="form-select" value="false" {{ old('case_sensitivity') == "false" || $blank['is_case_sensitive'] == "false" ? 'selected' : '' }}>false</option>
                                    </select>
                                </div>
                                <div class="mb-5 col-3 ">
                                    <label for="blank_grade" class="form-label">Grade for {{$blank['blank_id']}}: </label>
                                    <div class="row">
                                        <div class="col-5">
                                            <input type="number" class="form-control" name="blank_grade[{{$blank['blank_id']}}]" aria-describedby="blank_gradeHelp" value="{{ old('grade') ?? $blank['grade']}}" required>
                                        </div>
                                    </div>
                                    <div id="blank_gradeHelp" class="form-text">Enter the grade.</div>
                                </div>
                            @endforeach
                            <div class="mb-3 mt-3 col-3">
                                <button type="submit" class="btn btn-primary"><small>Update</small></button>
                            </div>
                        @elseif($question_type == 'Essay')
                            <div class="mb-3 col-10">
                                <label for="text" class="form-label">Question</label>
                                <textarea class="form-control" id="text" name="text" rows="3" aria-describedby="textHelp" required>{{ old('text') ?? $question->text}}</textarea>
                                <div id="textHelp" class="form-text">Enter the question.</div>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="answer" class="form-label">Answer</label>
                                <input type="text" class="form-control" id="answer" name="answer" aria-describedby="answerHelp" value="{{ old('answer') ?? $question_context['answer']}}" required>
                                <div id="answerHelp" class="form-text">Enter the answer.</div>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="grade" class="form-label">Grade</label>
                                <input type="number" class="form-control" id="grade" name="grade" aria-describedby="gradeHelp" value="{{ old('grade') ?? $question->grade }}" required>
                                <div id="gradeHelp" class="form-text">Enter the grade of your question.</div>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="is_case_sensitive" class="form-label">Case sensitivity</label>
                                <select class="form-select" id="is_case_sensitive" name="is_case_sensitive" aria-describedby="statusHelp" required>
                                    <option class="form-select" value="true" {{ old('is_case_sensitive') == "true" || $question_context['is_case_sensitive'] == "true" ? 'selected' : '' }}>case sensitive</option>
                                    <option class="form-select" value="false" {{ old('is_case_sensitive') == "false" || $question_context['is_case_sensitive'] == "false" ? 'selected' : '' }}>case insensitive</option>
                                </select>
                                <div id="statusHelp" class="form-text">Choose the case sensitivity.</div>
                            </div>
                            <div class="mb-3 mt-3 col-3">
                                <button type="submit" class="btn btn-primary"><small>Update</small></button>
                            </div>
                        @endif
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection

<script>

    function removeOption(id) {
        var element = document.getElementById(id);
        if(element != null) {
            element.parentNode.removeChild(element);
        }
        else{
            console.log("Element not found");
        }
    }

    function disableNewSubject() {
            document.getElementById("newSubject").disabled = true;
            document.getElementById("newSubject").value = "";
        }

        function enableNewSubject() {
            document.getElementById("newSubject").disabled = false;
        }

        function disableSubjects() {
            var radios = document.getElementsByName("subject");
            for (var i = 0; i < radios.length; i++) {
                radios[i].disabled = true;
                radios[i].checked = false;
            }
        }

        function enableSubjects() {
            var radios = document.getElementsByName("subject");
            for (var i = 0; i < radios.length; i++) {
                radios[i].disabled = false;
            }
        }

        function enableAllsubjects() {
            enableNewSubject();
            enableSubjects();
        }

        function disableNewCategory() {
            document.getElementById("newCategory").disabled = true;
            document.getElementById("newCategory").value = "";
        }

        function enableNewCategory() {
            document.getElementById("newCategory").disabled = false;
        }

        function disableCategories() {
            var radios = document.getElementsByName("category");
            for (var i = 0; i < radios.length; i++) {
                radios[i].disabled = true;
                radios[i].checked = false;
            }
        }

        function enableCategories() {
            var radios = document.getElementsByName("category");
            for (var i = 0; i < radios.length; i++) {
                radios[i].disabled = false;
            }
        }

        function enableAllCategories() {
            enableNewCategory();
            enableCategories();
        }

        function addnewMCQOption() {
            var optionsContainer = document.getElementById("options-container");
            var newOption = document.createElement("div");
            newOption.innerHTML = `
            <div class="mb-3 col-3">
                <label for="option" class="form-label">Option</label>
                <input type="text" class="form-control" id="option" name="mcq_options[]" aria-describedby="optionHelp" required>
                <div id="optionHelp" class="form-text">Enter the option.</div>
            </div>
        `;
            optionsContainer.appendChild(newOption);
        }

    </script>
