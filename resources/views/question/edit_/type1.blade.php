{{-- question.edit.type1
     Edit a question of type 1 with its radiobox answers.
     input: $question
--}}
<hr/>
<p>Question {{ $question->id }}, type 1:</p>
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
                @foreach ($question->getAnswers() as $answer)
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
