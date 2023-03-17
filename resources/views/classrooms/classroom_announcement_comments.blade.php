@extends('layouts.app')
@section('title', 'Announcement Comments')

@section('content')
    <main class='page'>
        <section class="clean-block clean-catalog dark">
            <div class="container">
                <div class="content" style="margin-top: 2rem;">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card">
                                <div class="card-body" style="padding: 6rem;">
                                    <div class="user mt-1" style="padding-left: 3rem;">
                                        <div class="row">
                                            <div class="col-sm-1">
                                                @if($announcement->announcement_author()->author_role == 'instructor')
                                                    <img
                                                        src="{{ asset('ProfilePics/instructors/' . $announcement->announcement_author->photo) }}"
                                                        alt="user" class="profile-photo-lg">
                                                @else
                                                    <img
                                                        src="{{ asset('ProfilePics/students/' . $announcement->announcement_author->photo) }}"
                                                        alt="user" class="profile-photo-lg">
                                                @endif
                                            </div>
                                            <div class="col pt-3">
                                                @auth('instructor')
                                                    <h5>
                                                        <a href="{{ Route('instructor_get_user', ['id' => $announcement->announcement_author->id, 'slug' => $classroom->slug , 'role'=> $announcement->announcement_author()->author_role ])}}">{{ $announcement->announcement_author->name }}</a>&nbsp&nbsp<small><a
                                                                class="btn btn-outline-primary btn-sm" role="button"
                                                                style="cursor: default">{{ $announcement->announcement_author()->author_role }}</a></small>
                                                    </h5>
                                                    &nbsp&nbsp&nbsp{{ $announcement->date_created }}
                                                @endauth
                                                @auth('web')
                                                    <h5>
                                                        <a href="{{ Route('student_get_user', ['id' => $announcement->announcement_author->id, 'slug' => $classroom->slug , 'role'=> $announcement->announcement_author()->author_role ])}}">{{ $announcement->announcement_author->name }}</a>&nbsp&nbsp<small><a
                                                                class="btn btn-outline-primary btn-sm" role="button"
                                                                style="cursor: default">{{ $announcement->announcement_author()->author_role }}</a></small></h5>
                                                    &nbsp&nbsp&nbsp{{ $announcement->date_created }}
                                                @endauth
                                                <p style="padding-top: 1.5rem;">{{ $announcement->title }}</p>
                                                <br/>
                                                <p>{{ $announcement->text }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="text-info clean-product-item">
                                        <i class="fas fa-comment"></i>&nbsp&nbsp&nbspComments
                                    </h5>
                                    @foreach($comments as $comment)
                                        <div class="user mt-1" style="padding-left: 3rem;">
                                            <div class="row">
                                                <div class="col-sm-1">
                                                    @if($comment->author_role == 'instructor')
                                                        <img
                                                            src="{{ asset('ProfilePics/instructors/' . $comment->comment_author->photo ) }}"
                                                            alt="user" class="profile-photo-lg">
                                                    @elseif($comment->author_role == 'student')
                                                        <img
                                                            src="{{ asset('ProfilePics/students/' . $comment->comment_author->photo ) }}"
                                                            alt="user" class="profile-photo-lg">
                                                    @endif
                                                </div>
                                                <div class="col pt-3">
                                                    @auth('instructor')
                                                        <h5>
                                                            <a href="{{ Route('instructor_get_user', ['id' => $announcement->announcement_author->id, 'slug' => $classroom->slug , 'role'=> $comment->author_role ])}}">{{ $comment->comment_author->name }}</a>&nbsp&nbsp<small><a
                                                                    class="btn btn-outline-primary btn-sm" role="button"
                                                                    style="cursor: default">{{ $comment->author_role }}</a></small>
                                                        </h5>
                                                        &nbsp&nbsp&nbsp{{ $comment->date_created }}
                                                    @endauth
                                                    @auth('web')
                                                        <h5>
                                                            <a href="{{ Route('student_get_user', ['id' => $announcement->announcement_author->id, 'slug' => $classroom->slug , 'role'=> $comment->author_role ])}}">{{ $comment->comment_author->name }}</a>&nbsp&nbsp<small><a
                                                                    class="btn btn-outline-primary btn-sm" role="button"
                                                                    style="cursor: default">{{ $comment->author_role }}</a></small>
                                                        </h5>
                                                        &nbsp&nbsp&nbsp{{ $comment->date_created }}
                                                    @endauth
                                                    <p style="padding-top: 1.5rem;">{{ $comment->text }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @auth('instructor')
                                        <form
                                            action="{{ Route('instructor_classrooms.comment', ['id' => $announcement->id, 'slug' => $classroom->slug]) }}"
                                            method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="text">Comment</label>
                                                <textarea class="form-control" id="text" name="text" rows="3"
                                                          placeholder="Write your comment here..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                        </form>
                                    @endauth
                                    @auth('web')
                                        <form
                                            action="{{ Route('student_classroom.comment', ['id' => $announcement->id, 'slug' => $classroom->slug]) }}"
                                            method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="text">Comment</label>
                                                <textarea class="form-control" id="text" name="text" rows="3"
                                                          placeholder="Write your comment here..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                        </form>
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

    .people .user {
        padding: 20px 0;
        border-top: 1px solid #f1f2f2;
        border-bottom: 1px solid #f1f2f2;
        margin-bottom: 20px;
    }

    img.profile-photo-lg {
        height: 80px;
        width: 80px;
        border-radius: 50%;
    }
</style>
