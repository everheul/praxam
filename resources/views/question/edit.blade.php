{{-- question.edit
     Edit a question of any type.
     input: $scene, $question
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

                    @include('question.form')

                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="submit"></label>
                        <div class="col-sm-10 pl-0">
                            <button name="save_stay" type="submit" class="btn btn-primary">Save &amp; Stay</button>
                            <button name="save_show" type="submit" class="btn btn-primary ml-2">Save &amp; Show</button>
                            <a class="btn btn-primary ml-2 px-4" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/show" role="button">Cancel</a>
                        </div>
                    </div>

                </form>
                <hr />

                {{-- ANSWERS --}}
                @foreach($question->answers as $key => $answer)
                <div class="form-group row">
                    <label class="col-lg-3 col-xl-2 col-form-label">Answer {{ $answer->order }}</label>
                    <div class="col-lg-9 col-xl-10">
                        <div class="card">
                            <div class="card-header p-1">
                                {{ $answer->text }}
                                <form method="POST" action="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/answer/{{ $answer->id }}/destroy" accept-charset="UTF-8">
                                    {{ csrf_field() }}
                                    <div class="btn-group btn-group-sm float-right" role="group">
                                        <button name="delete" type="submit" class="btn btn-danger" title="Delete Answer" onclick="return confirm(&quot;Click Ok to delete this Answer.&quot;)">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
				
                <div class="form-group row">
                    <label class="col-lg-3 col-xl-2 col-form-label"></label>
                    <div class="col-lg-9 col-xl-10">
                        <a class="btn btn-primary" href="/exam/{{ $question->scene->exam_id }}/scene/{{ $question->scene->id }}/question/{{ $question->id }}/answer/create" role="button">Add Answer</a>
                    </div>
                </div>

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
