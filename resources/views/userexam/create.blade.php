{{-- examuser.create
     input: $exam
--}}
@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="card w-100">
                <h1 class="text-center mt-2">Take a Practice Exam</h1>
                <h3 class="text-center">{{ $exam->name }}, {{ $exam->head }}</h3>
                <form method="post" action="/prax/{{ $exam->id }}/store" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="exam_id" type="hidden" value="{{ $exam->id }}">
                    <div class="form-row px-2 mt-2">
                        <div class="form-group col-md-6 mx-auto">
                            <label for="scene_count" class="col col-form-label">Number of Scenes</label>
                            <select class="form-control" name="scene_count">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <div class="form-group col-md-6 mx-auto">
                            <label for="scene_rand" class="col col-form-label">Scene Type</label>
                            <select class="form-control" name="scene_type">
                                <option value="0">All Types (random)</option>
                                <option value="1">Single-Question Scenes</option>
                                <option value="2">Multi-Question Scenes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row px-2">
                        <div class="form-group col-md-6 mx-auto">
                            <label for="scene_rand" class="col col-form-label">Question Type</label>
                            <select class="form-control" name="question_type">
                                <option value="0">All Types (random)</option>
                                <option value="1">One-Answer Questions (radio)</option>
                                <option value="2">More-Answers Questions (check)</option>
                                <option value="3">Ordered-Answers Questions (move)</option>
                            </select>
                        </div>
                    </div>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <div class="alert alert-danger text-center alert-dismissible fade show col-md-6 mx-auto" role="alert">
                        <strong>{{ $error }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endforeach
                @endif
                    <div class="form-row px-4 mb-4 text-center">
                        <div class="col-md-6 mx-auto px-2">
                            <button type="submit" class="btn btn-primary">Start Test</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
