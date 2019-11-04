{{-- scene.type2.edit
     Edit a scene of type 2.
     input: $scene (with: exam, questions, answers), $sidebar
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>{{ $pagehead }}</h3>
                <hr />

                <form method="post" action="/exam/{{ $scene->exam_id }}/scene/{{ $scene->id }}/update" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="exam_id" type="hidden" value="{{ $scene->exam_id }}">
                    <input name="scene_id" type="hidden" value="{{ $scene->id }}">

                    @include('scene.type1.form')
                    @include('scene.type2.form')

                    <div class="form-group row mb-0">
                        <label class="col-md-2 pt-2 control-label" for="submit"></label>
                        <div class="col-md-10 pl-0">
                            <button name="save_stay" type="submit" class="btn btn-primary">Save &amp; Stay</button>
                            <button name="save_show" type="submit" class="btn btn-primary ml-2">Save &amp; Show</button>
                            <a class="btn btn-primary ml-2 px-4" href="/exam/{{ $scene->exam_id }}/scene" role="button">Cancel</a>
                        </div>
                    </div>

                </form>
                <hr />

                @include('scene.questions')

            </div>
        </div>
    </div>
@endsection

@include('scene.edit_scripts')
