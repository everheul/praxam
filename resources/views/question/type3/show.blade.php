{{-- question.type3.show
     Show a question of type 3 with its (draggable) answers.
     input: $praxquestion, $praxscene
--}}
<question id="{{ $praxquestion->question->id }}">
    {!! $praxquestion->questionHeadStr() !!}
    {!! $praxquestion->question->text !!}
    <div class="card-body mt-0 p-2">
         <form method="POST" action="/answer" id="form_{{ $praxquestion->question->id }}" accept-charset="UTF-8">
            {{ csrf_field() }}
            <input name="useraction" type="hidden" value="{{ $useraction }}">
            @if($useraction === 'ANSWER')
                <input name="userquestion" type="hidden" value="{{ $praxquestion->userquestion->id }}">
            @else
                <input name="exam" type="hidden" value="{{ $praxquestion->question->scene->exam_id }}">
            @endif
            <div class="row justify-content-center align-items-start mb-4">
                <div class="col-xl-4 col-lg-5 col-sm-6 mt-1 mb-auto p-0">
                    <div class="col-head mr-0 mr-sm-1 mr-lg-2 mr-xl-3">Possible Choices:</div>
                    <div id="choices_{{ $praxquestion->question->id }}{{ $praxquestion->locked ? '_disabled' : '' }}" class="sortable mr-0 mr-sm-1 mr-lg-2 mr-xl-3">
                        @foreach ($praxquestion->praxanswers as $praxanswer)
                            @if(!$praxanswer->checked)
                                <div class="draggable nots card px-3 py-1 m-2 {{ $praxanswer->coolnessStr() }}" value="{{ $praxanswer->answer->id }}">
                                    {{ $praxanswer->orderStr() }}{{ $praxanswer->answer->text }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-sm-6 mt-3 mt-sm-1 mb-auto p-0">
                    <div class="col-head ml-0 ml-sm-1 ml-lg-2 ml-xl-3">Answer Area:</div>
                    <div id="answers_{{ $praxquestion->question->id }}{{ $praxquestion->locked ? '_disabled' : '' }}" class="sortable ml-0 ml-sm-1 ml-lg-2 ml-xl-3">
                        @foreach ($praxquestion->praxanswers as $praxanswer)
                            @if($praxanswer->checked)
                                <div class="draggable nots card px-3 py-1 m-2 {{ $praxanswer->coolnessStr() }}" value="{{ $praxanswer->answer->id }}">
                                    {{ $praxanswer->orderStr() }}{{ $praxanswer->answer->text }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="input-group-prepend mt-4">
                <button id="done_{{ $praxquestion->question->id }}" class="btn btn-outline-danger px-4 py-1"{{ $praxquestion->disabledStr() }}>Done</button>
    @if($praxquestion->locked)
                <a class="btn btn-primary px-4 py-1 ml-2" href="/prax/{{ $praxscene->userscene->userexam_id }}/scene/{{ $praxscene->userscene->order }}/question/{{ $praxquestion->order }}/next">Continue</a>
    @endif
            </div>
         </form>
    </div>
    @if($praxquestion->locked)
    <div id="exp_{{ $praxquestion->question->id }}">
        <div class="mt-3 px-3 pt-3 explanation">
            <h4>Explanation:</h4>
            {!! $praxquestion->question->explanation !!}
        </div>
    </div>
    @endif
</question>
