{{-- examuser.create
     input: $exam
--}}
@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="card w-100">
                <div class="examtext">
                    <h1 class="text-center">Take a Practice Exam</h1>
                    <h3 class="text-center">{{ $exam->name }}, {{ $exam->head }}</h3>
                    <form method="post" action="/prax/{{ $exam->id }}/store" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input name="exam_id" type="hidden" value="{{ $exam->id }}">
                        <div class="form-group row">
                            <label for="scene_count" class="col col-form-label">Number of Scenes</label>
                            <div class="col-sm-10">
                                <select class="custom-select" name="scene_count">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="15">15</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="introid" class="col col-form-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Start</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
