{{-- question.show.type1
     Show a question of type 1 with its radiobox answers.
     input: $userQuestion, $question
--}}
<question id="{{ $question->id }}">
    {!! $question->text !!}
    <div class="card-body mt-0 p-2">
        @foreach ($question->answers as $answer)
            <div class="input-group-prepend mb-1">
                <div class="input-group-text minw-25 {{ $answer->coolness() }}">
                    <input type="radio" name="question_{{ $question->id }}" iscool="{{ $answer->is_cool() }}" value="{{ $answer->id }}" {{ $answer->checked() }} {{ $answer->disabled() }} />
                    <div class="pl-3 py-0">{{ $answer->text }}</div>
                </div>
            </div>
        @endforeach
        <button id="done_{{ $question->id }}" class="btn btn-outline-danger px-4 py-1 mt-2" {{ $question->locked() ? 'disabled' : '' }}>Done</button>
    </div>
    <div id="ëxpo_{{ $question->id }}" style="{{ $question->locked() ? '' : 'display:none' }}" >
        <div class="mt-3 px-3 pt-3 explanation">
            <h4>Explanation:</h4>
            {!! $question->explanation !!}
        </div>
        <button id="next_{{ $question->id }}" class="btn btn-success px-4 py-1 mt-3" >Continue</button>
    </div>
</question>
