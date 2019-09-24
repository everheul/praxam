{{-- scene.edit.type1
     Edit a scene of type 1, its question and answers.
     input: $scene, $sidebar, $lastpage
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Edit Scene {{ $scene->id }}, Type 1</h3>

                <form method="post" action="/scene/{{ $scene->id }}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="scene[id]" type="hidden" value="{{ $scene->id }}">
                    <input name="lastpage" type="hidden" value="{{ $lastpage }}">
                    <div class="form-group row">
                        <label for="headid" class="col-sm-2 col-form-label">Scene Head</label>
                        <div class="col-sm-10">
                            <input name="scene[head]" type="text" class="form-control" id="headid" placeholder="Scene Head" value="{{ $scene->head }}">
                        </div>
                    </div>
                    @include('question.edit.type' . $scene->questions[0]->question_type_id, ['question' => $scene->questions[0]])
                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="submit">Control</label>
                        <div class="col-sm-10">
                            <button id="submit" name="submit" class="btn btn-primary mr-1">Save Changes</button>
                            <button type="reset" class="btn btn-primary mr-1">Undo Changes</button>
                            <a class="btn btn-primary" href="/scene/show/{{ $scene->id }}" role="button">Cancel</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@include('scene.edit.scripts')
