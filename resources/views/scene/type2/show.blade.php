{{-- scene.type2.show
     Show a scene of type 2, its questions and their answers.
     input: $praxscene, $sidebar, $action
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div id="scene-show" class="card p-4 w-100">
                @isset($praxscene->scene->head)
                <h3>{{ $praxscene->scene->head }}</h3>
                @endisset
                @isset($praxscene->scene->instructions)
                <div class="instructions">{!! $praxscene->scene->instructions !!}</div>
                @endisset
                {!!  App\Helpers\Helper::brbrToP($praxscene->scene->text) !!}
                @isset($praxscene->scene->image)
                <img class="mr-auto" src="/img/{{ $praxscene->scene->image }}"{{ $praxscene->getImageSizeStr() }} alt="" >
                @endisset
                <div class="row justify-content-center px-3">
                    <div id="accordion" class="accordion mt-2 w-100">
                        @foreach ($praxscene->praxquestions as $praxquestion)
                            <h3>Question {{ $loop->iteration }}{!! $praxquestion->isCheckedStr() !!}</h3>
                            <div class="w-100">
                                @include('question.type' . $praxquestion->question->question_type_id . '.show')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('scene.show.scripts')
