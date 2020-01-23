{{-- question.create
     Create a question of any type.
     input: $scene (with: exam), $question, $question_types, $sidebar
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>New Question</h3>
                <h4>Scene: {{ $scene->head }}</h4>
                <hr />
                <form method="post" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/store" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="exam_id" type="hidden" value="{{ $scene->exam_id }}">
                    <input name="scene_id" type="hidden" value="{{ $scene->id }}">

                    @include('question.form')

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label" for="submit"></label>
                        <div class="col-lg-10">
                            <button id="submit" name="submit" class="btn btn-primary mr-1">Create Question</button>
                            <a class="btn btn-outline-primary ml-3 px-4" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/edit" role="button">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@include('exam.edit_scripts')
