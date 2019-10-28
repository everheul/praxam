{{-- question.type1.edit
     Edit a question of type 1 - with radiobox answers.
     input: $question
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Edit Question</h3>
                <h4>Scene: {{ $scene->head }}</h4>
                <hr />
                <form method="post" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="exam_id" type="hidden" value="{{ $scene->exam_id }}">
                    <input name="scene_id" type="hidden" value="{{ $scene->id }}">
                    <input name="question_id" type="hidden" value="{{ $question->id }}">

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

                    <div class="form-group row {{ $errors->has('head') ? 'has-error' : '' }}">
                        <label for="head" class="col-md-2 pt-2 control-label">Head<p>± four or five words</p></label>
                        <div class="col-md-10">
                            <input class="form-control" name="head" type="text" id="head" value="{{ old('head', optional($question)->head) }}" maxlength="191" placeholder="Enter head here...">
                            {!! $errors->first('head', '<p class="form-text text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{ $errors->has('text') ? 'has-error' : '' }}">
                        <label for="text" class="col-md-2 pt-2 control-label">Question Text<p>± as long as you need</p></label>
                        <div class="col-md-10">
                            <textarea name="text" id="textid" class="ckeditor form-control w-100" placeholder="Question Text" maxlength="5000" rows="3" >{!! old('text', optional($question)->text) !!}</textarea>
                            {!! $errors->first('text', '<p class="form-text text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{ $errors->has('explanation') ? 'has-error' : '' }}">
                        <label for="explanation" class="col-md-2 pt-2 control-label">Explanation<p>± as long as you need</p></label>
                        <div class="col-md-10">
                            <textarea name="explanation" id="explanation" class="form-control w-100" placeholder="Explanation" maxlength="5000" rows="3" >{{ old('explanation', optional($question)->explanation) }}</textarea>
                            {!! $errors->first('explanation', '<p class="form-text text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group row {{ $errors->has('points') ? 'has-error' : '' }}">
                        <label for="points" class="col-md-2 pt-2 control-label">Points<p>± from 1-10</p></label>
                        <div class="col-md-10">
                            <input class="form-control" name="points" type="number" id="points" value="{{ old('points', optional($question)->points) }}" min="1" max="10" placeholder="Question Points">
                            {!! $errors->first('points', '<p class="form-text text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="submit"></label>
                        <div class="col-sm-10">
                            <button name="save_stay" type="submit" class="btn btn-primary">Save &amp; Stay</button>
                            <button name="save_show" type="submit" class="btn btn-primary ml-2">Save &amp; Show</button>
                            <a class="btn btn-primary ml-2 px-4" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/show" role="button">Cancel</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

@include('scene.edit_scripts')

{{--
<div class="form-group row">
    <label for="q_{{ $question->id }}_text" class="col-sm-2 col-form-label">Question Text</label>
    <div class="col">
        <textarea name="question[{{ $question->id }}][text]" id="q_{{ $question->id }}_text" class="ckeditor w-100" placeholder="Question Text" rows="8" >
            {!! $question->text !!}
        </textarea>
    </div>
</div>
<div class="form-group row">
    <label for="q_{{ $question->id }}_answers" class="col-sm-2 col-form-label">Answers</label>
    <div class="col-sm-10 table-responsive" id="q_{{ $question->id }}_answers">
        <table class="table table-bordered table-sm m-0">
            <tbody>
            @foreach ($question->answers as $answer)
                <tr id="tra{{ $answer->id }}">
                    <td class="align-middle text-center">
                        <input name="answer[{{ $answer->id }}][order]" type="number" class="answer-order btooltip" value="{{ $loop->iteration }}" data-toggle="tooltip" title="Change Answers Order" />
                    </td>
                    <td class="w-75">
                        <textarea class="form-control" name="answer[{{ $answer->id }}][text]" rows="1">{{ $answer->text }}</textarea>
                    </td>
                    <td class="align-middle text-center">
                        <input name="question[{{ $question->id }}][iscool]" type="radio" class="btooltip" value="{{ $answer->id }}" data-toggle="tooltip" title="Correct Answer" {{ $answer->is_correct ? "checked" : "" }} />
                    </td>
                    <td class="align-middle text-center">
                        <button id="dela{{ $answer->id }}" answerid="{{ $answer->id }}" class="btn btn-sm btn-outline-dark del-answer">Delete</button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-center">
                    <button id="add_answer_{{ $question->id }}" class="btn btn-sm btn-outline-dark m-1 add-answer">Add New Answer</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="form-group row">
    <label for="q_{{ $question->id }}_explanation" class="col-sm-2 col-form-label">Explanation 4</label>
    <div class="col-sm-10">
        <textarea name="question[{{ $question->id }}][explanation]" id="q_{{ $question->id }}_explanation" class="ckeditor w-100" placeholder="Explanation" rows="6" >
            {!! $question->explanation !!}
        </textarea>
    </div>
</div>
--}}
