@extends('layouts.app')
@section('title', 'Create a Question')

@section('content')
    <main class="page">
        <div class="container mb-4" style="margin-top: 4rem;">
            <section class="clean-form dark">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h4">Create a question</h1>
                </div>
                <div class="row">
                    <form method="POST" id="questionForm" action="{{ Route('instructor_questions.create.post', ['question_type' => $question_type, 'slug' => $classroom->slug]) }}">
                        <div class="mb-3 col-8">
                            <label for="title" class="form-label">Title</label>
                            <input type="title" class="form-control" id="title" name="title" aria-describedby="titleHelp"
                                   value="{{ old('title') }}">
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
                                                        {{ old('subject') == $subject ? 'checked' : '' }}>
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
                                                        {{ old('category') == $category ? 'checked' : '' }}>
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
                                    <option class="form-select" value="true" {{ old('status') == "true" ? 'selected' : '' }}>public</option>
                                    <option class="form-select" value="false" {{ old('status') == "false" ? 'selected' : '' }}>private</option>
                                </select>
                                <div id="statusHelp" class="form-text">Choose the status of your question.</div>
                            </div>

                            <div class="mb-3 col-2">
                                <label for="grade" class="form-label">grade</label>
                                <input type="number" class="form-control" id="grade" name="grade" aria-describedby="gradeHelp" value="{{ old('grade') }}">
                                <div id="gradeHelp" class="form-text">Enter the grade of your question.</div>
                            </div>
                        </div>
                        @if($question_type->type_name == 'MCQ')
                            <div class="mb-3 col-10">
                                <label for="text" class="form-label">Question</label>
                                <textarea class="form-control" id="text" name="text" rows="3" aria-describedby="textHelp" required>{{ old('text') }}</textarea>
                                <div id="textHelp" class="form-text">Enter the question.</div>
                            </div>
                            <div id='options-contianer'>
                                <div class="mb-3 col-3">
                                    <label for="answer" class="form-label">Answer</label>
                                    <input type="text" class="form-control" id="answer" name="answer" aria-describedby="answerHelp" value="{{ old('answer') }}" required>
                                    <div id="answerHelp" class="form-text">Enter the answer.</div>
                                </div>
                            </div>
                            <div type="button" class="btn btn-success mt-2" onclick="addnewMCQOption()"> <i class="fas fa-plus"></i> add new option</div>

                            <div class="mb-3 mt-3 col-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        @elseif($question_type->type_name == 'True False')
                            <div class="mb-3 col-10">
                                <label for="text" class="form-label">Question</label>
                                <textarea class="form-control" id="text" name="text" rows="3" aria-describedby="textHelp" required>{{ old('text') }}</textarea>
                                <div id="textHelp" class="form-text">Enter the question.</div>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="answer" class="form-label">Answer</label>
                                <select class="form-select" id="answer" name="answer" aria-describedby="answerHelp" required>
                                    <option class="form-select" value="true" {{ old('answer') == "true" ? 'selected' : '' }}>true</option>
                                    <option class="form-select" value="false" {{ old('answer') == "false" ? 'selected' : '' }}>false</option>
                                </select>
                                <div id="answerHelp" class="form-text">Choose the answer.</div>
                            </div>
                            <div class="mb-3 mt-3 col-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        @elseif($question_type->type_name == 'Fill in the blanks')
                            <div class="mb-3 col-10">
                                <label for="text" class="form-label">Question</label>
                                <textarea class="form-control" id="text" name="text" rows="3" aria-describedby="textHelp" required>{{ old('text') }}</textarea>
                                <div id="textHelp" class="form-text">Enter the question.</div>
                            </div>
                           @if(old('modified_text'))
                                <div class="mb-3 col-10">
                                    <label for="modified_text" class="form-label">Question text: </label>
                                    <textarea class="form-control" id="modified_text" name="modified_text" rows="3" aria-describedby="modified_textHelp" required>{{ old('modified_text') }}</textarea>
                                    <div id="modified_textHelp" class="form-text">Enter the question.</div>
                                </div>
                                @foreach(old('blanks') as $blank)
                                    <div class="mb-3 col-3">
                                        <label for="blank{{$blank}}" class="form-label">Answer for {{$blank}}</label>
                                        <input type="text" class="form-control" id="blank{{$blank}}" name="blank{{$blank}}" aria-describedby="Helpblank{{$blank}}" value="{{ old('blank'.$blank) }}" required>
                                        <div id="Helpblank{{$blank}}" class="form-text">Enter the answer for {{$blank}}.</div>
                                    </div>
                                    <div class="mb-3 col-3">
                                        <label for="grade_blank{{$blank}}" class="form-label">Grade for {{$blank}}</label>
                                        <input type="number" class="form-control" id="grade_blank{{$blank}}" name="grade_blank{{$blank}}" aria-describedby="gradeHelp{{$blank}}" value="{{ old('grade_blank'.$blank) }}" required>
                                        <div id="gradeHelp{{$blank}}" class="form-text">Enter the grade for {{$blank}}.</div>
                                    </div>
                                    <div class="mb-5 col-3 ">
                                        <label for="status_blank{{$blank}}" class="form-label">Case sensitivity for {{$blank}}</label>
                                        <select class="form-select" id="status_blank{{$blank}}" name="status_blank{{$blank}}" aria-describedby="statusHelpblank{{$blank}}" required>
                                            <option class="form-select" value="true" {{ old('status_blank'.$blank) == "true" ? 'selected' : '' }}>case sensitive</option>
                                            <option class="form-select" value="false" {{ old('status_blank'.$blank) == "false" ? 'selected' : '' }}>case insensitive</option>
                                        </select>
                                        <div id="statusHelpblank{{$blank}}" class="form-text">Choose the case sensitivity for {{$blank}}.</div>
                                    </div>
                                    <div class="col-2 mt-3 mb-3 border-bottom"></div>
                                @endforeach
                            @endif
                            <div class="mb-3 mt-3 col-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        @elseif($question_type->type_name == 'Essay')
                            <div class="mb-3 col-10">
                                <label for="text" class="form-label">Question</label>
                                <textarea class="form-control" id="text" name="text" rows="3" aria-describedby="textHelp" required>{{ old('text') }}</textarea>
                                <div id="textHelp" class="form-text">Enter the question.</div>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="answer" class="form-label">Answer</label>
                                <input type="text" class="form-control" id="answer" name="answer" aria-describedby="answerHelp" value="{{ old('answer') }}" required>
                                <div id="answerHelp" class="form-text">Enter the answer.</div>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="grade" class="form-label">Grade</label>
                                <input type="number" class="form-control" id="grade" name="grade" aria-describedby="gradeHelp" value="{{ old('grade') }}" required>
                                <div id="gradeHelp" class="form-text">Enter the grade of your question.</div>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="is_case_sensitive" class="form-label">Case sensitivity</label>
                                <select class="form-select" id="is_case_sensitive" name="is_case_sensitive" aria-describedby="statusHelp" required>
                                    <option class="form-select" value="true" {{ old('is_case_sensitive') == "true" ? 'selected' : '' }}>case sensitive</option>
                                    <option class="form-select" value="false" {{ old('is_case_sensitive') == "false" ? 'selected' : '' }}>case insensitive</option>
                                </select>
                                <div id="statusHelp" class="form-text">Choose the case sensitivity.</div>
                            </div>
                            <div class="mb-3 mt-3 col-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        @endif
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection

<script>
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
        var optionsContainer = document.getElementById("options-contianer");
        var optionNumber = optionsContainer.childElementCount + 1;
        var newOption = document.createElement("div");
        newOption.innerHTML = `
            <div class="mb-3 col-3">
                <label for="option${optionNumber}" class="form-label">Option ${optionNumber}</label>
                <input type="text" class="form-control" id="option${optionNumber}" name="option${optionNumber}" aria-describedby="option${optionNumber}Help" value="{{ old('option${optionNumber}') }}" >
                <div id="option${optionNumber}Help" class="form-text">Enter the option.</div>
            </div>
        `;
        optionsContainer.appendChild(newOption);
    }
</script>
