{{-- scene.show.type3
     Show a scene of type 3, its question and (dragable) answers.
     input: $scene, $question

     POST data:
     scene.question.answer
--}}

@extends('layouts.exam')

@section('content')
    <div class="container" id="scene-show">
        <div class="row justify-content-center mb-3">
            <div class="card p-4 w-100">
                @isset($scene->head)
                    <h3>{{ $scene->head }}</h3>
                @endisset
                @isset($scene->instructions)
                    <div class="instructions">{!! $scene->instructions !!}</div>
                @endisset
                {!!  App\Helpers\Helper::brbrToP($scene->text) !!}
                @isset($scene->image)
                    <img class="mr-auto" src="/img/{{ $scene->image }}" alt="" >
                @endisset
            </div>
        </div>
        {{ Form::open(['id' => "scene", 'class' => 'qform', 'url' => '/send']) }}
        <input type="hidden" name="scene_id" value="{{ $scene->id }}" />

        <div class="row justify-content-center mb-3">
            <div class="accordion mt-2 pb-3 w-100" id="questions">

                @foreach ($scene->questions() as $question)
                    <div class="card" id="question-show">
                        <div class="card-header p-0" id="heading{{ $loop->iteration }}">
                            <button class="btn btn-link btn-lg" type="button" data-toggle="collapse" data-target="#collapse{{ $loop->iteration }}" aria-expanded="true" aria-controls="collapse{{ $loop->iteration }}">
                                Question {{ $loop->iteration }}
                            </button>
                        </div>

                        @if($loop->first)
                            <div id="collapse{{ $loop->iteration }}" class="collapse show" aria-labelledby="heading{{ $loop->iteration }}" data-parent="#questions">
                        @else
                            <div id="collapse{{ $loop->iteration }}" class="collapse" aria-labelledby="heading{{ $loop->iteration }}" data-parent="#questions">
                        @endif

                            <div class="card-body">
                                <div class="card-body mt-0 p-2">
                                    {!! App\Helpers\Helper::brToSpace($question->text) !!}

                                    @foreach ($question->answers() as $answer)
                                        <div class="input-group-prepend mb-1">
                                            <div class="input-group-text">
                                                <input type="radio" name="answer" value="{{ $answer->id }}" iscool="{{ $answer->is_correct }}"/>
                                                <div class="pl-3 py-0">{{ $answer->text }}</div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <button id="done_{{ $question->id }}" class="btn btn-outline-danger px-4 py-1 mt-2">Done</button>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')
<script>

    function sendQuestionResult() {

    }

    $(function () {
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
