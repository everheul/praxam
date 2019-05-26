{{-- exam.show
     Show a scene, its question(s) and answers.
     input: $scene
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">

        @switch($scene->scene_type_id)
        @case(1)
        @case(2)
        @case(3)
            <div id="scene-show" class="card p-4 w-100">
                @isset($scene->head)
                    <h3>{{ $scene->head }}</h3>
                @endisset
                <div class="card-body">
                    @include('question.show',['question' => $scene->question()])
                </div>
            </div>
            @break
        @case(4)
        @case(5)
        @case(6)
            <div id="scene-show" class="card p-4 w-100">
                @isset($scene->head)
                    <h3>{{ $scene->head }}</h3>
                @endisset

                {!!  App\Helpers\Helper::brbrToP($scene->text) !!}

                @isset($scene->image)
                <img class="mr-auto" src="/img/{{ $scene->image }}" alt="" >
                @endisset

            </div>
            <div class="accordion mt-2 pb-3 w-100" id="questions">
                @foreach ($scene->questions() as $question)
                    <div class="card" id="question-show">
                        <div class="card-header p-0" id="heading{{ $loop->iteration }}">
                                <button class="btn btn-link btn-lg" type="button" data-toggle="collapse" data-target="#collapse{{ $loop->iteration }}" aria-expanded="true" aria-controls="collapse{{ $loop->iteration }}">
                                    Question {{ $loop->iteration }}
                                </button>
                        </div>
                        <div id="collapse{{ $loop->iteration }}"
                             @if($loop->first)
                                class="collapse show"
                             @else
                                class="collapse"
                             @endif
                             aria-labelledby="heading{{ $loop->iteration }}" data-parent="#questions">
                             <div class="card-body">
                                @include('question.show',['question' => $question])
                                 {{--
                                 {!! App\Helpers\Helper::brToSpace($question->text) !!}
                                 @foreach ($question->answers() as $answer)
                                    <div class="input-group-prepend mb-1">
                                        <div class="input-group-text">
                                            <input type="checkbox" aria-label="">
                                            <div class="pl-3 py-0">{{ $answer->text }}</div>
                                        </div>
                                    </div>
                                 @endforeach
                                 <button class="btn btn-outline-danger px-4 py-1 mt-2">Done</button>
                                 <div class="mt-2 px-3 explanation">
                                     {!! $question->explanation !!}
                                 </div>
                                 --}}
                             </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @break
        @endswitch

        </div>
    </div>
@endsection
