{{-- scene.show.type3
     Show a scene of type 3, its question and (dragable) answers.
     input: $scene
--}}

@extends('layouts.exam')

@section('content')
    <div class="container" id="scene-show">
        <div class="row justify-content-center mb-3">
            <div class="card p-4 w-100">
                {{ Form::open(['id' => "scene", 'class' => 'qform', 'url' => '/send']) }}
                <input type="hidden" name="scene_id" value="{{ $scene->id }}" />
                @foreach ($scene->first_question()->answers() as $answer)
                <input type="hidden" name="answer_{{ $answer->id }}" id="answer_{{ $answer->id }}" value="0" />
                @endforeach
                {{ Form::close() }}
                @isset($scene->head)
                <h3>{{ $scene->head }}</h3>
                @endisset
                {!! App\Helpers\Helper::brToSpace($scene->first_question()->text) !!}
            </div>
        </div>
        <div class="row justify-content-center align-items-start mb-3">

            <div class="col-xl-4 col-lg-5 col-sm-6 mt-1 mb-auto p-0">
                <div class="col-head mr-0 mr-sm-1 mr-lg-2 mr-xl-3">Possible Choices:</div>
                <div id="choices" class="sortable mr-0 mr-sm-1 mr-lg-2 mr-xl-3">
                @foreach ($scene->first_question()->answers() as $answer)
                    <div class="dragable nots card px-3 py-1 m-2" answerid="{{ $answer->id }}" iscool="{{ $answer->correct_order }}">
                        {{ $answer->text }}
                    </div>
                @endforeach
                </div>
            </div>

            <div class="col-xl-4 col-lg-5 col-sm-6 mt-3 mt-sm-1 mb-auto p-0">
                <div class="col-head ml-0 ml-sm-1 ml-lg-2 ml-xl-3">Answer Area:</div>
                <div id="answers" class="sortable ml-0 ml-sm-1 ml-lg-2 ml-xl-3">

                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="card px-3 w-100">
                <div class="card-body mt-0 p-2">
                    <button id="done" class="btn btn-outline-danger px-4 py-1 my-2">Done</button>
                    <div id="ëxpo" style="display: none">
                        <div class="px-3 pt-3 explanation">
                            <h4>Explanation:</h4>
                            {!! $scene->first_question()->explanation !!}
                        </div>
                        <button id="next" class="btn btn-success px-4 py-1 my-2">Continue</button>
                    </div>
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
        $("#answers .dragable").each(function(){
            //console.log($(this));
            ret = true;
        });
        return ret;
    }

    function isCorrect() {
        var ret = true;
        var order = 1;
        $("#answers .dragable").each(function(){
            if ($(this).attr('iscool') != order) ret = false;
            //- set form input value
            var id = $(this).attr('answerid');
            $('input[name=answer_' + id + ']').val(order);
            order += 1;
        });
        //- anything forgotten?
        $("#choices .dragable").each(function(){
            if ($(this).attr('iscool') != 0) ret = false;
            //- set form input value
            var id = $(this).attr('answerid');
            $('input[name=answer_' + id + ']').val(0);
        });
        //- set the input values:

        return ret;
    }

    function showAnswer() {
        $(".sortable .dragable").each(function(){
            var order = $(this).attr('iscool');
            if (order > 0) {
                $(this).addClass('correct');
                $(this).prepend( order + '. ');
            } else {
                $(this).addClass('wrong');
            }
        });
        //- disable changes
        $( ".sortable" ).sortable({ disabled: true });
        $("#done").prop('disabled', true);
    }

    $(function () {
        $('#choices, #answers').sortable({ connectWith: '.sortable', cursor: "move" });

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
    });

</script>
@endpush
