@extends('layouts.app')
@section('title', 'Question Bank')

<script src="{{asset('js/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">

@section('content')
<main class='page'>
    <section class="clean-block clean-catalog dark">
        <div class="container">
            <div class="content" style="margin-top: 2rem;">
                <div class="row">
                    <div class="m-3">
                        <div class="nav-item dropdown" style="padding-top: 0rem;"><a class="dropdown-toggle btn btn-success" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-plus"></i>Add New Question</a>
                                <div class="dropdown-menu">
                                    @foreach($question_types as $question_type)
                                            <a class="dropdown-item" href="{{route('instructor_questions.create', ['question_type' => $question_type->id, 'type_name' => $question_type->type_name, 'slug' => $classroom->slug])}}">{{$question_type->type_name}}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <table id='questions-table'>
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Subject</th>
                                            <th scope="col">Author</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Grade</th>
                                            <th scope="col">Edit</th>
                                            <th scope="col">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($questions as $question)
                                        <tr>
                                            <td>{{ $question->title }}</td>
                                            <td>{{ $question->category }}</td>
                                            <td>{{ $question->subject }}</td>
                                            <td>{{ $question->instructor_name }}</td>
                                            <td>{{ $question->type_name }}</td>
                                            <td>{{ $question->grade }}</td>
                                            @if($questions->count() > 0 && Auth::guard('instructor')->user()->id == $question->instructor_id)
                                            <td><a href="{{ Route('instructor_questions.edit', ['question_slug' => $question->slug, 'slug' => $classroom->slug]) }}" class="btn btn-primary"><small>Edit</small></a></td>
                                            <td><a href="{{ Route('instructor_questions.delete', ['question_slug' => $question->slug, 'slug' => $classroom->slug]) }}" class="btn btn-danger"><small>Delete</small></a></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
                "emptyTable": "No data available"
            }
        });

    });
</script>
