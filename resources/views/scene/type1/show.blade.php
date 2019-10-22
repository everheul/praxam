{{-- scene.type1.show
     Show a scene of type 1, its question and answers. Each question is a form.
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
                @include('question.type' . $praxscene->praxquestion()->question->question_type_id . '.show', ['praxquestion' => $praxscene->praxquestion() ])
            </div>
        </div>
    </div>
@endsection

@include('scene.show_scripts')
