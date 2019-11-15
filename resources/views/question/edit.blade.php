{{-- question.edit
     Edit a question of any type.
     input: $question (with: 'answers','scene','scene.exam')
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Edit Question {{ $question->order }} of {{ $question->scene->question_count }}</h3>
                <h4>Scene: {{ $question->scene->head }}</h4>
                <hr />
                <form method="post" class="p-0" action="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="exam_id" type="hidden" value="{{ $question->scene->exam_id }}">
                    <input name="scene_id" type="hidden" value="{{ $question->scene->id }}">
                    <input name="question_id" type="hidden" value="{{ $question->id }}">

                    @include('question.form')

                    <div class="form-group row mb-0">
                        <label class="col-lg-2 col-form-label" for="submit"></label>
                        <div class="col-lg-10">
                            <button name="save_stay" type="submit" class="btn btn-primary">Save &amp; Stay</button>
                            <button name="save_show" type="submit" class="btn btn-primary ml-2">Save &amp; Show</button>
                            <button name="save_next" type="submit" class="btn btn-primary ml-2">Save &amp; Next</button>
                        </div>
                    </div>

                </form>
                <hr />
                <div class="form-group row mb-3">
                    <label class="col-lg-2 col-form-label">Answers</label>
                    <div class="col-lg-10">
                        <a class="btn btn-primary" href="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/answers" role="button">Manage Answers</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@include('scene.edit_scripts')

