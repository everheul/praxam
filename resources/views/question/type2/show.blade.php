{{-- question.type2.show
     Show a question of type 2, with checkbox answers.
     input: $praxquestion, $praxscene
--}}
<question id="{{ $praxquestion->question->id }}">
    {!! $praxquestion->question->text !!}
    <div class="card-body mt-0 p-2">
        <form method="POST" action="/answer" id="form_{{ $praxquestion->userquestion->id }}" accept-charset="UTF-8">
            {{ csrf_field() }}
            <input name="useraction" type="hidden" value="{{ $useraction }}">
            <input name="userquestion" type="hidden" value="{{ $praxquestion->userquestion->id }}">
            @foreach ($praxquestion->praxanswers as $praxanswer)
                <div class="input-group-prepend mb-1">
                    <div class="{{ $praxanswer->coolness() }}input-group-text minw-25">
                        <input type="checkbox" name="answer[]" value="{{ $praxanswer->answer->id }}" {{ $praxanswer->checked() }} {{ $praxanswer->disabled() }} />
                        <div class="pl-3 py-0">{{ $praxanswer->answer->text }}</div>
                    </div>
                </div>
            @endforeach
            <div class="input-group-prepend mt-4">
                <input class="btn btn-outline-danger px-4 py-1" type="submit" value="Done"{{ $praxquestion->disabled() }}>
    @if($praxquestion->locked)
                <a class="btn btn-primary px-4 py-1 ml-2" href="/prax/{{ $praxscene->userscene->userexam_id }}/scene/{{ $praxscene->userscene->order }}/next">Next Scene</a>
    @endif
            </div>
        </form>
    </div>
    @if($praxquestion->locked)
    <div id="exp_{{ $praxquestion->question->id }}" >
        <div class="mt-3 px-3 pt-3 explanation">
            <h4>Explanation:</h4>
            {!! $praxquestion->question->explanation !!}
        </div>
    </div>
    @endif
</question>
