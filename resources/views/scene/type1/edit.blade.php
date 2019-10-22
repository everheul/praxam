{{-- scene.type1.edit
     Edit a scene of type 1, its question and answers.
     input: $scene (with: exam, questions, answers), $sidebar

                            <a class="btn btn-danger" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/destroy" role="button" onclick="return confirm(&quot;Click OK to delete this Scene. Warning: this cannot be undone!&quot;)">Delete</a>
     todo: add/delete/edit question options
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Edit Scene #{{ $scene->id }}</h3>
                <hr />
                <form method="post" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="scene[exam_id]" type="hidden" value="{{ $scene->exam_id }}">
                    <input name="scene[id]" type="hidden" value="{{ $scene->id }}">

                    {{-- HEAD --}}
                    <div class="form-group row">
                        <label for="headid" class="col-sm-2 col-form-label">Scene Head</label>
                        <div class="col-sm-5">
                            <input name="scene[head]" value="{{ $scene->head }}" type="text" class="form-control" id="head" placeholder="Scene Head">
                        </div>
                        <div class="col-sm-5 form-text">
                            <span class="text-secondary">The <b>Scene Head</b> stands on top of your scene, and is visible in your scenes-list.
                                Try to keep it as short, meaningful and unique as possible.
                                {!! $errors->first('head', '<span class="text-danger">:message</span>') !!}</span>
                        </div>
                    </div>

                    {{-- TYPE --}}
                    <div class="form-group row {{ $errors->has('scene_type_id') ? 'has-error' : '' }}">
                        <label for="scene_type_id" class="col-md-2 pt-2 control-label">Scene Type</label>
                        <div class="col-md-5">
                            <select name="scene[scene_type_id]" class="form-control" id="scene_type_id">
                                @foreach ($scene_types as $key => $scene_type)
                                    <option value="{{ $key }}"{{ $key === $scene->scene_type_id ? ' selected' : '' }}>{{ $scene_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5 text-secondary">
                            A <b>Single Question Scene</b> contains just one question, of any kind.
                            <b>Multi Question Scenes</b> can have more questions about one story - which can include instructions and a picture of its own.
                        </div>
                    </div>

                    {{-- QUESTION --}}
                    <div class="form-group row">
                        <label for="headid" class="col-sm-2 col-form-label">Question</label>
                        <div class="col-sm-10">

                            @if($scene->questions->isEmpty())
                                {{-- button: Add Question, with Question Type --}}
                                <a class="btn btn-primary" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/create" role="button">Add Question</a>
                            @else
                                {{-- button: Delete Question --}}
                                @foreach($scene->questions as $question)
                                    <a class="btn btn-danger" href="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/question/{{ $question->id }}/destroy" role="button">Delete Question</a>
                                    @include('question.type' . $question->question_type_id . '.edit')
                                @endforeach
                            @endif

                        </div>
                    </div>
                    <hr />
                    {{-- CONTROL --}}
                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="submit"></label>
                        <div class="col-sm-10">
                            <button id="submit" name="submit" class="btn btn-primary mr-1">Save Changes</button>
                            <a class="btn btn-primary ml-2 px-4" href="/exam/{{ $scene->exam_id }}/scene" role="button">Cancel</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

@include('scene.edit_scripts')
