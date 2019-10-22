{{-- question.create
     Create a question of any type.
     input: $exam_id, $scene_id, $sidebar
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Create New Question</h3>
                <hr />
                <form method="post" action="/exam/{{ $exam_id }}/scene/{{ $scene_id }}}}/question/store" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="exam_id" type="hidden" value="{{ $exam_id }}">
                    <input name="scene_id" type="hidden" value="{{ $scene_id }}">
                    <div class="form-group row {{ $errors->has('question_type_id') ? 'has-error' : '' }}">
                        <label for="question_type_id" class="col-md-2 pt-2 control-label">Question Type</label>
                        <div class="col-md-5">
                            <select class="form-control" id="question_type_id" name="question_type_id">
                                @foreach ($question_types as $key => $question_type)
                                    <option value="{{ $key }}"{{ empty($key)?' selected' : '' }}>{{ $question_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5 text-secondary">
                            Select the type of your first (or only) question.
                            Should it have <b>One correct answer</b> (radio buttons) or <b>More</b>?
                            Or do you want the correct answers in the correct <b>order</b>?
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="submit"></label>
                        <div class="col-sm-10">
                            <button id="submit" name="submit" class="btn btn-primary mr-1">Create Question</button>
                            <a class="btn btn-primary ml-2" href="/exam/{{ $exam_id }}/scene" role="button">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
