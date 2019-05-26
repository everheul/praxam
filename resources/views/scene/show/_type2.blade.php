{{-- scene.show.type2
     Show a scene of type 2, its question and answers.
     input: $scene
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div id="scene-show" class="card p-4 w-100">
                @isset($scene->head)
                <h3>{{ $scene->head }}</h3>
                @endisset
                {!! App\Helpers\Helper::brToSpace($scene->first_question()->text) !!}
                <div class="card-body mt-0 p-2">
                    {{ Form::open(['id' => "scene", 'class' => 'qform', 'url' => '/send']) }}
                    <input type="hidden" name="scene_id" value="{{ $scene->id }}" />
                    @foreach ($scene->first_question()->answers() as $answer)
                        <div class="input-group-prepend mb-1">
                            <div class="input-group-text">
                                <input type="checkbox" name="answer_{{ $answer->id }}" iscool="{{ $answer->is_correct }}"/>
                                <div class="nots pl-3 py-0">{{ $answer->text }}</div>
                            </div>
                        </div>
                    @endforeach
                    {{ Form::close() }}
                    <button id="done" class="btn btn-outline-danger px-4 py-1 mt-2">Done</button>
                </div>
                <div id="ëxpo" style="display: none">
                    <div class="mt-3 px-3 pt-3 explanation">
                        <h4>Explanation:</h4>
                        {!! $scene->first_question()->explanation !!}
                    </div>
                    <button id="next" class="btn btn-success px-4 py-1 mt-3">Continue</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>

    function isAnswered() {
        //console.log("isAnswered");
        var ret = false;
        $("form#scene :input[type='checkbox']:checked").each(function(){
            //console.log($(this));
            ret = true;
        });
        return ret;
    }

    function isCorrect() {
        var ret = false;
        $("form#scene :input[type='checkbox']:checked").each(function(){
            //console.log($(this));
            ret = ($(this).attr('iscool') == 1);
        });
        return ret;
    }

    function showAnswer() {
        $("form#scene :input[type='checkbox']").each(function(){
            //console.log($(this));
            if ($(this).attr('iscool') == 1) {
                $(this).parent('div').addClass('correct');
            } else {
                $(this).parent('div').addClass('wrong');
            }
            $(this).prop('disabled', true);
        });
        $("#done").prop('disabled', true);
    }

    $("#done").click(function() {
        if (isAnswered()) {
            if (isCorrect()) {
                //- next question?
                $("form#scene").submit();
            } else {
                $("#ëxpo").toggle();
                showAnswer();
            }
        }
    });

    $("#next").click(function() {
        $("form#scene").submit();
    });

</script>
@endpush
