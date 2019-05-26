{{-- question.show.type3
     Show a question of type 3 with its (dragable) answers.
     input: $question
--}}
<question id="{{ $question->id }}">
    {!! $question->text !!}
    <div class="card-body mt-0 p-2">
        <div class="row justify-content-center align-items-start mb-3">
            <div class="col-xl-4 col-lg-5 col-sm-6 mt-1 mb-auto p-0">
                <div class="col-head mr-0 mr-sm-1 mr-lg-2 mr-xl-3">Possible Choices:</div>
                <div id="choices_{{ $question->id }}" class="sortable mr-0 mr-sm-1 mr-lg-2 mr-xl-3">
                    @foreach ($question->getAnswers() as $answer)
                        <div class="dragable nots card px-3 py-1 m-2" value="{{ $answer->id }}" iscool="{{ $answer->is_cool() }}">
                            {{ $answer->text }}
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-sm-6 mt-3 mt-sm-1 mb-auto p-0">
                <div class="col-head ml-0 ml-sm-1 ml-lg-2 ml-xl-3">Answer Area:</div>
                <div id="answers_{{ $question->id }}" class="sortable ml-0 ml-sm-1 ml-lg-2 ml-xl-3">
                </div>
            </div>
        </div>
        <button id="done_{{ $question->id }}" class="btn btn-outline-danger px-4 py-1 mt-2">Done</button>
    </div>
    <div id="ëxpo_{{ $question->id }}" style="display: none">
        <div class="mt-3 px-3 pt-3 explanation">
            <h4>Explanation:</h4>
            {!! $question->explanation !!}
        </div>
        <button id="next_{{ $question->id }}" class="btn btn-success px-4 py-1 mt-3">Continue</button>
    </div>

</question>
