@extends('layouts.app')
@section('title', 'Create exam')

<script src="{{asset('js/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">

@section('content')
    <main class="page">
        <section class="clean-block clean-catalog dark">
            <div class="container">
                <div class="content">
                    <div class="row_cus">
                        <div class="text-center" style="padding-top: 2rem;">
                            <div class="block-heading">
                                <h3 class="text-info"><dt>Exam options</dt></h3>
                            </div>
                        </div>
                        <form method="POST" action="{{ Route('instructor_classrooms.exams.create.post', $classroom->slug)}}">
                            @if(old('exam_options_done'))
                                @foreach(old('exam_options_done') as $option)
                                    <input type="hidden" name="exam_options_done[]" value="{{$option}}">
                                @endforeach
                            @else
                                <div class="text-center">
                                    @foreach($exam_options as $option)
                                        <div class="row justify-content-md-center" >
                                            <div class="col-md-6">
                                                <div class="clean-product-item">
                                                    <div class="form-check form-switch">
                                                        <label class="form-check-label" for="{{$option->name}}">{{$option->name}}</label>
                                                        <input class="form-check-input" type="checkbox" id="{{$option->name}}" name="{{$option->name}}">
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
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

<style>
    .row_cus{
        min-height: 80vh;
    }
</style>
