@extends('layouts.app')

@section('title', 'StudentsCheat')

<script src="{{asset('js/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('js/datatables.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">

@section('content')
<main class='page'>
<section class="clean-block clean-catalog dark">
    <div class="container">
        <div class="content" style="margin-top: 2rem;">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @auth('instructor')
                            <table id='students-table'>
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Institute</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Exam Title</th>
                                        <th scope="col">Exam Date</th>
                                        <th scope="col">Cheating Attempts</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                        <tr>
                                            <td>{{$studentt->name}}</td>
                                            <td>{{$studentt->institute}}</td>
                                            <td>{{$studentt->email}}</td>
                                            <td>{{$exam->title}}</td>
                                            <td>{{$exam->start_date}}</td>
                                            <td>{{$cheating_attempts->student_attempts}}</td>
                                        
                                        </tr>
                                    
                                </tbody>
                            </table>
                            @endauth
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection

<style>
.people {
  background: #f8f8f8;
  border-radius: 4px;
  border: 1px solid #f1f2f2;
  padding: 20px;
  margin-bottom: 20px;
}

.people {
  height: 300px;
  width: 100%;
  border: none;
}

.people .user{
  padding: 20px 0;
  border-top: 1px solid #f1f2f2;
  border-bottom: 1px solid #f1f2f2;
  margin-bottom: 20px;
}

img.profile-photo-lg{
  height: 80px;
  width: 80px;
  border-radius: 50%;
}
</style>

<script>
    $(document).ready(function() {
        $('#students-table').DataTable();
    });
</script>
