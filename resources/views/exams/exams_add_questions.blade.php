@extends('layouts.app')
@section('title', 'Select new questions')

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
                        <h1 class="h4">Select new questions</h1>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ Route('instructor_classrooms.exams.questions.add.post', ['slug' => $classroom->slug , 'exam_slug' => $exam->slug]) }}" method="POST">
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
                                    @foreach ($new_questions as $question)
                                        <tr>
                                            <td><input type="checkbox" name="exam_questions_add[]" value="{{ $question->id }}"></td>
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
                                <button class="btn btn-success mt-2" type="submit"><small>Add selected questions</small></button>
                            </form>
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
                "emptyTable": "No questions available to add"
            }
        });

    });
</script>
