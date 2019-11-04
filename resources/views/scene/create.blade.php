{{-- scene.create
     Create a scene of any type.
     input: $exam_id, $sidebar, optional($scene)
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

                    @include('scene.type1.form')

                    <div class="form-group row">
                        <label class="col-md-2 pt-2 control-label" for="submit"></label>
                        <div class="col-md-10 pl-0">
                            <button id="submit" name="submit" class="btn btn-primary mx-0">Create Scene</button>
                            <a class="btn btn-primary ml-3 px-4" href="/exam/{{ $exam_id }}/scene" role="button">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include('scene.edit_scripts')
