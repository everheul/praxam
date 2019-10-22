{{-- scene.create
     Create a scene of any type.
     input: $exam_id, $sidebar
--}}

@extends('layouts.exam')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="card w-100 p-3 appcolor">
                <h3>Create New Scene</h3>
                <hr />
                <form method="post" action="/exam/{{ $exam_id }}/scene/store" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="exam_id" type="hidden" value="{{ $exam_id }}">
                    <div class="form-group row">
                        <label for="headid" class="col-sm-2 col-form-label">Scene Head</label>
                        <div class="col-sm-5">
                            <input name="head" type="text" class="form-control" id="head" placeholder="Scene Header">
                        </div>
                        <div class="col-sm-5 form-text">
                            <span class="text-secondary">The <b>Scene Head</b> stands on top of your scene, and is visible in your scenes-list.
                                Try to keep it as short, meaningful and unique as possible.
                            {!! $errors->first('head', '<span class="text-danger">:message</span>') !!}</span>
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('scene_type_id') ? 'has-error' : '' }}">
                        <label for="scene_type_id" class="col-md-2 pt-2 control-label">Scene Type</label>
                        <div class="col-md-5">
                            <select class="form-control" id="scene_type_id" name="scene_type_id">
                                @foreach ($scene_types as $key => $scene_type)
                                    <option value="{{ $key }}"{{ empty($key)?' selected' : '' }}>{{ $scene_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5 text-secondary">
                            A <b>Single Question Scene</b> contains just one question, of any kind.
                            <b>Multi Question Scenes</b> can have more questions about one story - which can include instructions and a picture of its own.
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="submit"></label>
                        <div class="col-sm-10">
                            <button id="submit" name="submit" class="btn btn-primary mr-1">Create Scene</button>
                            <a class="btn btn-primary ml-2 px-4" href="/exam/{{ $exam_id }}/scene" role="button">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
