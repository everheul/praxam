{{-- question.type1.show
     Show a question of type 1, with radiobox answers.
     input: $userquestion
--}}
<question id="{{ $userquestion->question->id }}">
    {!! $userquestion->question->text !!}
    <div class="card-body mt-0 p-2">
        <form method="POST" action="/answer" id="uq_{{ $userquestion->id }}" name="uq_{{ $userquestion->id }}" accept-charset="UTF-8">
            {{ csrf_field() }}
            <input name="action" type="hidden" value="ANSWER">
            <input name="userquestion" type="hidden" value="{{ $userquestion->id }}">
            @foreach ($userquestion->question->answers as $answer)
                <div class="input-group-prepend mb-1">
                    <div class="input-group-text minw-25 {{ $answer->coolness() }}">
                        <input type="radio" name="uq_{{ $userquestion->question->id }}" value="{{ $answer->id }}" {{ $answer->checked() }} {{ $answer->disabled() }} />
                        <div class="pl-3 py-0">{{ $answer->text }}</div>
                    </div>
                </div>
            @endforeach
            <button id="done_{{ $userquestion->question->id }}" class="btn btn-outline-danger px-4 py-1 mt-2" {{ $userquestion->question->locked ? 'disabled' : '' }}>Done</button>
        </form>
    </div>

    <div id="ëxpo_{{ $userquestion->question->id }}" style="{{ $userquestion->question->locked ? '' : 'display:none' }}" >
        <div class="mt-3 px-3 pt-3 explanation">
            <h4>Explanation:</h4>
            {!! $userquestion->question->explanation !!}
        </div>
        <button id="next_{{ $userquestion->question->id }}" class="btn btn-success px-4 py-1 my-3" >Continue</button>
    </div>
</question>
