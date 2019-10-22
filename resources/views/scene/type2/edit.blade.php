{{-- scene.type2.edit
     Edit a scene of type 2.
     input: $scene (with: exam, questions, answers), $sidebar
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Edit Scene #{{ $scene->id }}, Type 1</h3>
                <form method="post" action="/exam/{{ $exam_id }}/scene/{{ $scene->id }}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="scene[id]" type="hidden" value="{{ $scene->id }}">
                    <div class="form-group row">
                        <label for="headid" class="col-sm-2 col-form-label">Scene Head</label>
                        <div class="col-sm-10">
                            <input name="scene[head]" type="text" class="form-control" id="headid" placeholder="Scene Head" value="{{ $scene->head }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="textid" class="col-sm-2 col-form-label">Scene Text</label>
                        <div class="col">
                            <textarea id="textid" class="ckeditor w-100" placeholder="Scene Text" rows="8" >
                                {!! $scene->text !!}
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="instid" class="col-sm-2 col-form-label">Instructions</label>
                        <div class="col">
                            <textarea id="instid" class="ckeditor w-100" placeholder="Scene Instructions" rows="8" >
                                {!! $scene->instructions !!}
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="imageid" class="col col-form-label">Image</label>
                        <div class="col-sm-10">
                            <input name="image" type="text" class="form-control" placeholder="No Image" value="{{ $scene->image }}">
                            <input name="newimage" type="file" class="form-control-file m-1" id="imageid">
                        </div>
                    </div>
{{--
 todo:
    - split template into edit/form templates and add create/form
    - setup accordion to easily change the order of the questions
    - show / edit / delete buttins for all questions
    - add question button
 --}}
                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="submit">Control</label>
                        <div class="col-sm-10">
                            <button id="submit" name="submit" class="btn btn-primary mr-1">Save Changes</button>
                            <button type="reset" class="btn btn-primary mr-1">Undo Changes</button>
                            <a class="btn btn-primary" href="/scene/{{ $scene->id }}/show" role="button">Cancel</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@include('scene.edit_scripts')
