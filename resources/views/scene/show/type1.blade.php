{{-- scene.show.type1
     Show a scene of type 1, its question and answers.
     input: $scene, $userScene, $sidebar, $action
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div id="scene-show" class="card p-4 w-100">
                @isset($scene->head)
                    <h3>{{ $scene->head }}</h3>
                @endisset
                @include('question.show.type' . $scene->first_question()->question_type_id, ['question' => $scene->first_question()])
            </div>
        </div>
    </div>
@endsection

@include('scene.show.scripts')
